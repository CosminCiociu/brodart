<?php

declare(strict_types=1);

/**
 * Brodart Shop Tweaks
 *
 * - Romanian translations for WooCommerce strings missing from the official
 *   ro_RO pack (mostly new block strings).
 * - Appends "/ metru liniar" to Mendola import prices on catalog pages.
 * - Adds a "NOU" badge for products published in the last 30 days.
 * - Restyles shop UI bits (badges, chips, hover image) to the Brodart palette.
 */

function brodart_global_typography(): void
{
    wp_register_style('brodart-global-typography', false, array(), null);
    wp_enqueue_style('brodart-global-typography');
    wp_add_inline_style(
        'brodart-global-typography',
        ':root, body { --theme-font-family: Arial, Helvetica, sans-serif; }
        h1, h2, h3, h4, h5, h6, .wp-block-pullquote {
            --theme-font-family: Arial, Helvetica, sans-serif;
        }
        .brodart-image-submenu > .sub-menu > li.current-menu-item > .ct-menu-link {
            --theme-link-initial-color: #8B8175;
            --theme-link-hover-color: #8B8175;
        }
        .brodart-image-submenu > .sub-menu > li.current-menu-item .brodart-menu-card__title {
            color: #8B8175;
            font-weight: 600;
            text-decoration: underline;
            text-decoration-color: #A9A296;
            text-underline-offset: 3px;
        }'
    );
}
add_action('wp_enqueue_scripts', 'brodart_global_typography', 100);

/**
 * Localizes the product category taxonomy labels (WooCommerce registers them
 * in English; the block filters use singular_name for group labels).
 */
function brodart_category_labels(): void
{
    global $wp_taxonomies;

    if (isset($wp_taxonomies['product_cat'])) {
        $wp_taxonomies['product_cat']->labels->name          = 'Categorii';
        $wp_taxonomies['product_cat']->labels->menu_name     = 'Categorii';
        $wp_taxonomies['product_cat']->labels->singular_name = 'Categorie';
        $wp_taxonomies['product_cat']->label                 = 'Categorii';
    }
}
add_action('init', 'brodart_category_labels', 30);

/**
 * Reduces the category filter value to the last selected category.
 */
function brodart_normalize_category_filter_value(string $value): string
{
    $categories = array_values(array_filter(array_map('sanitize_title', explode(',', $value))));

    return $categories ? (string) end($categories) : '';
}

function brodart_is_current_shop_filter_menu_item(WP_Post $menu_item): bool
{
    $request = $GLOBALS['brodart_category_filter_request'] ?? null;

    if (is_shop() && is_array($request) && is_string($request['value'] ?? null)) {
        $current_category = brodart_normalize_category_filter_value($request['value']);

        if (in_array($current_category, array('perdele', 'draperii'), true)) {
            $menu_query = wp_parse_url($menu_item->url, PHP_URL_QUERY);

            if (is_string($menu_query)) {
                parse_str($menu_query, $menu_args);
                $menu_category = $menu_args['categorii'] ?? $menu_args['categories'] ?? null;

                if (
                    is_string($menu_category)
                    && brodart_normalize_category_filter_value($menu_category) === $current_category
                ) {
                    return true;
                }
            }
        }
    }

    if (! is_shop() || ! isset($_GET['filter_colectie']) || ! is_string($_GET['filter_colectie'])) {
        return false;
    }

    $current_collection = sanitize_title(wp_unslash($_GET['filter_colectie']));

    if ('' === $current_collection) {
        return false;
    }

    $menu_query = wp_parse_url($menu_item->url, PHP_URL_QUERY);

    if (is_string($menu_query)) {
        parse_str($menu_query, $menu_args);
        $menu_collection = $menu_args['filter_colectie'] ?? null;

        if (is_string($menu_collection) && sanitize_title($menu_collection) === $current_collection) {
            return true;
        }
    }

    return false;
}

