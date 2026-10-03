<?php

/**
 * Plugin Name: Brodart Product Importer
 * Description: Imports validated Mendola product manifests into WooCommerce as drafts.
 * Version: 1.0.0
 * Requires Plugins: woocommerce
 * Requires PHP: 8.0
 * Text Domain: brodart-product-importer
 */

if (! defined('ABSPATH')) {
    exit;
}

final class Brodart_Product_Importer
{
    private const IMAGE_ROOT = 'E:\\update-2022\\Brod-art\\email_photos';
    private const TEMPORARY_PRICE_RON = '100';
    private const MODEL_META = '_brodart_import_model_key';
    private const SOURCE_META = '_brodart_source_url';
    private const IMAGE_META = '_brodart_source_file';

    private const ATTRIBUTES = array(
        'Culoare'             => 'culoare',
        'Repetare model'      => 'repetare-model',
        'Repetare înălțime'   => 'repetare-inaltime',
        'Dimensiune model'    => 'dimensiune-model',
        'Colecție'            => 'colectie',
    );

    public function __construct()
    {
        add_action('admin_menu', array($this, 'register_page'));
        add_filter('woocommerce_get_price_html', array($this, 'append_linear_meter_unit'), 20, 2);
        add_filter('woocommerce_product_single_add_to_cart_text', array($this, 'translate_imported_add_to_cart'), 20, 2);
        add_filter('woocommerce_product_tabs', array($this, 'translate_imported_product_tabs'), 20);
        add_filter('woocommerce_dropdown_variation_attribute_options_args', array($this, 'translate_imported_variation_dropdown'), 20);
        add_filter('woocommerce_available_variation', array($this, 'add_editor_preview_variation_gallery'), 20, 3);
    }

    public function register_page(): void
    {
        add_submenu_page(
            'woocommerce',
            __('Import produse Brodart', 'brodart-product-importer'),
            __('Import Brodart', 'brodart-product-importer'),
            'manage_woocommerce',
            'brodart-product-importer',
            array($this, 'render_page')
        );
    }

    public function render_page(): void
    {
        if (! current_user_can('manage_woocommerce')) {
            wp_die(esc_html__('Nu ai permisiunea de a importa produse.', 'brodart-product-importer'));
        }

        $manifest_json = '';
        $messages = array();
        $result_link = '';

        if (isset($_POST['brodart_import_manifest'])) {
            check_admin_referer('brodart_product_import');
            $manifest_json = wp_unslash($_POST['brodart_import_manifest']);
            $manifest = json_decode($manifest_json, true);

            if (! is_array($manifest)) {
                $messages[] = array('error', __('JSON invalid. Verifică structura și încearcă din nou.', 'brodart-product-importer'));
            } else {
                $validated = $this->validate_manifest($manifest);
                if (is_wp_error($validated)) {
                    $messages[] = array('error', $validated->get_error_message());
                } elseif (isset($_POST['brodart_import_execute'])) {
                    $imported = $this->import_manifest($validated);
                    if (is_wp_error($imported)) {
                        $messages[] = array('error', $imported->get_error_message());
                    } else {
                        $messages[] = array('success', sprintf(
                            __('Produsul a fost importat/actualizat ca draft (ID %d); variații procesate: %d.', 'brodart-product-importer'),
                            $imported['product_id'],
                            $imported['variation_count']
                        ));
                        $result_link = get_edit_post_link($imported['product_id'], '');
                    }
                } else {
                    $messages[] = array('success', sprintf(
                        __('Validare reușită: %1$d variații, %2$d imagini locale. Se va aplica prețul de 100 RON/metru liniar; apasă „Importă ca draft” pentru a continua.', 'brodart-product-importer'),
                        count($validated['variations']),
                        count($validated['image_files'])
                    ));
                }
            }
        }
?>
        <div class="wrap">
            <h1><?php esc_html_e('Import produse Brodart', 'brodart-product-importer'); ?></h1>
            <p><?php esc_html_e('Importul creează produse variabile ca draft, la tariful temporar de 100 RON/metru liniar pentru fiecare variație. Imaginile sunt preluate numai din folderul local aprobat.', 'brodart-product-importer'); ?></p>
            <?php foreach ($messages as $message) : ?>
                <div class="notice notice-<?php echo esc_attr($message[0]); ?>">
                    <p><?php echo esc_html($message[1]); ?></p>
                </div>
            <?php endforeach; ?>
            <?php if ($result_link) : ?>
                <p><a class="button button-primary" href="<?php echo esc_url($result_link); ?>"><?php esc_html_e('Editează produsul', 'brodart-product-importer'); ?></a></p>
            <?php endif; ?>
            <form method="post">
                <?php wp_nonce_field('brodart_product_import'); ?>
                <p><label for="brodart-import-json"><strong><?php esc_html_e('Manifest JSON', 'brodart-product-importer'); ?></strong></label></p>
                <textarea id="brodart-import-json" name="brodart_import_manifest" class="large-text code" rows="24" spellcheck="false"><?php echo esc_textarea($manifest_json); ?></textarea>
                <p>
                    <button type="submit" class="button" name="brodart_import_validate" value="1"><?php esc_html_e('Validează', 'brodart-product-importer'); ?></button>
                    <button type="submit" class="button button-primary" name="brodart_import_execute" value="1"><?php esc_html_e('Importă ca draft', 'brodart-product-importer'); ?></button>
                </p>
            </form>
            <p><?php esc_html_e('Câmpuri obligatorii: model_key, name, source_url, category, attributes, variations. Fiecare variație are sku, name, color, image și alt. local_attributes acceptă Lățime pentru valori nefiltrabile. Prețul este fixat temporar la 100 RON/metru liniar.', 'brodart-product-importer'); ?></p>
        </div>
<?php
    }

