<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit(1);
}

require dirname(__DIR__) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

$apply = in_array('--apply', $argv, true);
$loco_plugin = 'loco-translate/loco.php';
$loco_file = WP_PLUGIN_DIR . '/' . $loco_plugin;

$pages = array(
    626 => array('home', 'home', 'Acasă'),
    7   => array('shop', 'magazin', 'Magazin'),
    8   => array('cart', 'cos', 'Coș'),
    9   => array('checkout', 'finalizare-comanda', 'Finalizarea comenzii'),
    10  => array('my-account', 'contul-meu', 'Contul meu'),
    303 => array('blog', 'jurnal', 'Jurnal'),
    628 => array('about-us', 'despre-noi', 'Despre noi'),
    630 => array('contact-us', 'contact', 'Contact'),
);

$posts = array(
    1   => array('hello-world', 'idei-simple-pentru-ferestre'),
    123 => array('the-yinki-ilori-colour-explosion', 'texturi-si-culori-pentru-o-casa-echilibrata'),
    127 => array('how-to-style-a-console-table', 'cum-alegi-perdelele-potrivite'),
);

$terms = array(
    16 => array('news', 'noutati', 'Noutăți'),
    17 => array('useful', 'ghiduri', 'Ghiduri'),
);

$catalogs = array(
    WP_LANG_DIR . '/loco/themes/blocksy-ro_RO.po',
    WP_LANG_DIR . '/loco/plugins/blocksy-companion-ro_RO.po',
    WP_LANG_DIR . '/loco/plugins/woocommerce-ro_RO.po',
);

if (! is_file($loco_file)) {
    throw new RuntimeException('Loco Translate is not installed.');
}

foreach ($catalogs as $catalog) {
    if (! is_file($catalog)) {
        throw new RuntimeException('Missing Loco catalog: ' . $catalog);
    }
}

if (! function_exists('loco_autoload')) {
    require_once $loco_file;
}

foreach ($catalogs as $catalog) {
    $po = Loco_gettext_Data::fromSource(file_get_contents($catalog));
    if ('' === $po->msgfmt()) {
        throw new RuntimeException('Loco produced an empty catalog: ' . $catalog);
    }
}

foreach ($pages + $posts as $post_id => $change) {
    $post = get_post($post_id);
    $old_slug = $change[0];
    $new_slug = $change[1];

    if (! $post || ($post->post_name !== $old_slug && $post->post_name !== $new_slug)) {
        throw new RuntimeException('Unexpected current slug for post ' . $post_id . '.');
    }

    $collision = get_page_by_path($new_slug, OBJECT, array('page', 'post', 'product'));
    if ($collision && (int) $collision->ID !== $post_id) {
        throw new RuntimeException('Slug collision for ' . $new_slug . '.');
    }
}

foreach ($terms as $term_id => $change) {
    $term = get_term($term_id, 'category');
    $collision = get_term_by('slug', $change[1], 'category');

    if (! $term || ($term->slug !== $change[0] && $term->slug !== $change[1])) {
        throw new RuntimeException('Unexpected current category slug for term ' . $term_id . '.');
    }

    if ($collision && (int) $collision->term_id !== $term_id) {
        throw new RuntimeException('Category slug collision for ' . $change[1] . '.');
    }
}

$permalink_structure = wc_get_permalink_structure();
$curtain_category = get_term(34, 'product_cat');
$curtain_category_url = get_term_link($curtain_category);
if (is_wp_error($curtain_category_url)) {
    throw new RuntimeException($curtain_category_url->get_error_message());
}

$home_post = get_post(626);
$home_style_revision = get_post(1275);
$restore_home_stackable_markup = $home_post
    && 60944 === strlen($home_post->post_content)
    && ! str_contains($home_post->post_content, '<style');

if (
    $restore_home_stackable_markup
    && (! $home_style_revision || 'revision' !== $home_style_revision->post_type || 626 !== (int) $home_style_revision->post_parent || ! str_contains($home_style_revision->post_content, '<style'))
) {
    throw new RuntimeException('The intact homepage Stackable revision could not be verified.');
}

if (! $apply) {
    echo "Dry run OK. Would activate Loco Translate, set ro_RO, update 11 page/post slugs, 2 category slugs, WooCommerce bases, internal links, and compile 3 Loco catalogs.\n";
    echo 'WooCommerce bases: ' . $permalink_structure['product_rewrite_slug'] . ', ' . $permalink_structure['category_rewrite_slug'] . "\n";
    echo 'Perdele category URL: ' . $curtain_category_url . "\n";
    if ($restore_home_stackable_markup) {
        echo "Would restore the intact Stackable markup for Home from revision 1275.\n";
    }
    echo "Run with --apply to make these changes.\n";
    exit(0);
}

$activation = is_plugin_active($loco_plugin) ? null : activate_plugin($loco_plugin);
if (is_wp_error($activation)) {
    throw new RuntimeException($activation->get_error_message());
}