function brodart_shop_filter_menu_item_classes(array $classes, WP_Post $menu_item): array
{
    if (
        brodart_is_current_shop_filter_menu_item($menu_item)
        && ! in_array('current-menu-item', $classes, true)
    ) {
        $classes[] = 'current-menu-item';
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'brodart_shop_filter_menu_item_classes', 10, 2);

function brodart_shop_filter_menu_item_link_attributes(array $atts, WP_Post $menu_item): array
{
    if (brodart_is_current_shop_filter_menu_item($menu_item)) {
        $atts['aria-current'] = 'page';
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'brodart_shop_filter_menu_item_link_attributes', 10, 2);

/**
 * Adapts category filter requests to WooCommerce while retaining a Romanian,
 * single-category URL and selection.
 *
 * @param WP $wp WordPress request.
 */
function brodart_prepare_romanian_category_filter_request(WP $wp): void
{
    $shop_path = wp_parse_url(get_permalink(wc_get_page_id('shop')), PHP_URL_PATH);
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $request_path = wp_parse_url($request_uri, PHP_URL_PATH);

    if (
        ! is_string($shop_path)
        || ! is_string($request_path)
        || untrailingslashit($shop_path) !== untrailingslashit($request_path)
    ) {
        return;
    }

    $query = array();
    $parsed_url = wp_parse_url($request_uri);

    if (! empty($parsed_url['query'])) {
        parse_str($parsed_url['query'], $query);
    }

    $parameter = isset($query['categories']) ? 'categories' : 'categorii';

    if (! isset($query[$parameter]) || ! is_string($query[$parameter])) {
        return;
    }

    $requested_category = sanitize_text_field($query[$parameter]);
    $category = brodart_normalize_category_filter_value($requested_category);

    if ('' === $category) {
        return;
    }

    $GLOBALS['brodart_category_filter_request'] = array(
        'parameter' => $parameter,
        'value'     => $requested_category,
        'url'       => $request_uri,
    );

    unset($query['categorii'], $query['categories']);
    $query['categories'] = $category;
    $_SERVER['REQUEST_URI'] = $request_path . '?' . build_query($query);
    unset($_GET['categorii'], $_REQUEST['categorii']);
    $_GET['categories'] = $category;
    $_REQUEST['categories'] = $category;
    $wp->query_vars['categories'] = $category;
}
add_action('parse_request', 'brodart_prepare_romanian_category_filter_request', 1);

/**
 * Keeps category filter URLs in Romanian and removes older multi-category
 * values produced by WooCommerce's default multi-select behavior.
 */
function brodart_canonicalize_category_filter_url(): void
{
    $request = $GLOBALS['brodart_category_filter_request'] ?? null;

    if (! is_shop() || ! is_array($request) || ! is_string($request['url'] ?? null)) {
        return;
    }

    $category = brodart_normalize_category_filter_value((string) ($request['value'] ?? ''));

    if ('' === $category || ('categorii' === ($request['parameter'] ?? '') && $category === $request['value'])) {
        return;
    }

    $url = remove_query_arg(array('categories', 'categorii'), $request['url']);
    $url = add_query_arg('categorii', $category, $url);

    wp_safe_redirect($url, 302, 'Brodart Romanian category filter');
    exit;
}
add_action('template_redirect', 'brodart_canonicalize_category_filter_url', 1);

/**
 * Hides the page title ("Magazin") on the shop page only. Blocksy strips
 * per-page hero options for the shop page (it is an archive), so the title
 * layer of the woo_categories hero is disabled here, keeping breadcrumbs and
 * category archive titles untouched.
 *
 * @param mixed $value Theme mod value for woo_categories_hero_elements.
 * @return mixed
 */
function brodart_hide_shop_title($value)
{
    if (is_admin() || ! function_exists('is_shop') || ! is_shop()) {
        return $value;
    }

    if (! is_array($value)) {
        return $value;
    }

    foreach ($value as $index => $element) {
        if ('custom_title' === ($element['id'] ?? '')) {
            $value[$index]['enabled'] = false;
        }
    }

    return $value;
}
add_filter('theme_mod_woo_categories_hero_elements', 'brodart_hide_shop_title');

/**
 * Romanian overrides for strings not yet covered by the official pack.
 *
 * @param string $translation Current translation.
 * @param string $text        Original string.
 * @param string $domain      Text domain.
 * @return string
 */
function brodart_translate_woocommerce(string $translation, string $text, string $domain): string
{
    static $map = array(
        'woocommerce' => array(
            'This product has multiple variants. The options may be chosen on the product page' => 'Acest produs are mai multe variante. Opțiunile pot fi alese pe pagina produsului',
            'Select options'                                   => 'Alege opțiuni',
            'Shop order'                                       => 'Ordonare magazin',
            'Default sorting'                                  => 'Sortare implicită',
            'Sort by popularity'                               => 'Sortează după popularitate',
            'Sort by average rating'                           => 'Sortează după evaluarea medie',
            'Sort by latest'                                   => 'Sortează după noutate',
            'Sort by price: low to high'                       => 'Sortează după preț: crescător',
            'Sort by price: high to low'                       => 'Sortează după preț: descrescător',
            'Sorted by popularity'                             => 'Sortate după popularitate',
            'Sorted by average rating'                         => 'Sortate după evaluarea medie',
            'Sorted by latest'                                 => 'Sortate după noutate',
            'Sorted by price: low to high'                     => 'Sortate după preț: crescător',
            'Sorted by price: high to low'                     => 'Sortate după preț: descrescător',
            'Filter products by minimum price'                 => 'Filtrează produsele după prețul minim',
            'Filter products by maximum price'                 => 'Filtrează produsele după prețul maxim',
            'Remove filter: %s'                                => 'Elimină filtrul: %s',
            'Remove filter: {{label}}'                         => 'Elimină filtrul: {{label}}',
            'Active filters'                                   => 'Filtre active',
            'Product Filters'                                  => 'Filtre produse',
            'Show more…'                                       => 'Arată mai multe…',
            'Show %d more'                                     => 'Arată încă %d',
            'Select options for &ldquo;%s&rdquo;'              => 'Alege opțiuni pentru „%s”',
        ),
    );

    if (! isset($map[$domain][$text])) {
        return $translation;
    }

    return $map[$domain][$text];
}
add_filter('gettext', 'brodart_translate_woocommerce', 20, 3);
add_filter('gettext_with_context', 'brodart_translate_woocommerce_context', 20, 4);

/**
 * Same overrides, for strings that carry a translation context.
 *
 * @param string $translation Current translation.
 * @param string $text        Original string.
 * @param string $context     gettext context.
 * @param string $domain      Text domain.
 * @return string
 */
function brodart_translate_woocommerce_context(string $translation, string $text, string $context, string $domain): string
{
    if ('woocommerce' !== $domain) {
        return $translation;
    }

    if ('with first and last result' === $context && str_starts_with($text, 'Showing %1$d')) {
        return 'Se afișează %1$d–%2$d din %3$d rezultate';
    }

    return brodart_translate_woocommerce($translation, $text, $domain);
}

/**
 * Plural-form overrides: result count and the filter "Show more" button.
 *
 * @param string|null $translation Translated string.
 * @param string      $single      Singular form.
 * @param string      $plural      Plural form.
 * @param int         $number      The number.
 * @param string      $domain      Text domain.
 * @return string|null
 */
function brodart_ngettext_woocommerce(?string $translation, string $single, string $plural, int $number, string $domain): ?string
{
    if ('woocommerce' !== $domain) {
        return $translation;
    }

    if ('Showing all %1$d result' === $single) {
        return 'Se afișează toate cele %1$d rezultate';
    }

    if ('Show %d more' === $single || 'Show %1$d more item' === $single) {
        return 'Arată încă %d';
    }

    return $translation;
}
add_filter('ngettext', 'brodart_ngettext_woocommerce', 20, 5);

/**
 * Overrides the JS translations (block scripts) for strings not yet in the
 * ro_RO JSON pack, e.g. the removable chip aria-label.
 *
 * @param string $file   Path to the translation JSON file.
 * @param string $handle Script handle.
 * @param string $domain Text domain.
 * @return string
 */
function brodart_js_translations(string $file, string $handle, string $domain): string
{
    if ('woocommerce' !== $domain || ! str_contains($handle, 'product-filter')) {
        return $file;
    }

    $custom = WP_CONTENT_DIR . '/languages/brodart-woocommerce-js-ro_RO.json';
    if (! file_exists($custom)) {
        $data = array(
            '' => array('domain' => 'woocommerce', 'lang' => 'ro_RO'),
            'Remove filter: {{label}}' => array('Elimină filtrul: {{label}}'),
        );
        file_put_contents($custom, wp_json_encode($data));
    }

    return file_exists($custom) ? $custom : $file;
}
add_filter('load_script_translation_file', 'brodart_js_translations', 20, 3);

/**
 * Plural-form overrides with context (_nx), e.g. the paginated result count.
 *
 * @param string|null $translation Translated string.
 * @param string      $single      Singular form.
 * @param string      $plural      Plural form.
 * @param int         $number      The number.
 * @param string      $context     gettext context.
 * @param string      $domain      Text domain.
 * @return string|null
 */
function brodart_ngettext_context_woocommerce(?string $translation, string $single, string $plural, int $number, string $context, string $domain): ?string
{
    if ('woocommerce' !== $domain) {
        return $translation;
    }

    if ('with first and last result' === $context && str_starts_with($single, 'Showing %1$d')) {
        return 'Se afișează %1$d–%2$d din %3$d rezultate';
    }

    return $translation;
}
add_filter('ngettext_with_context', 'brodart_ngettext_context_woocommerce', 20, 6);

/**
 * Adds the "Default sorting" option back into the ordering dropdown.
 *
 * @param array $options Sorting options.
 * @return array
 */
function brodart_catalog_orderby_options(array $options): array
{
    return array_merge(array('menu_order' => __('Sortare implicită', 'woocommerce')), $options);
}
add_filter('woocommerce_catalog_orderby', 'brodart_catalog_orderby_options', 20);

/**
 * Appends "/ metru liniar" to prices of products imported from Mendola
 * (they are sold by linear meter).
 *
 * @param string      $price_html Formatted price HTML.
 * @param WC_Product  $product    The product.
 * @return string
 */
function brodart_price_suffix(string $price_html, $product): string
{
    if (is_admin() && ! wp_doing_ajax()) {
        return $price_html;
    }

    $product_id = $product instanceof WC_Product_Variation ? $product->get_parent_id() : $product->get_id();
    if ('linear_meter' !== get_post_meta($product_id, '_brodart_import_price_unit', true)) {
        return $price_html;
    }

    if (str_contains($price_html, 'metru liniar')) {
        return $price_html;
    }

    return $price_html . ' <small class="brodart-price-suffix">/ metru liniar</small>';
}
add_filter('woocommerce_get_price_html', 'brodart_price_suffix', 5, 2);

/**
 * Defensive: collapses a doubled "/ metru liniar" suffix that can appear when
 * two price filters both append the unit (e.g. for the Alana pilot).
 *
 * @param string $price_html Formatted price HTML.
 * @return string
 */
function brodart_collapse_price_suffix(string $price_html): string
{
    return preg_replace(
        '/(\/ metru liniar)(<\/(small|span)>)?(\s|<[^>]+>)*(\/ metru liniar)/u',
        '$1$2',
        $price_html
    );
}
add_filter('woocommerce_get_price_html', 'brodart_collapse_price_suffix', 99);

/**
 * Adds a "NOU" flash badge for products published in the last 30 days.
 */
function brodart_new_badge(): void
{
    global $product;

    if (! $product instanceof WC_Product) {
        return;
    }

    $published = get_post_time('U', true, $product->get_id());
    if ($published && $published > time() - 30 * DAY_IN_SECONDS) {
        echo '<span class="onsale brodart-badge-new">NOU</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'brodart_new_badge', 9);

/**
 * Adds the second gallery image as a hover layer on product cards
 * (Style Guide §6: subtle fade to the second image on hover).
 */
function brodart_hover_image(): void
{
    global $product;

    if (! $product instanceof WC_Product) {
        return;
    }

    $gallery_ids = $product->get_gallery_image_ids();
    if (empty($gallery_ids)) {
        return;
    }

    echo '<span class="brodart-hover-image-frame" aria-hidden="true">';
    echo wp_get_attachment_image(
        $gallery_ids[0],
        'woocommerce_thumbnail',
        false,
        array('class' => 'brodart-hover-image', 'loading' => 'lazy', 'alt' => '')
    );
    echo '</span>';
}
add_action('woocommerce_before_shop_loop_item_title', 'brodart_hover_image', 11);

function brodart_mobile_filter_trigger(): void
{
    if (
        ! function_exists('is_shop')
        || (! is_shop() && (! function_exists('is_product_taxonomy') || ! is_product_taxonomy()))
    ) {
        return;
    }
?>
    <button class="brodart-mobile-filter-trigger" type="button" aria-label="Filtrează produsele" aria-haspopup="dialog">
        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M10 17.5H14V16H10V17.5ZM6 6V7.5H18V6H6ZM8 12.5H16V11H8V12.5Z" fill="currentColor" />
        </svg>
        <span>Filtre</span>
    </button>
<?php
}
add_action('woocommerce_before_shop_loop', 'brodart_mobile_filter_trigger', 15);

/**
 * Shop polish styles (badges, filters, hover image, price suffix).
 * Scoped to catalog pages; colors come from the Brodart palette.
 */
function brodart_shop_styles(): void
{
?>
    <style id="brodart-shop-tweaks">
        /* Badge-uri discrete: fundal alb translucid, text mic uppercase (Style Guide §8) */
        .woocommerce span.onsale,
        .wc-block-grid__product .wc-block-grid__product-onsale {
            background: rgba(255, 255, 255, 0.92);
            color: #1A1A1A;
            border: 1px solid #E5E3E0;
            border-radius: 2px;
            box-shadow: none;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 10px;
            min-height: 0;
            line-height: 1.4;
        }

        span.brodart-badge-new {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
        }

        /* Cardurile folosesc același cadru 4:5 pentru ambele fotografii. */
        .woocommerce ul.products li.product figure {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 5;
            margin: 0;
            overflow: hidden;
        }

        .woocommerce ul.products li.product figure>a.ct-media-container {
            position: absolute !important;
            inset: 0;
            width: 100%;
            height: 100%;
            aspect-ratio: auto !important;
        }

        .woocommerce ul.products li.product figure>a.ct-media-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            aspect-ratio: auto !important;
        }

        /* Imaginea secundară acoperă doar cadrul imaginii, nu cardul întreg. */
        .woocommerce ul.products li.product .brodart-hover-image-frame {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            display: block;
            width: 100%;
            aspect-ratio: 4 / 5;
            overflow: hidden;
            pointer-events: none;
        }

        .woocommerce ul.products li.product .ct-woo-card-actions~a img,
        .woocommerce ul.products li.product>a .woocommerce-loop-product__link img {
            position: relative;
        }

        .woocommerce ul.products li.product img.brodart-hover-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 400ms ease-in-out;
            pointer-events: none;
        }

        .woocommerce ul.products li.product:hover img.brodart-hover-image {
            opacity: 1;
        }

        .woocommerce ul.products li.product .ct-image-container {
            position: relative;
            overflow: hidden;
        }

        .brodart-mobile-filter-trigger {
            display: none;
        }

        @media (max-width: 1023px) {
            .woocommerce .woo-listing-top {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .woocommerce .woo-listing-top .woocommerce-ordering {
                margin-left: auto;
            }

            .woocommerce .brodart-mobile-filter-trigger {
                display: inline-flex;
                flex: 1 1 auto;
                align-items: center;
                justify-content: center;
                gap: 8px;
                min-height: 34px;
                padding: 0 12px;
                border: 1px solid #E5E3E0;
                border-radius: 2px;
                background: #FFFFFF;
                color: #1A1A1A;
                font-size: 13px;
            }

            #sidebar.ct-hidden-sm.ct-hidden-md {
                position: fixed !important;
                top: 0;
                left: 0;
                z-index: 10000;
                display: block !important;
                width: 0 !important;
                height: 0 !important;
                overflow: visible !important;
                pointer-events: none;
            }

            #sidebar .ct-sidebar,
            #sidebar .ct-widget,
            #sidebar .wc-block-product-filters {
                width: 0 !important;
                height: 0 !important;
                overflow: visible !important;
            }

            #sidebar .wc-block-product-filters__open-overlay {
                display: none !important;
            }

            #sidebar .wc-block-product-filters__overlay {
                pointer-events: none;
            }

            #sidebar .wc-block-product-filters.is-overlay-opened .wc-block-product-filters__overlay {
                pointer-events: auto;
            }
        }

        /* Sufix preț "/ metru liniar" discret */
        .brodart-price-suffix {
            color: #6B6B6B;
            font-size: 0.85em;
            font-weight: 400;
        }

        /* Filtre: titluri discrete, chip-uri pe paleta taupe */
        .wc-block-product-filters .wp-block-heading+* {
            margin-top: 0.35rem;
        }

        .wc-block-product-filter-removable-chips .wc-block-components-chip,
        .wc-block-product-filter-removable-chips button {
            background: #F8F7F5;
            border: 1px solid #E5E3E0;
            color: #1A1A1A;
            border-radius: 2px;
        }

        .wc-block-product-filter-removable-chips button:hover {
            background: #A9A296;
            color: #fff;
            border-color: #A9A296;
        }

        /* Meta discret: număr rezultate + sortare */
        .woocommerce-result-count {
            color: #6B6B6B;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .woocommerce-ordering select {
            border: 1px solid #E5E3E0;
            border-radius: 2px;
            font-size: 13px;
            color: #6B6B6B;
        }
    </style>
