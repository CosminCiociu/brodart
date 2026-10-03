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

/**
 * Localizes the product category taxonomy labels (WooCommerce registers them
 * in English; the block filters use singular_name for group labels).
 */
function brodart_category_labels(): void
{
    global $wp_taxonomies;

    if (isset($wp_taxonomies['product_cat'])) {
        $wp_taxonomies['product_cat']->labels->singular_name = 'Categorie';
        $wp_taxonomies['product_cat']->label                 = 'Categorii';
    }
}
add_action('init', 'brodart_category_labels', 30);

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

    echo wp_get_attachment_image(
        $gallery_ids[0],
        'woocommerce_thumbnail',
        false,
        array('class' => 'brodart-hover-image', 'loading' => 'lazy')
    );
}
add_action('woocommerce_before_shop_loop_item_title', 'brodart_hover_image', 11);

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

        /* Hover cu a doua imagine: fade discret, 400ms (Style Guide §6) */
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

        .woocommerce ul.products li.product figure,
        .woocommerce ul.products li.product .ct-image-container {
            position: relative;
            overflow: hidden;
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