    private function validate_manifest(array $manifest)
    {
        if (! class_exists('WooCommerce') || ! function_exists('wc_get_product')) {
            return new WP_Error('woocommerce_missing', __('WooCommerce nu este activ.', 'brodart-product-importer'));
        }

        if ('RON' !== get_woocommerce_currency()) {
            return new WP_Error('currency_mismatch', __('Moneda magazinului nu este RON. Importul este oprit pentru a evita prețuri cu monedă greșită.', 'brodart-product-importer'));
        }

        $required = array('model_key', 'name', 'source_url', 'category', 'attributes', 'variations');
        foreach ($required as $field) {
            if (! isset($manifest[$field]) || '' === $manifest[$field] || array() === $manifest[$field]) {
                return new WP_Error('missing_field', sprintf(__('Lipsește câmpul obligatoriu „%s”.', 'brodart-product-importer'), $field));
            }
        }
        foreach (array('model_key', 'name', 'source_url', 'category') as $field) {
            if (! is_string($manifest[$field])) {
                return new WP_Error('invalid_field_type', sprintf(__('Câmpul „%s” trebuie să fie text.', 'brodart-product-importer'), $field));
            }
        }

        $model_key = sanitize_text_field($manifest['model_key']);
        if (! preg_match('/^[A-Za-z0-9._-]+$/', $model_key)) {
            return new WP_Error('invalid_model_key', __('model_key poate conține doar litere, cifre, punct, cratimă și underscore.', 'brodart-product-importer'));
        }

        $name = sanitize_text_field($manifest['name']);
        $source_url = esc_url_raw($manifest['source_url']);
        if (! $name || 'https' !== wp_parse_url($source_url, PHP_URL_SCHEME) || 'mendolafabrics.ro' !== strtolower((string) wp_parse_url($source_url, PHP_URL_HOST))) {
            return new WP_Error('invalid_source', __('Numele și URL-ul public Mendola Fabrics valid sunt obligatorii.', 'brodart-product-importer'));
        }

        $category = get_term_by('name', sanitize_text_field($manifest['category']), 'product_cat');
        if (! $category || is_wp_error($category)) {
            return new WP_Error('category_missing', __('Categoria indicată nu există în WooCommerce; creeaz-o sau folosește numele unei categorii existente.', 'brodart-product-importer'));
        }

        if (! is_array($manifest['attributes']) || ! is_array($manifest['variations']) || empty($manifest['variations'])) {
            return new WP_Error('invalid_structure', __('attributes și variations trebuie să fie obiecte/listă JSON valide.', 'brodart-product-importer'));
        }

        $attributes = array();
        foreach ($manifest['attributes'] as $label => $values) {
            if (! isset(self::ATTRIBUTES[$label])) {
                return new WP_Error('unknown_attribute', sprintf(__('Atributul „%s” nu este permis de schema importului.', 'brodart-product-importer'), sanitize_text_field($label)));
            }
            $raw_values = is_array($values) ? $values : array($values);
            $values = array();
            foreach ($raw_values as $value) {
                if (! is_string($value) && ! is_numeric($value)) {
                    return new WP_Error('invalid_attribute_value', sprintf(__('Valorile atributului „%s” trebuie să fie text.', 'brodart-product-importer'), $label));
                }
                $value = sanitize_text_field((string) $value);
                if ('' !== $value) {
                    $values[] = $value;
                }
            }
            $values = array_values(array_unique($values));
            if (empty($values)) {
                return new WP_Error('empty_attribute', sprintf(__('Atributul „%s” nu are valori.', 'brodart-product-importer'), $label));
            }
            $attribute_id = wc_attribute_taxonomy_id_by_name(self::ATTRIBUTES[$label]);
            if (! $attribute_id) {
                return new WP_Error('attribute_missing', sprintf(__('Atributul global WooCommerce „%s” nu există.', 'brodart-product-importer'), $label));
            }
            $attributes[$label] = $values;
        }

        if (! isset($attributes['Culoare'])) {
            return new WP_Error('color_missing', __('Atributul global „Culoare” trebuie inclus pentru variații.', 'brodart-product-importer'));
        }

        $local_attributes = array();
        if (isset($manifest['local_attributes'])) {
            if (! is_array($manifest['local_attributes'])) {
                return new WP_Error('invalid_local_attributes', __('local_attributes trebuie să fie un obiect JSON.', 'brodart-product-importer'));
            }
            foreach ($manifest['local_attributes'] as $label => $raw_values) {
                if ('Lățime' !== $label) {
                    return new WP_Error('unknown_local_attribute', sprintf(__('Atributul local „%s” nu este permis.', 'brodart-product-importer'), sanitize_text_field($label)));
                }
                $raw_values = is_array($raw_values) ? $raw_values : array($raw_values);
                $values = array();
                foreach ($raw_values as $value) {
                    if (! is_string($value) && ! is_numeric($value)) {
                        return new WP_Error('invalid_local_attribute_value', sprintf(__('Valorile atributului local „%s” trebuie să fie text.', 'brodart-product-importer'), $label));
                    }
                    $value = sanitize_text_field((string) $value);
                    if ('' !== $value) {
                        $values[] = $value;
                    }
                }
                if (empty($values)) {
                    return new WP_Error('empty_local_attribute', sprintf(__('Atributul local „%s” nu are valori.', 'brodart-product-importer'), $label));
                }
                $local_attributes[$label] = array_values(array_unique($values));
            }
        }

        $image_root = defined('BRODART_PRODUCT_IMPORT_IMAGE_ROOT') ? BRODART_PRODUCT_IMPORT_IMAGE_ROOT : self::IMAGE_ROOT;
        $root = realpath($image_root);
        if (! $root || ! is_dir($root)) {
            return new WP_Error('image_root_missing', __('Folderul local de imagini nu este accesibil. Configurează BRODART_PRODUCT_IMPORT_IMAGE_ROOT în wp-config.php.', 'brodart-product-importer'));
        }

        $variations = array();
        $seen_skus = array();
        $image_files = array();
        foreach ($manifest['variations'] as $variation) {
            if (! is_array($variation)) {
                return new WP_Error('invalid_variation', __('Fiecare variație trebuie să fie un obiect JSON.', 'brodart-product-importer'));
            }
            foreach (array('sku', 'name', 'color', 'image', 'alt') as $field) {
                if (empty($variation[$field]) || ! is_string($variation[$field])) {
                    return new WP_Error('variation_field_missing', sprintf(__('Fiecare variație trebuie să aibă câmpul „%s”.', 'brodart-product-importer'), $field));
                }
            }

            $sku = strtoupper(sanitize_text_field($variation['sku']));
            if (! preg_match('/^[A-Z0-9][A-Z0-9._-]*$/', $sku) || isset($seen_skus[$sku])) {
                return new WP_Error('duplicate_or_invalid_sku', sprintf(__('SKU invalid sau duplicat în manifest: %s.', 'brodart-product-importer'), $sku));
            }
            $seen_skus[$sku] = true;

            $color = sanitize_text_field($variation['color']);
            if (! in_array($color, $attributes['Culoare'], true)) {
                return new WP_Error('variation_color_missing', sprintf(__('Culoarea variației %s lipsește din atributul global Culoare.', 'brodart-product-importer'), $sku));
            }

            $price = wc_format_decimal(self::TEMPORARY_PRICE_RON);
            if (isset($variation['price']) && '' !== $variation['price']) {
                if (! is_numeric($variation['price']) || wc_format_decimal($variation['price']) !== $price) {
                    return new WP_Error('invalid_price', sprintf(__('SKU %1$s trebuie să folosească tariful temporar de %2$s RON/metru liniar.', 'brodart-product-importer'), $sku, $price));
                }
            }

            $variation_source_url = isset($variation['source_url']) ? esc_url_raw($variation['source_url']) : $source_url;
            if (! is_string($variation_source_url) || 'https' !== wp_parse_url($variation_source_url, PHP_URL_SCHEME) || 'mendolafabrics.ro' !== strtolower((string) wp_parse_url($variation_source_url, PHP_URL_HOST))) {
                return new WP_Error('invalid_variation_source', sprintf(__('URL-ul sursei Mendola este invalid pentru SKU %s.', 'brodart-product-importer'), $sku));
            }

            $gallery = isset($variation['gallery']) && is_array($variation['gallery']) ? $variation['gallery'] : array();
            $files = array_merge(array($variation['image']), $gallery);
            $validated_files = array();
            foreach ($files as $filename) {
                if (! is_string($filename) || basename($filename) !== $filename || ! preg_match('/\.(jpe?g|png|webp)$/i', $filename)) {
                    return new WP_Error('invalid_image_name', __('Imaginile trebuie indicate numai prin numele fișierului, cu extensie JPG, PNG sau WebP.', 'brodart-product-importer'));
                }
                $path = realpath($root . DIRECTORY_SEPARATOR . $filename);
                if (! $path || ! is_file($path) || 0 !== strpos(strtolower($path), strtolower($root . DIRECTORY_SEPARATOR))) {
                    return new WP_Error('image_missing', sprintf(__('Imagine locală lipsă sau în afara folderului aprobat: %s.', 'brodart-product-importer'), $filename));
                }
                $validated_files[$filename] = $path;
                $image_files[$filename] = $path;
            }

            $variations[] = array(
                'sku'     => $sku,
                'name'    => sanitize_text_field($variation['name']),
                'color'   => $color,
                'price'   => $price,
                'source_url' => $variation_source_url,
                'image'   => $variation['image'],
                'gallery' => array_values(array_unique(array_slice(array_keys($validated_files), 1))),
                'alt'     => sanitize_text_field($variation['alt']),
            );
        }

        return array(
            'model_key'        => $model_key,
            'name'             => $name,
            'description'      => isset($manifest['description']) ? wp_kses_post($manifest['description']) : '',
            'short_description' => isset($manifest['short_description']) ? wp_kses_post($manifest['short_description']) : '',
            'source_url'       => $source_url,
            'category_id'      => (int) $category->term_id,
            'attributes'       => $attributes,
            'local_attributes' => $local_attributes,
            'variations'       => $variations,
            'seo'              => isset($manifest['seo']) && is_array($manifest['seo']) ? $manifest['seo'] : array(),
            'image_files'      => $image_files,
        );
    }