<?php
}
add_action('wp_head', 'brodart_shop_styles', 20);

function brodart_mobile_filter_trigger_script(): void
{
    if (
        ! function_exists('is_shop')
        || (! is_shop() && (! function_exists('is_product_taxonomy') || ! is_product_taxonomy()))
    ) {
        return;
    }
?>
    <script id="brodart-mobile-filter-trigger-script">
        document.addEventListener('click', function(event) {
            const target = event.target instanceof Element ?
                event.target.closest('.brodart-mobile-filter-trigger') :
                null;

            if (!target) {
                return;
            }

            document.querySelector('#sidebar .wc-block-product-filters__open-overlay')?.click();
        });
    </script>
<?php
}
add_action('wp_footer', 'brodart_mobile_filter_trigger_script', 30);

/**
 * Fixes alt text on the homepage collection cards (Perdele / Draperii).
 * The cards currently reuse product images whose alt describes the product,
 * not the category. We swap the alt only on the homepage, keeping the
 * product page alt intact.
 *
 * @param string $html The image HTML.
 * @param int    $attachment_id Attachment ID.
 * @return string
 */
function brodart_homepage_category_alt(string $html, int $attachment_id): string
{
    if (! is_front_page()) {
        return $html;
    }

    $map = array(
        1201 => 'Perdele Brodart — materiale ușoare pentru lumină și intimitate',
        1075 => 'Draperii Brodart — texturi pentru profunzime și confort',
    );

    if (! isset($map[$attachment_id])) {
        return $html;
    }

    return preg_replace('/alt="[^"]*"/', 'alt="' . esc_attr($map[$attachment_id]) . '"', $html);
}
add_filter('wp_get_attachment_image', 'brodart_homepage_category_alt', 10, 2);

