<?php

declare(strict_types=1);

require dirname(__DIR__) . '/wp-load.php';

if (! class_exists('WooCommerce')) {
    fwrite(STDERR, "WooCommerce is not active.\n");
    exit(1);
}

const BRODART_FI3_REPORT = __DIR__ . '/../docs/FEATURED-IMAGE-3-REPORT.csv';

$apply = in_array('--apply', $argv, true);

/**
 * Best-effort original filename for an attachment: the import meta first,
 * then the physical attached file basename.
 */
function brodart_fi3_filename(int $attachment_id): string
{
    $source = get_post_meta($attachment_id, '_brodart_source_file', true);
    if (is_string($source) && '' !== $source) {
        return strtolower($source);
    }
    $file = get_post_meta($attachment_id, '_wp_attached_file', true);
    return is_string($file) && '' !== $file ? strtolower(basename($file)) : '';
}

/**
 * Splits a filename into [base, numeric suffix].
 * "14-socotra-v1_3.jpg" -> ["14-socotra-v1", 3]; "14-socotra-v1.jpg" -> ["14-socotra-v1", null].
 *
 * @return array{0: string, 1: ?int}
 */
function brodart_fi3_parse(string $filename): array
{
    if (preg_match('/^(?<base>.+)_(?<n>\d+)\.[a-z0-9]+$/', $filename, $m)) {
        return array($m['base'], (int) $m['n']);
    }
    return array((string) preg_replace('/\.[a-z0-9]+$/', '', $filename), null);
}

/**
 * Decides the new featured image for a product or variation.
 *
 * Rule 1: the image with suffix `_3` from the same group (SKU base) as the current image.
 * Rule 2 (fallback): the 3rd image in the object's current display order
 * ([featured, ...gallery]).
 *
 * @param array<int, string> $filenames Attachment ID => lowercase filename, product-wide.
 * @return array{0: string, 1: int, 2: string} [rule, new_image_id, reason]
 */
function brodart_fi3_resolve(WC_Product $object, array $filenames): array
{
    $image_id = (int) $object->get_image_id();
    $gallery  = array_map('intval', $object->get_gallery_image_ids());
    $sequence = array_values(array_unique(array_filter(array_merge(array($image_id), $gallery))));

    if ($image_id <= 0 || ! isset($filenames[$image_id])) {
        return array('skip', 0, 'fara imagine featured cunoscuta');
    }

    [$base] = brodart_fi3_parse($filenames[$image_id]);

    $candidate = 0;
    foreach ($filenames as $id => $file) {
        [$file_base, $suffix] = brodart_fi3_parse($file);
        if ($file_base === $base && 3 === $suffix && (0 === $candidate || $id < $candidate)) {
            $candidate = (int) $id;
        }
    }
    if ($candidate > 0) {
        return $candidate === $image_id
            ? array('already', $candidate, '')
            : array('_3', $candidate, '');
    }

    if (count($sequence) >= 3) {
        $third = (int) $sequence[2];
        if (isset($filenames[$third])) {
            return array('fallback-3rd', $third, '');
        }
    }

    return array('skip', 0, sprintf('fara _3 si doar %d imagine/i in total', count($sequence)));
}

/**
 * Applies the featured swap: new image becomes featured, the old one takes
 * its slot in the gallery so the display order stays stable and no image
 * is shown twice.
 */
function brodart_fi3_apply(WC_Product $object, int $new_image_id): void
{
    $old_image_id = (int) $object->get_image_id();
    $gallery      = array_map('intval', $object->get_gallery_image_ids());

    $position = array_search($new_image_id, $gallery, true);
    if (false !== $position) {
        $gallery[$position] = $old_image_id;
    } elseif ($old_image_id > 0) {
        array_unshift($gallery, $old_image_id);
    }

    $object->set_image_id($new_image_id);
    $object->set_gallery_image_ids(array_values(array_unique($gallery)));
    $object->save();
}