    private function import_manifest(array $manifest)
    {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $parent_ids = get_posts(array(
            'post_type'      => 'product',
            'post_status'    => array('publish', 'draft', 'pending', 'private'),
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_key'       => self::MODEL_META,
            'meta_value'     => $manifest['model_key'],
        ));
        $parent_id = empty($parent_ids) ? 0 : (int) $parent_ids[0];
        if ($parent_id) {
            $parent = wc_get_product($parent_id);
            if (! $parent || ! $parent->is_type('variable')) {
                return new WP_Error('model_conflict', __('Cheia modelului este deja folosită de un produs care nu este variabil.', 'brodart-product-importer'));
            }
        } else {
            $parent = new WC_Product_Variable();
            $parent->set_status('draft');
        }

        foreach ($manifest['variations'] as $variation_data) {
            $existing_id = wc_get_product_id_by_sku($variation_data['sku']);
            if (! $existing_id) {
                continue;
            }
            $existing_product = wc_get_product($existing_id);
            if (! $parent_id || ! $existing_product || ! $existing_product->is_type('variation') || (int) $existing_product->get_parent_id() !== $parent_id) {
                return new WP_Error('sku_conflict', sprintf(__('SKU %s este deja folosit de alt produs; nu a fost suprascris.', 'brodart-product-importer'), $variation_data['sku']));
            }
        }

        $attachment_ids = array();
        foreach ($manifest['image_files'] as $filename => $path) {
            $existing = get_posts(array(
                'post_type'      => 'attachment',
                'post_status'    => 'inherit',
                'posts_per_page' => 1,
                'fields'         => 'ids',
                'meta_key'       => self::IMAGE_META,
                'meta_value'     => $filename,
            ));
            if (! empty($existing)) {
                $attachment_ids[$filename] = (int) $existing[0];
                continue;
            }

            $temp_path = wp_tempnam($filename);
            if (! $temp_path || ! copy($path, $temp_path)) {
                if ($temp_path && file_exists($temp_path)) {
                    unlink($temp_path);
                }
                return new WP_Error('image_copy_failed', sprintf(__('Nu am putut pregăti copia temporară pentru imaginea %s.', 'brodart-product-importer'), $filename));
            }
            $file = array('name' => $filename, 'tmp_name' => $temp_path);
            $attachment_id = media_handle_sideload($file, 0);
            if (is_wp_error($attachment_id)) {
                if (file_exists($temp_path)) {
                    unlink($temp_path);
                }
                return new WP_Error('media_import_failed', sprintf(__('Nu am putut importa imaginea %1$s: %2$s', 'brodart-product-importer'), $filename, $attachment_id->get_error_message()));
            }
            update_post_meta($attachment_id, self::IMAGE_META, $filename);
            $attachment_ids[$filename] = (int) $attachment_id;
        }

        $parent->set_name($manifest['name']);
        $parent->set_description($manifest['description']);
        $parent->set_short_description($manifest['short_description']);
        $parent->set_category_ids(array($manifest['category_id']));

        $product_attributes = $this->build_product_attributes($manifest['attributes'], $manifest['local_attributes']);
        if (is_wp_error($product_attributes)) {
            return $product_attributes;
        }
        $parent->set_attributes($product_attributes);

        $gallery_ids = array();
        foreach ($manifest['variations'] as $variation) {
            foreach (array_merge(array($variation['image']), $variation['gallery']) as $filename) {
                $gallery_ids[] = $attachment_ids[$filename];
            }
        }
        $gallery_ids = array_values(array_unique($gallery_ids));
        $parent->set_image_id($attachment_ids[$manifest['variations'][0]['image']]);
        $parent->set_gallery_image_ids(array_slice($gallery_ids, 1));

        $parent_id = $parent->save();
        if (! $parent_id) {
            return new WP_Error('product_save_failed', __('WooCommerce nu a putut salva produsul părinte.', 'brodart-product-importer'));
        }

        update_post_meta($parent_id, self::MODEL_META, $manifest['model_key']);
        update_post_meta($parent_id, self::SOURCE_META, $manifest['source_url']);
        update_post_meta($parent_id, '_brodart_import_price_unit', 'linear_meter');
        $this->save_seo($parent_id, $manifest['seo']);

        foreach ($manifest['variations'] as $variation_data) {
            $existing_id = wc_get_product_id_by_sku($variation_data['sku']);
            if ($existing_id) {
                $variation_product = wc_get_product($existing_id);
                if (! $variation_product || ! $variation_product->is_type('variation') || (int) $variation_product->get_parent_id() !== (int) $parent_id) {
                    return new WP_Error('sku_conflict', sprintf(__('SKU %s este deja folosit de alt produs; nu a fost suprascris.', 'brodart-product-importer'), $variation_data['sku']));
                }
            } else {
                $variation_product = new WC_Product_Variation();
                $variation_product->set_parent_id($parent_id);
            }

            $variation_product->set_name($manifest['name'] . ' ' . $variation_data['name']);
            $variation_product->set_sku($variation_data['sku']);
            $variation_product->set_attributes(array('pa_culoare' => sanitize_title($variation_data['color'])));
            $variation_product->set_image_id($attachment_ids[$variation_data['image']]);
            $variation_gallery_ids = array();
            foreach ($variation_data['gallery'] as $filename) {
                $variation_gallery_ids[] = $attachment_ids[$filename];
            }
            $variation_product->set_gallery_image_ids($variation_gallery_ids);
            foreach (array_merge(array($variation_data['image']), $variation_data['gallery']) as $filename) {
                update_post_meta($attachment_ids[$filename], '_wp_attachment_image_alt', $variation_data['alt']);
            }
            $variation_product->set_sale_price('');
            $variation_product->set_regular_price($variation_data['price']);
            $variation_id = $variation_product->save();
            if (! $variation_id) {
                return new WP_Error('variation_save_failed', sprintf(__('WooCommerce nu a putut salva variația %s.', 'brodart-product-importer'), $variation_data['sku']));
            }
            update_post_meta($variation_id, self::MODEL_META, $manifest['model_key']);
            update_post_meta($variation_id, self::SOURCE_META, $variation_data['source_url']);
            update_post_meta($variation_id, '_brodart_import_color', $variation_data['color']);
            delete_post_meta($variation_id, '_brodart_import_gallery_ids');
        }

        WC_Product_Variable::sync($parent_id);

        return array('product_id' => $parent_id, 'variation_count' => count($manifest['variations']));
    }