/**
 * Translates the newsletter placeholder to Romanian on the homepage.
 *
 * @param string $html The form HTML.
 * @return string
 */
function brodart_newsletter_placeholder(string $html): string
{
    if (! is_front_page()) {
        return $html;
    }

    return str_replace('placeholder="Your email *"', 'placeholder="Adresa ta de email *"', $html);
}
add_filter('render_block', 'brodart_newsletter_placeholder', 10, 1);

/**
 * Styles for the "Sisteme de prindere" mini-gallery (3-up grid).
 */
function brodart_home_gallery_styles(): void
{
    if (! is_front_page()) {
        return;
    }
?>
    <style id="brodart-home-gallery">
        .brodart-mini-gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 24px;
        }

        .brodart-mini-gallery figure {
            margin: 0;
            border-radius: 2px;
            overflow: hidden;
        }

        .brodart-mini-gallery img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform 400ms ease-in-out;
        }

        .brodart-mini-gallery figure:hover img {
            transform: scale(1.03);
        }

        @media (max-width: 767px) {
            .brodart-mini-gallery {
                grid-template-columns: 1fr;
            }

            .brodart-mini-gallery img {
                height: 220px;
            }
        }
    </style>
<?php
}
add_action('wp_head', 'brodart_home_gallery_styles', 25);

