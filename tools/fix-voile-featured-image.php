<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit(1);
}

require dirname(__DIR__) . '/wp-load.php';

if (! class_exists('WooCommerce')) {
    throw new RuntimeException('WooCommerce is not active.');
}

$product_id = 821;
$expected_current_image = 931;
$featured_image_id = 1144;
$hover_image_id = 1145;
$apply = in_array('--apply', $argv, true);
$product = wc_get_product($product_id);

if (! $product instanceof WC_Product || 'draperie-voile' !== $product->get_slug()) {
    throw new RuntimeException('The expected Draperie Voile product was not found.');
}

$current_image_id = (int) $product->get_image_id();
$featured_file = basename((string) get_attached_file($featured_image_id));
$gallery_ids = array_map('intval', $product->get_gallery_image_ids());

if (! in_array($current_image_id, array($expected_current_image, $featured_image_id), true)) {
    throw new RuntimeException('The product featured image changed unexpectedly.');
}

if ('14-voile-r1700_4.jpg' !== $featured_file || ! in_array($hover_image_id, $gallery_ids, true)) {
    throw new RuntimeException('The selected main/hover image pair could not be verified.');
}

if (! $apply) {
    echo "Dry run OK. Draperie Voile would use 14-voile-r1700_4.jpg as its featured image and keep 14-voile-r1700_5.jpg first in the gallery.\n";
    echo "Run with --apply to make the change.\n";
    exit(0);
}

set_post_thumbnail($product_id, $featured_image_id);
update_post_meta($featured_image_id, '_wp_attachment_image_alt', 'Draperie Voile R1700 la fereastră.');
clean_post_cache($product_id);

echo "Updated Draperie Voile featured image to 14-voile-r1700_4.jpg.\n";