    public function append_linear_meter_unit(string $price_html, WC_Product $product): string
    {
        if ($product->is_type('variation')) {
            $product = wc_get_product($product->get_parent_id());
        }

        if (! $product || 'linear_meter' !== $product->get_meta('_brodart_import_price_unit', true)) {
            return $price_html;
        }

        return $price_html . ' <span class="brodart-price-unit">' . esc_html__(' / metru liniar', 'brodart-product-importer') . '</span>';
    }

    public function translate_imported_add_to_cart(string $text, WC_Product $product): string
    {
        return $product->get_meta(self::MODEL_META, true) ? __('Adaugă în coș', 'brodart-product-importer') : $text;
    }

    public function translate_imported_product_tabs(array $tabs): array
    {
        global $product;

        if (! $product instanceof WC_Product || ! $product->get_meta(self::MODEL_META, true)) {
            return $tabs;
        }

        $titles = array(
            'description'            => __('Descriere', 'brodart-product-importer'),
            'additional_information' => __('Informații suplimentare', 'brodart-product-importer'),
            'reviews'                => __('Recenzii', 'brodart-product-importer'),
        );
        foreach ($titles as $key => $title) {
            if (isset($tabs[$key])) {
                $tabs[$key]['title'] = $title;
            }
        }

        return $tabs;
    }

