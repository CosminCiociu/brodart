<?php

declare(strict_types=1);

require dirname(__DIR__) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

if (! class_exists('WooCommerce')) {
    fwrite(STDERR, "WooCommerce is not active.\n");
    exit(1);
}

const BRODART_BATCH_IMAGE_ROOT = 'E:\\update-2022\\Brod-art\\email_photos';
const BRODART_BATCH_PRICE = '100';
const BRODART_BATCH_MODEL_META = '_brodart_import_model_key';
const BRODART_BATCH_SOURCE_META = '_brodart_source_url';
const BRODART_BATCH_IMAGE_META = '_brodart_source_file';
const BRODART_BATCH_REPORT = __DIR__ . '/../docs/PRODUCT-IMPORT-BATCH-REPORT.csv';

function brodart_batch_clean(string $value): string
{
    return trim(html_entity_decode(wp_strip_all_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

function brodart_batch_match(string $pattern, string $html): string
{
    return preg_match($pattern, $html, $matches) ? brodart_batch_clean($matches[1]) : '';
}

function brodart_batch_term_id(string $taxonomy, string $name): int
{
    $name = brodart_batch_clean($name);
    if ('' === $name) {
        return 0;
    }
    $term = term_exists($name, $taxonomy);
    if (is_array($term)) {
        return (int) $term['term_id'];
    }
    if (is_int($term)) {
        return $term;
    }
    $created = wp_insert_term($name, $taxonomy);
    return is_wp_error($created) ? 0 : (int) $created['term_id'];
}

function brodart_batch_attribute(string $taxonomy, array $names, bool $variation = false): ?WC_Product_Attribute
{
    $term_ids = array_values(array_filter(array_unique(array_map(
        static fn(string $name): int => brodart_batch_term_id($taxonomy, $name),
        $names
    ))));
    if (empty($term_ids)) {
        return null;
    }

    $attribute = new WC_Product_Attribute();
    $attribute->set_id((int) wc_attribute_taxonomy_id_by_name(str_replace('pa_', '', $taxonomy)));
    $attribute->set_name($taxonomy);
    $attribute->set_options($term_ids);
    $attribute->set_visible(true);
    $attribute->set_variation($variation);
    return $attribute;
}

function brodart_batch_attachment(string $filename, string $path, string $alt, int $parent_id): int
{
    $existing = get_posts(array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_key'       => BRODART_BATCH_IMAGE_META,
        'meta_value'     => $filename,
    ));
    if (! empty($existing)) {
        $attachment_id = (int) $existing[0];
        update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt);
        return $attachment_id;
    }

    $temporary = wp_tempnam($filename);
    if (! $temporary || ! copy($path, $temporary)) {
        if ($temporary && file_exists($temporary)) {
            unlink($temporary);
        }
        return 0;
    }

    $attachment_id = media_handle_sideload(array('name' => $filename, 'tmp_name' => $temporary), $parent_id);
    if (is_wp_error($attachment_id)) {
        if (file_exists($temporary)) {
            unlink($temporary);
        }
        return 0;
    }

    update_post_meta($attachment_id, BRODART_BATCH_IMAGE_META, $filename);
    update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt);
    return (int) $attachment_id;
}

function brodart_batch_parse_page(string $url, string $html): array
{
    $sku = brodart_batch_match('/class=["\'][^"\']*detline[^"\']*["\'][^>]*>.*?Cod articol.*?<span[^>]*class=["\']value["\'][^>]*>(.*?)<\/span>/is', $html);
    $name = brodart_batch_match('/<h1[^>]*class=["\'][^"\']*product_title[^"\']*["\'][^>]*>(.*?)<\/h1>/is', $html);
    $description = brodart_batch_match('/<div[^>]*class=["\'][^"\']*product_description[^"\']*["\'][^>]*>(.*?)<\/div>/is', $html);
    $collection = brodart_batch_match('/<div[^>]*class=["\'][^"\']*product_collection[^"\']*["\'][^>]*>.*?<li>(.*?)<\/li>/is', $html);
    $color = brodart_batch_match('/<span[^>]*class=["\']label["\'][^>]*>Culoare<\/span>.*?<span[^>]*class=["\']value["\'][^>]*>(.*?)<\/span>/is', $html);
    $repeat = brodart_batch_match('/<span[^>]*class=["\']label["\'][^>]*>Repetare model<\/span>.*?<span[^>]*class=["\']value["\'][^>]*>(.*?)<\/span>/is', $html);
    $size = brodart_batch_match('/<span[^>]*class=["\']label["\'][^>]*>Dimensiune model<\/span>.*?<span[^>]*class=["\']value["\'][^>]*>(.*?)<\/span>/is', $html);

    if ('' === $sku) {
        $sku = strtoupper(brodart_batch_match('/\/product\/([^\/]+)\//i', $url));
    }
    $sku = strtoupper($sku);
    if ('' === $name) {
        $name = $sku;
    }

    $model_key = preg_match('/^(14|149)-([A-Z0-9]+)/', $sku, $model_match)
        ? strtoupper($model_match[1] . '-' . $model_match[2])
        : '';
    $is_drapery = (bool) preg_match('/draper/i', $name . ' ' . $description);
    $category = $is_drapery ? 'Draperii' : 'Perdele';

    return array(
        'url'         => $url,
        'sku'         => $sku,
        'model_key'   => $model_key,
        'name'        => $name,
        'description' => $description,
        'color'       => $color,
        'collection'  => preg_replace('/^Colecția\s+/iu', '', $collection),
        'repeat'      => $repeat,
        'size'        => $size,
        'category'    => $category,
    );
}