$product_ids = get_posts(array(
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'orderby'        => 'ID',
    'order'          => 'ASC',
));

$report  = array();
$summary = array('_3' => 0, 'fallback-3rd' => 0, 'already' => 0, 'skip' => 0);

foreach ($product_ids as $product_id) {
    $product = wc_get_product((int) $product_id);
    if (! $product instanceof WC_Product) {
        continue;
    }

    $attachment_ids = get_posts(array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => -1,
        'post_parent'    => $product_id,
        'fields'         => 'ids',
        'orderby'        => 'ID',
        'order'          => 'ASC',
    ));

    // Include featured/gallery images even when orphaned (post_parent = 0, e.g. Amalfi).
    $extra_ids = array_merge(
        array((int) $product->get_image_id()),
        array_map('intval', $product->get_gallery_image_ids())
    );
    $children = array_map('intval', $product->get_children());
    foreach ($children as $variation_id) {
        $variation = wc_get_product($variation_id);
        if ($variation instanceof WC_Product) {
            $extra_ids = array_merge(
                $extra_ids,
                array((int) $variation->get_image_id()),
                array_map('intval', $variation->get_gallery_image_ids())
            );
        }
    }

    $filenames = array();
    foreach (array_values(array_unique(array_filter(array_merge($attachment_ids, $extra_ids)))) as $attachment_id) {
        $filename = brodart_fi3_filename((int) $attachment_id);
        if ('' !== $filename) {
            $filenames[(int) $attachment_id] = $filename;
        }
    }

    $objects = array($product);
    foreach ($children as $variation_id) {
        $variation = wc_get_product($variation_id);
        if ($variation instanceof WC_Product) {
            $objects[] = $variation;
        }
    }

    foreach ($objects as $object) {
        [$rule, $new_image_id, $reason] = brodart_fi3_resolve($object, $filenames);
        $summary[$rule] = ($summary[$rule] ?? 0) + 1;

        $old_image_id = (int) $object->get_image_id();
        $scope        = $object instanceof WC_Product_Variation
            ? 'variation ' . $object->get_sku()
            : 'product';

        if ($apply && in_array($rule, array('_3', 'fallback-3rd'), true) && $new_image_id > 0) {
            brodart_fi3_apply($object, $new_image_id);
        }

        $report[] = array(
            'product_id'   => $product_id,
            'product_name' => $product->get_name(),
            'scope'        => $scope,
            'object_id'    => $object->get_id(),
            'rule'         => $rule,
            'old_image'    => $filenames[$old_image_id] ?? (string) $old_image_id,
            'new_image'    => $new_image_id > 0 ? ($filenames[$new_image_id] ?? (string) $new_image_id) : '',
            'note'         => $reason,
        );
    }
}

$handle = fopen(BRODART_FI3_REPORT, 'wb');
fputcsv($handle, array('product_id', 'product_name', 'scope', 'object_id', 'rule', 'old_image', 'new_image', 'note'), ';');
foreach ($report as $row) {
    fputcsv($handle, $row, ';');
}
fclose($handle);

printf("%s\n", $apply ? 'MODE: APPLY (changes written)' : 'MODE: DRY-RUN (no changes written)');
printf(
    "Rules: _3 => %d, fallback-3rd => %d, already => %d, skip => %d\n\n",
    $summary['_3'],
    $summary['fallback-3rd'],
    $summary['already'],
    $summary['skip']
);

foreach ($report as $row) {
    if ('already' === $row['rule']) {
        continue;
    }
    printf(
        "%-4s %-28s %-28s %-13s %s -> %s %s\n",
        $row['product_id'],
        mb_strimwidth($row['product_name'], 0, 28, '..'),
        mb_strimwidth($row['scope'], 0, 28, '..'),
        $row['rule'],
        $row['old_image'],
        $row['new_image'],
        '' !== $row['note'] ? '(' . $row['note'] . ')' : ''
    );
}

printf("\nReport: %s\n", BRODART_FI3_REPORT);