if (! function_exists('loco_autoload')) {
    require_once $loco_file;
}

if (! class_exists('Loco_gettext_Data') || ! class_exists('Loco_gettext_Compiler')) {
    throw new RuntimeException('Loco Translate compiler could not be loaded.');
}

update_option('WPLANG', 'ro_RO');

foreach ($pages as $post_id => $change) {
    $result = wp_update_post(
        array('ID' => $post_id, 'post_name' => $change[1], 'post_title' => $change[2]),
        true
    );
    if (is_wp_error($result)) {
        throw new RuntimeException($result->get_error_message());
    }
}

update_post_meta(626, '_yoast_wpseo_title', 'Perdele și draperii la comandă în Brașov | Brodart');
update_post_meta(626, '_yoast_wpseo_metadesc', 'Descoperă perdele și draperii pentru casa ta. Alege texturile potrivite pentru lumină și intimitate. Cere consultanță pentru configurarea lor în Brașov.');

foreach ($posts as $post_id => $change) {
    $result = wp_update_post(array('ID' => $post_id, 'post_name' => $change[1]), true);
    if (is_wp_error($result)) {
        throw new RuntimeException($result->get_error_message());
    }
}

foreach ($terms as $term_id => $change) {
    $result = wp_update_term($term_id, 'category', array('slug' => $change[1], 'name' => $change[2]));
    if (is_wp_error($result)) {
        throw new RuntimeException($result->get_error_message());
    }
}

$woocommerce_permalinks = get_option('woocommerce_permalinks', array());
$woocommerce_permalinks['product_base'] = 'produs';
$woocommerce_permalinks['category_base'] = 'categorii-produse';
update_option('woocommerce_permalinks', $woocommerce_permalinks);
update_option('category_base', 'categorii');

$replacements = array(
    home_url('/shop-2/')          => home_url('/magazin/'),
    home_url('/product-category/') => home_url('/categorii-produse/'),
    home_url('/my-account/')      => home_url('/contul-meu/'),
    home_url('/about-us/')        => home_url('/despre-noi/'),
    home_url('/contact-us/')       => home_url('/contact/'),
    home_url('/checkout/')        => home_url('/finalizare-comanda/'),
    home_url('/category/news/')   => home_url('/categorii/noutati/'),
    home_url('/category/useful/') => home_url('/categorii/ghiduri/'),
    home_url('/product/')         => home_url('/produs/'),
    home_url('/category/')        => home_url('/categorii/'),
    home_url('/cart/')            => home_url('/cos/'),
    home_url('/blog/')            => home_url('/jurnal/'),
    home_url('/shop/')            => home_url('/magazin/'),
);

global $wpdb;
$content_posts = $wpdb->get_results(
    "SELECT ID, post_content FROM {$wpdb->posts} WHERE post_type IN ('page', 'post') AND post_status IN ('publish', 'draft', 'private')"
);
foreach ($content_posts as $content_post) {
    $source_content = (int) $content_post->ID === 626 && $restore_home_stackable_markup
        ? $home_style_revision->post_content
        : $content_post->post_content;
    $content = str_replace(array_keys($replacements), array_values($replacements), $source_content);
    if ($content !== $content_post->post_content) {
        $updated = $wpdb->update(
            $wpdb->posts,
            array('post_content' => $content),
            array('ID' => (int) $content_post->ID),
            array('%s'),
            array('%d')
        );
        if (false === $updated) {
            throw new RuntimeException('Could not update post content without filtering serialized block markup.');
        }
        clean_post_cache((int) $content_post->ID);
    }
}

$menu_ids = $wpdb->get_col(
    $wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value LIKE %s",
        '_menu_item_url',
        '%' . $wpdb->esc_like(home_url('/shop/')) . '%'
    )
);
foreach ($menu_ids as $menu_id) {
    $url = get_post_meta((int) $menu_id, '_menu_item_url', true);
    update_post_meta((int) $menu_id, '_menu_item_url', str_replace(array_keys($replacements), array_values($replacements), $url));
}

function brodart_localize_homepage_option($value, array $replacements)
{
    if (is_string($value)) {
        return str_replace(array_keys($replacements), array_values($replacements), $value);
    }

    if (is_array($value)) {
        foreach ($value as $key => $item) {
            $value[$key] = brodart_localize_homepage_option($item, $replacements);
        }
    }

    return $value;
}

$block_widgets = get_option('widget_block', array());
update_option('widget_block', brodart_localize_homepage_option($block_widgets, $replacements));

foreach ($catalogs as $catalog) {
    $po = Loco_gettext_Data::fromSource(file_get_contents($catalog));
    $compiler = new Loco_gettext_Compiler(new Loco_fs_File($catalog));
    $compiler->writeAll($po);
}

flush_rewrite_rules(false);
wp_cache_flush();

echo "Applied Romanian frontend strings and URLs; Loco Translate is active and catalogs were compiled.\n";