function brodart_batch_files(string $root): array
{
    $files = array();
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (! $file->isFile() || ! preg_match('/\.(jpe?g|png|webp)$/i', $file->getFilename())) {
            continue;
        }
        if (preg_match('/^(14|149)-[A-Z0-9]+-[A-Z0-9]+(?:_\d+)?\.(?:jpe?g|png|webp)$/i', $file->getFilename())) {
            $files[strtolower($file->getFilename())] = $file->getPathname();
        }
    }
    return $files;
}

function brodart_batch_source_urls(): array
{
    $response = wp_remote_get('https://mendolafabrics.ro/wp-sitemap-posts-product-1.xml', array('timeout' => 30, 'user-agent' => 'Brodart catalog importer/1.0'));
    if (is_wp_error($response)) {
        return array();
    }
    preg_match_all('/<loc>(.*?)<\/loc>/is', wp_remote_retrieve_body($response), $matches);
    return array_values(array_unique(array_map('html_entity_decode', $matches[1] ?? array())));
}

$local_files = brodart_batch_files(BRODART_BATCH_IMAGE_ROOT);
$cache_path = dirname(__DIR__) . '/docs/PUBLIC-PRODUCT-PAGE-CACHE.json';
$cache_json = file_exists($cache_path) ? (string) file_get_contents($cache_path) : '';
$cache_json = preg_replace('/^\xEF\xBB\xBF/', '', $cache_json);
$cached_pages = '' !== $cache_json ? json_decode($cache_json, true) : array();
$cached_pages = is_array($cached_pages) ? $cached_pages : array();
$local_models = array_values(array_unique(array_filter(array_map(
    static function (string $filename): string {
        return preg_match('/^(?<model>(?:14|149)-[A-Z0-9]+)/i', $filename, $matches) ? strtoupper($matches['model']) : '';
    },
    array_keys($local_files)
))));
$products = array();
$report = array();

foreach ($cached_pages as $cached_page) {
    $url = isset($cached_page['url']) && is_string($cached_page['url']) ? $cached_page['url'] : '';
    $html = isset($cached_page['html']) && is_string($cached_page['html']) ? $cached_page['html'] : '';
    if ('' === $url || '' === $html) {
        continue;
    }
    $data = brodart_batch_parse_page($url, $html);
    if ('' === $data['sku'] || '' === $data['model_key'] || '' === $data['color']) {
        $report[] = array('model_key' => $data['model_key'], 'sku' => $data['sku'], 'status' => 'skipped', 'reason' => 'Missing SKU, model or color on public page', 'url' => $url);
        continue;
    }
    $candidate_files = array_filter($local_files, static fn(string $path, string $filename): bool => str_starts_with(strtolower($filename), strtolower($data['sku'])), ARRAY_FILTER_USE_BOTH);
    if (empty($candidate_files)) {
        continue;
    }
    $data['files'] = array();
    foreach ($candidate_files as $filename => $path) {
        $data['files'][$filename] = $path;
    }
    $products[$data['model_key']]['pages'][] = $data;
}

$category_ids = array(
    'Perdele'  => brodart_batch_term_id('product_cat', 'Perdele'),
    'Draperii' => brodart_batch_term_id('product_cat', 'Draperii'),
);