/**
 * Improves text contrast on the homepage category cards and editorial banner.
 */
function brodart_home_text_panel_styles(): void
{
    if (! is_front_page()) {
        return;
    }
?>
    <style id="brodart-home-text-panels">
        .brodart-home-category-card {
            display: block;
            color: inherit;
            text-decoration: none;
            cursor: pointer;
        }

        .brodart-home-category-card:focus-visible {
            outline: 2px solid #A9A296;
            outline-offset: 4px;
        }

        .stk-76abb28 .stk-d5f4d72-container,
        .stk-9794fe2 .stk-14910d5-container {
            background: rgba(248, 247, 245, 0.96) !important;
            padding: 14px 20px !important;
        }

        .stk-76abb28 .stk-block-subtitle__text,
        .stk-9794fe2 .stk-block-subtitle__text,
        .stk-41508ab .stk-58f7d19-inner-blocks>.wp-block-stackable-text:first-child .stk-block-text__text {
            color: #6B6B6B !important;
        }

        body.home .stk-76abb28 .stk-block-heading__text,
        body.home .stk-9794fe2 .stk-block-heading__text,
        .stk-41508ab .stk-58f7d19-inner-blocks .stk-block-heading__text,
        .stk-41508ab .stk-58f7d19-inner-blocks .wp-block-stackable-text:not(:first-child) .stk-block-text__text {
            color: #1A1A1A !important;
        }

        .stk-41508ab:before {
            opacity: 0.24 !important;
        }

        .stk-41508ab .stk-58f7d19-inner-blocks {
            background: rgba(248, 247, 245, 0.96);
            padding: 28px 30px;
        }

        .stk-41508ab .stk-block-button .stk-button,
        .stk-41508ab .stk-block-button .stk-button__inner-text {
            color: #1A1A1A !important;
        }

        .stk-41508ab .stk-block-button .stk-button {
            background: #A9A296 !important;
        }

        @media (max-width: 767px) {

            .stk-76abb28 .stk-d5f4d72-container,
            .stk-9794fe2 .stk-14910d5-container {
                padding: 12px 16px !important;
            }

            .stk-41508ab .stk-58f7d19-inner-blocks {
                padding: 22px 20px;
            }
        }
    </style>
<?php
}
add_action('wp_head', 'brodart_home_text_panel_styles', 26);