    public function translate_imported_variation_dropdown(array $args): array
    {
        if (! isset($args['product'], $args['attribute']) || ! $args['product'] instanceof WC_Product || ! $args['product']->get_meta(self::MODEL_META, true)) {
            return $args;
        }

        if (in_array($args['attribute'], array('pa_culoare', 'Culoare'), true)) {
            $args['show_option_none'] = __('Alege o culoare', 'brodart-product-importer');
        }

        return $args;
    }

    public function add_editor_preview_variation_gallery(array $data, WC_Product $product, WC_Product_Variation $variation): array
    {
        if (! is_preview() || ! current_user_can('edit_post', $product->get_id()) || ! $product->get_meta(self::MODEL_META, true) || ! function_exists('blocksy_render_view')) {
            return $data;
        }

        $gallery_template = get_template_directory() . '/inc/components/woocommerce/single/woo-gallery-template.php';
        if (! is_readable($gallery_template)) {
            return $data;
        }

        global $blocksy_current_variation;
        $previous_variation = $blocksy_current_variation ?? null;
        $thumbnail_priority = has_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails');
        if (false !== $thumbnail_priority) {
            remove_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', $thumbnail_priority);
        }

        $blocksy_current_variation = $variation;
        try {
            $gallery_html = blocksy_render_view(
                $gallery_template,
                array(
                    'product'               => $product,
                    'forced_single'         => true,
                    'skip_default_variation' => true,
                )
            );
        } finally {
            $blocksy_current_variation = $previous_variation;
            if (false !== $thumbnail_priority) {
                add_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', $thumbnail_priority);
            }
        }

        if (is_string($gallery_html) && '' !== $gallery_html) {
            $data['blocksy_gallery_html'] = $gallery_html;
        }

        return $data;
    }