foreach ($products as $model_key => $product_data) {
    $pages = array();
    $seen_skus = array();
    foreach ($product_data['pages'] as $page) {
        if (! is_array($page) || empty($page['sku']) || isset($seen_skus[$page['sku']])) {
            continue;
        }
        $seen_skus[$page['sku']] = true;
        $pages[] = $page;
    }
    if (empty($pages)) {
        continue;
    }
    $first = $pages[0];
    if ('14-ALANA' === $model_key) {
        foreach ($pages as $page) {
            $report[] = array('model_key' => $model_key, 'sku' => $page['sku'], 'status' => 'imported', 'reason' => 'Existing published simple product retained; SKU is already active', 'url' => $page['url']);
        }
        continue;
    }
    $parent_ids = get_posts(array(
        'post_type'      => 'product',
        'post_status'    => array('publish', 'draft', 'pending', 'private'),
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_key'       => BRODART_BATCH_MODEL_META,
        'meta_value'     => $model_key,
    ));
    $parent = ! empty($parent_ids) ? wc_get_product((int) $parent_ids[0]) : new WC_Product_Variable();
    if (! $parent instanceof WC_Product_Variable) {
        $report[] = array('model_key' => $model_key, 'sku' => '', 'status' => 'error', 'reason' => 'Existing model is not variable', 'url' => $first['url']);
        continue;
    }

    $display_model = preg_replace('/^(14|149)-/i', '', $model_key);
    $display_model = ucwords(strtolower(str_replace('-', ' ', $display_model)));
    $type = 'Draperii' === $first['category'] ? 'Draperie' : 'Perdea';
    $parent->set_name($type . ' ' . $display_model);
    $parent->set_status('publish');
    $parent->set_category_ids(array($category_ids[$first['category']] ?: $category_ids['Perdele']));
    $parent->set_description($first['description'] ?: $type . ' ' . $display_model . '.');
    $parent->set_short_description($type . ' ' . $display_model . ', disponibil(ă) în variantele confirmate din catalogul Mendola Fabrics.');

    $colors = array_values(array_unique(array_map(static fn(array $page): string => $page['color'], $pages)));
    $attributes = array();
    $color_attribute = brodart_batch_attribute('pa_culoare', $colors, true);
    if ($color_attribute) {
        $attributes['pa_culoare'] = $color_attribute;
    }
    foreach (array('repeat' => 'pa_repetare-model', 'size' => 'pa_dimensiune-model', 'collection' => 'pa_colectie') as $field => $taxonomy) {
        $values = array_values(array_unique(array_filter(array_map(static fn(array $page): string => $page[$field], $pages))));
        $attribute = brodart_batch_attribute($taxonomy, $values, false);
        if ($attribute) {
            $attributes[$taxonomy] = $attribute;
        }
    }
    $parent->set_attributes($attributes);
    $parent_id = $parent->save();
    update_post_meta($parent_id, BRODART_BATCH_MODEL_META, $model_key);
    update_post_meta($parent_id, BRODART_BATCH_SOURCE_META, $first['url']);
    update_post_meta($parent_id, '_brodart_import_price_unit', 'linear_meter');

    $parent_gallery = array();
    foreach ($pages as $page) {
        if (! is_array($page) || empty($page['sku'])) {
            $report[] = array('model_key' => $model_key, 'sku' => '', 'status' => 'skipped', 'reason' => 'Invalid cached page structure', 'url' => '');
            continue;
        }
        $page_files = isset($page['files']) && is_array($page['files']) ? $page['files'] : array_filter(
            $local_files,
            static fn(string $path, string $filename): bool => str_starts_with(strtolower($filename), strtolower($page['sku'])),
            ARRAY_FILTER_USE_BOTH
        );
        if (empty($page_files)) {
            $report[] = array('model_key' => $model_key, 'sku' => $page['sku'], 'status' => 'skipped', 'reason' => 'No local image matched public SKU', 'url' => $page['url']);
            continue;
        }
        $image_ids = array();
        foreach ($page_files as $filename => $path) {
            $alt = $type . ' ' . $display_model . ' ' . $page['color'] . ', cod ' . $page['sku'] . '.';
            $attachment_id = brodart_batch_attachment(basename($filename), $path, $alt, $parent_id);
            if ($attachment_id) {
                $image_ids[] = $attachment_id;
                $parent_gallery[] = $attachment_id;
            }
        }
        if (empty($image_ids)) {
            $report[] = array('model_key' => $model_key, 'sku' => $page['sku'], 'status' => 'skipped', 'reason' => 'No local image matched public SKU', 'url' => $page['url']);
            continue;
        }

        $variation_id = wc_get_product_id_by_sku($page['sku']);
        $variation = $variation_id ? wc_get_product($variation_id) : new WC_Product_Variation();
        if (! $variation instanceof WC_Product_Variation) {
            $report[] = array('model_key' => $model_key, 'sku' => $page['sku'], 'status' => 'error', 'reason' => 'Existing SKU is not a variation', 'url' => $page['url']);
            continue;
        }
        $variation->set_parent_id($parent_id);
        $variation->set_status('publish');
        $variation->set_name($parent->get_name() . ' ' . $page['color']);
        $variation->set_sku($page['sku']);
        $variation->set_regular_price(BRODART_BATCH_PRICE);
        $variation->set_price(BRODART_BATCH_PRICE);
        $variation->set_attributes(array('pa_culoare' => sanitize_title($page['color'])));
        $variation->set_image_id($image_ids[0]);
        $variation->set_gallery_image_ids(array_slice($image_ids, 1));
        $variation->save();
        update_post_meta($variation->get_id(), BRODART_BATCH_SOURCE_META, $page['url']);
        $report[] = array('model_key' => $model_key, 'sku' => $page['sku'], 'status' => 'imported', 'reason' => 'Published with local images and confirmed public source', 'url' => $page['url']);
    }

    $parent->set_image_id($parent_gallery[0] ?? 0);
    $parent->set_gallery_image_ids(array_values(array_unique(array_slice($parent_gallery, 1))));
    $parent->save();
}

$handle = fopen(BRODART_BATCH_REPORT, 'wb');
fputcsv($handle, array('model_key', 'sku', 'status', 'reason', 'url'), ';');
foreach ($report as $row) {
    fputcsv($handle, $row, ';');
}
fclose($handle);

printf("Processed %d model groups, %d cached source pages, %d report rows.\n", count($products), count($cached_pages), count($report));