/**
 * Links homepage category cards and the hero CTA to their shop filters.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block data.
 * @return string
 */
function brodart_link_home_category_ctas(string $block_content, array $block): string
{
    if (is_admin()) {
        return $block_content;
    }

    $unique_id = $block['attrs']['uniqueId'] ?? '';

    if (is_front_page() && 'stackable/button' === ($block['blockName'] ?? '') && '4701e58' === $unique_id) {
        $category = false !== strpos(strtolower(wp_strip_all_tags($block_content)), 'draperii')
            ? 'draperii'
            : 'perdele';
        $url = esc_url(home_url('/magazin/?categorii=' . $category));

        return preg_replace_callback(
            '/(<a\b[^>]*\bhref=)(["\'])[^"\']*\2/i',
            static function (array $matches) use ($url): string {
                return $matches[1] . $matches[2] . $url . $matches[2];
            },
            $block_content,
            1
        ) ?? $block_content;
    }

    if ('stackable/image-box' !== ($block['blockName'] ?? '')) {
        return $block_content;
    }

    $links = array(
        '76abb28' => array(
            'url' => home_url('/magazin/?categorii=perdele'),
        ),
        '9794fe2' => array(
            'url' => home_url('/magazin/?categorii=draperii'),
        ),
    );

    if (! isset($links[$unique_id])) {
        return $block_content;
    }

    return sprintf(
        '<a class="brodart-home-category-card" href="%1$s">%2$s</a>',
        esc_url($links[$unique_id]['url']),
        $block_content
    );
}
add_filter('render_block', 'brodart_link_home_category_ctas', 10, 2);