    private function build_product_attributes(array $attributes, array $local_attributes)
    {
        $product_attributes = array();
        foreach ($attributes as $label => $values) {
            $slug = self::ATTRIBUTES[$label];
            $attribute_id = wc_attribute_taxonomy_id_by_name($slug);
            $taxonomy = wc_attribute_taxonomy_name($slug);
            $term_ids = array();

            foreach ($values as $value) {
                $term = get_term_by('name', $value, $taxonomy);
                if (! $term) {
                    $term = wp_insert_term($value, $taxonomy);
                    if (is_wp_error($term)) {
                        return $term;
                    }
                    $term_id = (int) $term['term_id'];
                } else {
                    $term_id = (int) $term->term_id;
                }
                $term_ids[] = $term_id;
            }

            $attribute = new WC_Product_Attribute();
            $attribute->set_id($attribute_id);
            $attribute->set_name($taxonomy);
            $attribute->set_options($term_ids);
            $attribute->set_visible(true);
            $attribute->set_variation('Culoare' === $label);
            $product_attributes[] = $attribute;
        }

        foreach ($local_attributes as $label => $values) {
            $attribute = new WC_Product_Attribute();
            $attribute->set_name($label);
            $attribute->set_options($values);
            $attribute->set_position(count($product_attributes));
            $attribute->set_visible(true);
            $attribute->set_variation(false);
            $product_attributes[] = $attribute;
        }

        return $product_attributes;
    }

    private function save_seo(int $product_id, array $seo): void
    {
        $fields = array(
            'title'       => '_yoast_wpseo_title',
            'description' => '_yoast_wpseo_metadesc',
            'focus_keyphrase' => '_yoast_wpseo_focuskw',
        );
        foreach ($fields as $key => $meta_key) {
            if (isset($seo[$key]) && is_string($seo[$key])) {
                update_post_meta($product_id, $meta_key, sanitize_text_field($seo[$key]));
            }
        }
    }
}

new Brodart_Product_Importer();