/**
 * Renders the Contact page form when WPForms returns an empty block.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block data.
 * @return string
 */
function brodart_render_contact_form_block(string $block_content, array $block): string
{
    if (
        is_admin()
        || ! is_page(630)
        || '' !== $block_content
        || 246 !== (int) ($block['attrs']['formId'] ?? 0)
    ) {
        return $block_content;
    }

    ob_start();
    if (function_exists('wpforms_display') && function_exists('wpforms')) {
        wpforms_display(246);
    } elseif (class_exists('WPForms\\WPForms')) {
        $frontend = WPForms\WPForms::instance()->obj('frontend');

        if (is_object($frontend)) {
            $frontend->output(246);
        }
    }

    $form_html = (string) ob_get_clean();

    if ('' === $form_html) {
        error_log('Brodart Contact page: WPForms could not render form 246.');

        return '<p class="brodart-form-unavailable">Formularul nu este disponibil momentan. Ne poți scrie la <a href="mailto:contact@brod-art.ro">contact@brod-art.ro</a>.</p>';
    }

    return $form_html;
}
add_filter('render_block_wpforms/form-selector', 'brodart_render_contact_form_block', 99, 2);

/**
 * Keeps homepage category buttons pointed at their shop filters.
 */
function brodart_home_category_button_links_script(): void
{
    $home_path    = untrailingslashit((string) wp_parse_url(home_url('/'), PHP_URL_PATH));
    $request_path = wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);

    if (
        is_admin()
        || ! is_string($request_path)
        || $home_path !== untrailingslashit($request_path)
    ) {
        return;
    }

    $filter_url = wp_json_encode(home_url('/magazin/?categorii='));
?>
    <script id="brodart-home-category-button-links">
        (function () {
            const filterUrl = <?php echo $filter_url; ?>;

            function updateCategoryButtons() {
                document.querySelectorAll('a.stk-button').forEach(function (button) {
                    const text = (button.textContent || '').toLowerCase();
                    const href = (button.getAttribute('href') || '').toLowerCase();
                    const category = text.includes('draperii') || href.includes('/draperii/')
                        ? 'draperii'
                        : text.includes('perdele') || href.includes('/perdele/')
                            ? 'perdele'
                            : '';

                    if (category && button.href !== filterUrl + category) {
                        button.href = filterUrl + category;
                    }
                });
            }

            updateCategoryButtons();
            new MutationObserver(updateCategoryButtons).observe(document.body, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['href']
            });
        }());
    </script>
<?php
}
add_action('wp_footer', 'brodart_home_category_button_links_script', 100);

/**
 * Redirects legacy English URL bases to their Romanian equivalents.
 */
function brodart_redirect_legacy_english_routes(): void
{
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return;
    }

    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $request_path = wp_parse_url($request_uri, PHP_URL_PATH);

    if (! is_string($request_path)) {
        return;
    }

    $home_path = untrailingslashit((string) wp_parse_url(home_url('/'), PHP_URL_PATH));
    if ('' !== $home_path && str_starts_with($request_path, $home_path . '/')) {
        $request_path = substr($request_path, strlen($home_path));
    }

    $relative_path = trim($request_path, '/');
    $routes = array(
        'category/news'       => 'categorii/noutati',
        'category/useful'     => 'categorii/ghiduri',
        'product-category'    => 'categorii-produse',
        'my-account'          => 'contul-meu',
        'finalize-order'      => 'finalizare-comanda',
        'about-us'            => 'despre-noi',
        'contact-us'           => 'contact',
        'checkout'            => 'finalizare-comanda',
        'product'             => 'produs',
        'category'            => 'categorii',
        'cart'                => 'cos',
        'blog'                => 'jurnal',
        'shop'                => 'magazin',
    );

    foreach ($routes as $old_path => $new_path) {
        if ($relative_path !== $old_path && ! str_starts_with($relative_path, $old_path . '/')) {
            continue;
        }

        $new_path .= substr($relative_path, strlen($old_path));
        $target_url = trailingslashit(home_url('/' . trim($new_path, '/') . '/'));
        $query = wp_parse_url($request_uri, PHP_URL_QUERY);

        if (is_string($query) && '' !== $query) {
            $target_url .= '?' . $query;
        }

        wp_safe_redirect($target_url, 301, 'Brodart Romanian URL migration');
        exit;
    }
}
add_action('template_redirect', 'brodart_redirect_legacy_english_routes', 1);

/**
 * Keeps the Blocksy footer to three desktop columns and normalizes its titles.
 */
function brodart_footer_styles(): void
{
?>
    <style id="brodart-footer-styles">
        @media (min-width: 1000px) {
            #footer [data-row="middle"] > .ct-container {
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            }
        }

        #footer [data-row="middle"] > .ct-container > [data-column="ghost"] {
            display: none !important;
        }

        #footer [data-row="middle"] [data-column^="widget-area-"] .wp-block-heading {
            margin-block: 0 16px !important;
            color: #1A1A1A !important;
            font-size: 20px !important;
            font-weight: 600 !important;
            line-height: 1.3 !important;
        }

        #block-7 > div > p > img.emoji {
            display: inline-block;
            width: 1em;
            height: 1em;
            margin-inline-end: 0.25em;
            vertical-align: -0.15em;
        }
    </style>
<?php
}
add_action('wp_head', 'brodart_footer_styles', 27);
