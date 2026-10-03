<?php

/**
 * Plugin Name: Brodart Measurement Calculator
 * Description: Adds configurable fabric width and length measurements to WooCommerce products and calculates linear-meter pricing.
 * Version: 1.2.0
 * Requires Plugins: woocommerce
 * Requires PHP: 8.0
 * Text Domain: brodart-measurement-calculator
 */

if (! defined('ABSPATH')) {
    exit;
}

final class Brodart_Measurement_Calculator
{
    private const ENABLED_META = '_brodart_measurement_enabled';
    private const WIDTH_MIN_META = '_brodart_width_min_m';
    private const WIDTH_MAX_META = '_brodart_width_max_m';
    private const LENGTH_MIN_META = '_brodart_length_min_m';
    private const LENGTH_MAX_META = '_brodart_length_max_m';
    private const STEP_META = '_brodart_measurement_step_m';
    private const ACCESSORIES_META = '_brodart_accessories';

    public function __construct()
    {
        add_action('woocommerce_product_options_general_product_data', array($this, 'render_product_fields'));
        add_action('woocommerce_admin_process_product_object', array($this, 'save_product_fields'));
        add_action('woocommerce_before_add_to_cart_button', array($this, 'render_measurement_fields'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_filter('woocommerce_add_to_cart_validation', array($this, 'validate_add_to_cart'), 10, 5);
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_measurements_to_cart'), 10, 4);
        add_action('woocommerce_before_calculate_totals', array($this, 'calculate_cart_prices'));
        add_filter('woocommerce_get_item_data', array($this, 'display_cart_measurements'), 10, 2);
        add_action('woocommerce_checkout_create_order_line_item', array($this, 'save_order_measurements'), 10, 4);
        add_filter('woocommerce_dropdown_variation_attribute_options_html', array($this, 'render_color_swatches'), 20, 2);
        add_filter('woocommerce_get_price_html', array($this, 'add_price_unit'), 10, 2);
        add_filter('woocommerce_product_single_add_to_cart_text', array($this, 'translate_add_to_cart'), 10, 2);
        add_action('woocommerce_after_add_to_cart_button', array($this, 'render_purchase_guidance'));
        add_filter('the_content', array($this, 'append_product_information'), 20);
        add_filter('woocommerce_product_description_tab_title', array($this, 'translate_description_tab'));
        add_filter('woocommerce_product_additional_information_tab_title', array($this, 'translate_additional_information_tab'));
        add_filter('woocommerce_product_reviews_tab_title', array($this, 'translate_reviews_tab'));
    }

    public function render_product_fields(): void
    {
        global $post;

        if (! $post instanceof WP_Post) {
            return;
        }

        $product = wc_get_product($post->ID);
        if (! $product || ! in_array($product->get_type(), array('simple', 'variable'), true)) {
            return;
        }

        echo '<div class="options_group">';
        echo '<h4>' . esc_html__('Calculator dimensiuni material', 'brodart-measurement-calculator') . '</h4>';
        echo '<p class="form-field">' . esc_html__('Prețul produsului este tariful materialului pe metru liniar. Opțiunile de prindere pot avea tarife proprii.', 'brodart-measurement-calculator') . '</p>';

        woocommerce_wp_checkbox(
            array(
                'id'          => self::ENABLED_META,
                'label'       => esc_html__('Activează calculatorul', 'brodart-measurement-calculator'),
                'value'       => $this->is_in_curtain_category($product) ? 'yes' : $product->get_meta(self::ENABLED_META, true),
                'cbvalue'     => 'yes',
                'description' => $this->is_in_curtain_category($product)
                    ? esc_html__('Activ automat pentru produsele din categoria Perdele/Draperii.', 'brodart-measurement-calculator')
                    : esc_html__('Afișează configuratorul pe pagina produsului.', 'brodart-measurement-calculator'),
            )
        );

        $this->render_number_field($product, self::WIDTH_MIN_META, __('Lățime minimă (m)', 'brodart-measurement-calculator'), '0.01');
        $this->render_number_field($product, self::WIDTH_MAX_META, __('Lățime maximă (m)', 'brodart-measurement-calculator'), '2.50');
        $this->render_number_field($product, self::LENGTH_MIN_META, __('Înălțime minimă (m)', 'brodart-measurement-calculator'), '1');
        $this->render_number_field($product, self::LENGTH_MAX_META, __('Înălțime maximă (m)', 'brodart-measurement-calculator'), '5');
        $this->render_number_field($product, self::STEP_META, __('Pas selecție (m)', 'brodart-measurement-calculator'), '0.01');

        woocommerce_wp_textarea_input(
            array(
                'id'          => self::ACCESSORIES_META,
                'label'       => esc_html__('Sisteme de prindere', 'brodart-measurement-calculator'),
                'description' => esc_html__('Câte o opțiune pe linie, în formatul: Etichetă|tarif lei/metru.', 'brodart-measurement-calculator'),
                'desc_tip'    => true,
                'value'       => $product->get_meta(self::ACCESSORIES_META, true),
            )
        );

        echo '</div>';
    }

    private function render_number_field(WC_Product $product, string $meta_key, string $label, string $default): void
    {
        $value = $product->get_meta($meta_key, true);
        if ('' === $value) {
            $value = $default;
        }

        woocommerce_wp_text_input(
            array(
                'id'                => $meta_key,
                'label'             => esc_html($label),
                'type'              => 'number',
                'value'             => $value,
                'desc_tip'          => true,
                'custom_attributes' => array(
                    'min'  => '0.01',
                    'step' => '0.01',
                ),
            )
        );
    }

    public function save_product_fields(WC_Product $product): void
    {
        if (! current_user_can('edit_product', $product->get_id())) {
            return;
        }

        $product->update_meta_data(self::ENABLED_META, isset($_POST[self::ENABLED_META]) ? 'yes' : 'no');

        $fields = array(
            self::WIDTH_MIN_META,
            self::WIDTH_MAX_META,
            self::LENGTH_MIN_META,
            self::LENGTH_MAX_META,
            self::STEP_META,
        );

        foreach ($fields as $field) {
            if (! isset($_POST[$field])) {
                continue;
            }

            $value = wc_format_decimal(wp_unslash($_POST[$field]), 4);
            if (is_numeric($value) && (float) $value > 0) {
                $product->update_meta_data($field, $value);
            }
        }

        if (isset($_POST[self::ACCESSORIES_META])) {
            $product->update_meta_data(self::ACCESSORIES_META, sanitize_textarea_field(wp_unslash($_POST[self::ACCESSORIES_META])));
        }
    }

    public function render_measurement_fields(): void
    {
        global $product;

        if (! $product instanceof WC_Product || ! $this->is_enabled($product) || ! in_array($product->get_type(), array('simple', 'variable'), true)) {
            return;
        }

        $settings = $this->get_settings($product);
        $price_per_meter = $this->get_initial_price_per_meter($product);
        wp_nonce_field('brodart_measurement_' . $product->get_id(), 'brodart_measurement_nonce');
?>
        <fieldset class="brodart-measurement" data-brodart-measurement>
            <legend><?php esc_html_e('Configurează produsul', 'brodart-measurement-calculator'); ?></legend>
            <p class="brodart-measurement__fields">
                <label for="brodart-width">
                    <span><?php esc_html_e('Lățime', 'brodart-measurement-calculator'); ?></span>
                    <span class="brodart-measurement__input-wrap">
                        <input id="brodart-width" name="brodart_width" type="number" inputmode="decimal" required min="<?php echo esc_attr($settings['width_min']); ?>" max="<?php echo esc_attr($settings['width_max']); ?>" step="<?php echo esc_attr($settings['step']); ?>" value="<?php echo esc_attr($settings['width_max']); ?>">
                        <span>m</span>
                    </span>
                    <small><?php echo esc_html(sprintf(__('Între %1$s și %2$s m', 'brodart-measurement-calculator'), $this->format_measurement($settings['width_min']), $this->format_measurement($settings['width_max']))); ?></small>
                </label>
                <label for="brodart-height">
                    <span><?php esc_html_e('Înălțime', 'brodart-measurement-calculator'); ?></span>
                    <span class="brodart-measurement__input-wrap">
                        <input id="brodart-height" name="brodart_height" type="number" inputmode="decimal" required min="<?php echo esc_attr($settings['height_min']); ?>" max="<?php echo esc_attr($settings['height_max']); ?>" step="<?php echo esc_attr($settings['step']); ?>" value="<?php echo esc_attr($settings['height_min']); ?>">
                        <span>m</span>
                    </span>
                    <small><?php echo esc_html(sprintf(__('Între %1$s și %2$s m', 'brodart-measurement-calculator'), $this->format_measurement($settings['height_min']), $this->format_measurement($settings['height_max']))); ?></small>
                </label>
            </p>
            <?php if (! empty($settings['accessories'])) : ?>
                <fieldset class="brodart-measurement__options">
                    <legend><?php esc_html_e('Sistem de prindere', 'brodart-measurement-calculator'); ?></legend>
                    <?php foreach ($settings['accessories'] as $index => $accessory) : ?>
                        <label class="brodart-measurement__option">
                            <input type="radio" name="brodart_accessory" value="<?php echo esc_attr($accessory['key']); ?>" data-price="<?php echo esc_attr($accessory['price']); ?>" <?php checked(0 === $index); ?> required>
                            <span><?php echo esc_html($accessory['label']); ?></span>
                            <strong><?php echo wp_kses_post(wc_price($accessory['price'])); ?> / m</strong>
                        </label>
                    <?php endforeach; ?>
                </fieldset>
            <?php endif; ?>
            <fieldset class="brodart-measurement__options">
                <legend><?php esc_html_e('Număr bucăți', 'brodart-measurement-calculator'); ?></legend>
                <label class="brodart-measurement__option">
                    <input type="radio" name="brodart_pieces" value="1" checked required>
                    <span><?php esc_html_e('1 bucată', 'brodart-measurement-calculator'); ?></span>
                    <img src="<?php echo esc_url(plugins_url('assets/single-curtain-btn.png', __FILE__)); ?>" alt="">
                </label>
                <label class="brodart-measurement__option">
                    <input type="radio" name="brodart_pieces" value="2" required>
                    <span><?php esc_html_e('2 bucăți', 'brodart-measurement-calculator'); ?></span>
                    <img src="<?php echo esc_url(plugins_url('assets/double-curtain-btn.png', __FILE__)); ?>" alt="">
                </label>
            </fieldset>
            <p class="brodart-measurement__note"><?php esc_html_e('Preț calculat pe metru liniar: totalul se bazează pe lățime și numărul de bucăți. Înălțimea este folosită pentru execuție.', 'brodart-measurement-calculator'); ?></p>
            <dl class="brodart-measurement__summary" aria-live="polite">
                <div>
                    <dt><?php esc_html_e('Material', 'brodart-measurement-calculator'); ?></dt>
                    <dd data-brodart-material><?php echo wp_kses_post(wc_price($price_per_meter * $settings['width_max'])); ?></dd>
                </div>
                <div class="brodart-measurement__total">
                    <dt><?php esc_html_e('Total estimat', 'brodart-measurement-calculator'); ?></dt>
                    <dd data-brodart-total><?php echo wp_kses_post(wc_price($this->calculate_total($price_per_meter, 0, $settings['width_max'], 1))); ?></dd>
                </div>
            </dl>
        </fieldset>
<?php
    }

    public function render_color_swatches(string $html, array $args): string
    {
        $product = $args['product'] ?? null;
        $attribute = $args['attribute'] ?? '';
        if (! $product instanceof WC_Product_Variable || ! $this->is_enabled($product) || 'pa_culoare' !== $attribute) {
            return $html;
        }

        $variations = array();
        $variation_attribute = wc_variation_attribute_name($attribute);
        foreach ($product->get_children() as $variation_id) {
            $variation = wc_get_product($variation_id);
            if (! $variation instanceof WC_Product_Variation) {
                continue;
            }
            $variation_attributes = $variation->get_variation_attributes();
            $color_value = $variation_attributes[$variation_attribute] ?? '';
            $image_id = $variation->get_image_id();
            if ('' === $color_value || ! $image_id || ! wp_attachment_is_image($image_id)) {
                continue;
            }
            $variations[$color_value] = $variation;
        }

        if (empty($variations) || count($variations) !== count($args['options'] ?? array())) {
            return $html;
        }

        $terms = wc_get_product_terms($product->get_id(), $attribute, array('fields' => 'all'));
        $term_names = array();
        foreach ($terms as $term) {
            $term_names[$term->slug] = $term->name;
        }

        $selected = (string) ($args['selected'] ?? '');
        $buttons = '';
        foreach ($args['options'] as $option) {
            if (! isset($variations[$option])) {
                return $html;
            }

            $variation = $variations[$option];
            $color_name = $term_names[$option] ?? $option;
            $sku = $variation->get_sku();
            $variation_code = $sku ? substr($sku, strrpos($sku, '-') + 1) : '';
            $label = trim($color_name . ' ' . $variation_code);
            $is_selected = $selected === $option;

            $buttons .= sprintf(
                '<button type="button" class="brodart-color-swatch%1$s" data-brodart-color-value="%2$s" aria-pressed="%3$s" aria-label="%4$s"><span class="brodart-color-swatch__image">%5$s</span><span class="brodart-color-swatch__label">%6$s</span></button>',
                $is_selected ? ' is-selected' : '',
                esc_attr($option),
                $is_selected ? 'true' : 'false',
                esc_attr($label),
                wp_get_attachment_image($variation->get_image_id(), 'woocommerce_thumbnail', false, array('alt' => '', 'loading' => 'lazy')),
                esc_html($label)
            );
        }

        $html = preg_replace('/<select\b/', '<select tabindex="-1" aria-hidden="true"', $html, 1);

        return '<div class="brodart-color-swatches" role="group" aria-label="' . esc_attr__('Alege culoarea', 'brodart-measurement-calculator') . '">' . $buttons . '</div><span class="brodart-color-select-accessible">' . $html . '</span>';
    }

    public function enqueue_assets(): void
    {
        if (is_front_page()) {
            $upload_base = wp_upload_dir()['baseurl'] . '/2026/09/';
            $slides = array(
                array(
                    'image'       => $upload_base . 'Draperii-perdele.ro-Brodart-galerie-de-prezentare-1.jpg',
                    'eyebrow'     => 'PERDELE PENTRU LUMINĂ ȘI INTIMITATE',
                    'title'       => 'Alege lumina potrivită',
                    'description' => 'Perdele create pentru spații calme, luminoase și personale.',
                    'cta'         => 'Descoperă perdelele',
                    'url'         => home_url('/product-category/perdele/'),
                ),
                array(
                    'image'       => $upload_base . 'Draperii-perdele.ro-Brodart-galerie-de-prezentare-2.jpg',
                    'eyebrow'     => 'DRAPERII PENTRU PROFUNZIME',
                    'title'       => 'Dă spațiului mai mult caracter',
                    'description' => 'Texturi și tonuri atent alese pentru confort și echilibru.',
                    'cta'         => 'Vezi draperiile',
                    'url'         => home_url('/product-category/draperii/'),
                ),
                array(
                    'image'       => $upload_base . 'Draperii-perdele.ro-Brodart-galerie-de-prezentare-5.jpg',
                    'eyebrow'     => 'CONSULTANȚĂ PENTRU CASA TA',
                    'title'       => 'Construim atmosfera împreună',
                    'description' => 'Spune-ne ce îți dorești, iar noi te ajutăm să găsești soluția potrivită.',
                    'cta'         => 'Programează o consultanță',
                    'url'         => home_url('/contact-us/'),
                ),
            );

            wp_register_style('brodart-home-carousel', false, array(), '1.0.0');
            wp_enqueue_style('brodart-home-carousel');
            wp_add_inline_style(
                'brodart-home-carousel',
                '.stk-1284f2b{transition:opacity .7s ease-in-out;background-size:cover;background-position:center}.stk-1284f2b.brodart-carousel-is-changing{opacity:.18}@media(prefers-reduced-motion:reduce){.stk-1284f2b{transition:none}.stk-1284f2b.brodart-carousel-is-changing{opacity:1}}'
            );

            wp_register_script('brodart-home-carousel', false, array(), '1.0.0', true);
            wp_enqueue_script('brodart-home-carousel');
            wp_add_inline_script(
                'brodart-home-carousel',
                'window.BrodartHomeCarousel = ' . wp_json_encode($slides) . ';',
                'before'
            );
            wp_add_inline_script(
                'brodart-home-carousel',
                <<<'JS'
(function () {
    const slides = window.BrodartHomeCarousel || [];
    const hero = document.querySelector('.stk-1284f2b');

    if (!hero || slides.length < 2) {
        return;
    }

    const textBlocks = hero.querySelectorAll('.stk-block-text__text');
    const eyebrow = textBlocks[0];
    const title = hero.querySelector('.stk-block-heading__text');
    const description = textBlocks[1];
    const button = hero.querySelector('.stk-button__inner-text');
    const buttonLink = hero.querySelector('a.stk-button');
    let current = 0;

    function showSlide(index) {
        const slide = slides[index];
        hero.classList.add('brodart-carousel-is-changing');

        window.setTimeout(function () {
            hero.style.animation = 'none';
            hero.style.setProperty('background-image', 'url("' + slide.image + '")', 'important');
            if (eyebrow) eyebrow.textContent = slide.eyebrow;
            if (title) title.textContent = slide.title;
            if (description) description.textContent = slide.description;
            if (button) button.textContent = slide.cta;
            if (buttonLink) buttonLink.href = slide.url;
            hero.classList.remove('brodart-carousel-is-changing');
        }, 350);
    }

    hero.style.animation = 'none';
    hero.style.setProperty('background-image', 'url("' + slides[0].image + '")', 'important');
    showSlide(0);

    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        window.setInterval(function () {
            current = (current + 1) % slides.length;
            showSlide(current);
        }, 7000);
    }
}());
JS
            );
        }

        if (! is_product()) {
            return;
        }

        $product = wc_get_product(get_queried_object_id());
        if (! $product || ! $this->is_enabled($product) || ! in_array($product->get_type(), array('simple', 'variable'), true)) {
            return;
        }

        $settings = $this->get_settings($product);
        $price_per_meter = $this->get_initial_price_per_meter($product);
        wp_enqueue_style('brodart-measurement-calculator', plugins_url('assets/measurement.css', __FILE__), array(), '1.2.0');
        wp_enqueue_script('brodart-measurement-calculator', plugins_url('assets/measurement.js', __FILE__), array('jquery', 'wc-add-to-cart-variation'), '1.2.1', true);
        wp_localize_script(
            'brodart-measurement-calculator',
            'BrodartMeasurement',
            array(
                'pricePerMeter' => $price_per_meter,
                'isVariable'    => $product->is_type('variable'),
                'currency'      => get_woocommerce_currency(),
                'locale'        => 'ro-RO',
                'decimals'      => wc_get_price_decimals(),
                'heightMin'     => $settings['height_min'],
                'heightMax'     => $settings['height_max'],
                'widthMin'      => $settings['width_min'],
                'widthMax'      => $settings['width_max'],
                'step'          => $settings['step'],
                'accessories'   => $settings['accessories'],
                'invalidText'   => __('Introdu dimensiuni în limitele afișate.', 'brodart-measurement-calculator'),
            )
        );
    }

    public function validate_add_to_cart(bool $passed, int $product_id, int $quantity, int $variation_id = 0, array $variations = array()): bool
    {
        $product = wc_get_product($product_id);
        if (! $product || ! $this->is_enabled($product) || ! in_array($product->get_type(), array('simple', 'variable'), true)) {
            return $passed;
        }

        $nonce = isset($_POST['brodart_measurement_nonce']) ? sanitize_text_field(wp_unslash($_POST['brodart_measurement_nonce'])) : '';
        if (! wp_verify_nonce($nonce, 'brodart_measurement_' . $product_id)) {
            wc_add_notice(__('Reîncarcă pagina produsului și încearcă din nou.', 'brodart-measurement-calculator'), 'error');
            return false;
        }

        if ($product->is_type('variable')) {
            $variation = $variation_id ? wc_get_product($variation_id) : false;
            if (! $variation instanceof WC_Product_Variation || (int) $variation->get_parent_id() !== $product_id) {
                wc_add_notice(__('Alege mai întâi culoarea produsului.', 'brodart-measurement-calculator'), 'error');
                return false;
            }
        }

        $measurements = $this->get_posted_measurements($product);
        if (false === $measurements) {
            wc_add_notice(__('Introdu dimensiuni în limitele afișate.', 'brodart-measurement-calculator'), 'error');
            return false;
        }

        return $passed;
    }

    public function add_measurements_to_cart(array $cart_item_data, int $product_id, int $variation_id, int $quantity): array
    {
        $product = wc_get_product($product_id);
        if (! $product || ! $this->is_enabled($product) || ! in_array($product->get_type(), array('simple', 'variable'), true)) {
            return $cart_item_data;
        }

        $measurements = $this->get_posted_measurements($product);
        if (false === $measurements) {
            return $cart_item_data;
        }

        $priced_product = $variation_id ? wc_get_product($variation_id) : $product;
        if (! $priced_product instanceof WC_Product) {
            return $cart_item_data;
        }

        $cart_item_data['brodart_measurement'] = array(
            'width'          => $measurements['width'],
            'height'         => $measurements['height'],
            'pieces'         => $measurements['pieces'],
            'accessory'      => $measurements['accessory'],
            'material_price' => (float) $priced_product->get_price(),
        );

        return $cart_item_data;
    }

    public function calculate_cart_prices(WC_Cart $cart): void
    {
        if (is_admin() && ! wp_doing_ajax()) {
            return;
        }

        foreach ($cart->get_cart() as $cart_item) {
            if (empty($cart_item['brodart_measurement']) || ! isset($cart_item['data'])) {
                continue;
            }

            $measurement = $cart_item['brodart_measurement'];
            $cart_item['data']->set_price($this->calculate_total((float) $measurement['material_price'], (float) $measurement['accessory']['price'], (float) $measurement['width'], (int) $measurement['pieces']));
        }
    }

    public function display_cart_measurements(array $item_data, array $cart_item): array
    {
        if (empty($cart_item['brodart_measurement'])) {
            return $item_data;
        }

        $item_data[] = array(
            'key'   => __('Lățime', 'brodart-measurement-calculator'),
            'value' => wc_clean($this->format_measurement((float) $cart_item['brodart_measurement']['width']) . ' m'),
        );
        $item_data[] = array(
            'key'   => __('Înălțime', 'brodart-measurement-calculator'),
            'value' => wc_clean($this->format_measurement((float) $cart_item['brodart_measurement']['height']) . ' m'),
        );
        $item_data[] = array(
            'key'   => __('Prindere', 'brodart-measurement-calculator'),
            'value' => wc_clean($cart_item['brodart_measurement']['accessory']['label']),
        );
        $item_data[] = array(
            'key'   => __('Bucăți', 'brodart-measurement-calculator'),
            'value' => wc_clean((string) $cart_item['brodart_measurement']['pieces']),
        );

        return $item_data;
    }

    public function save_order_measurements(WC_Order_Item_Product $item, string $cart_item_key, array $values, WC_Order $order): void
    {
        if (empty($values['brodart_measurement'])) {
            return;
        }

        $item->add_meta_data(__('Lățime', 'brodart-measurement-calculator'), $this->format_measurement((float) $values['brodart_measurement']['width']) . ' m', true);
        $item->add_meta_data(__('Înălțime', 'brodart-measurement-calculator'), $this->format_measurement((float) $values['brodart_measurement']['height']) . ' m', true);
        $item->add_meta_data(__('Prindere', 'brodart-measurement-calculator'), $values['brodart_measurement']['accessory']['label'], true);
        $item->add_meta_data(__('Bucăți', 'brodart-measurement-calculator'), (string) $values['brodart_measurement']['pieces'], true);
    }

    public function add_price_unit(string $price_html, WC_Product $product): string
    {
        if (! $this->is_enabled($product) || ! in_array($product->get_type(), array('simple', 'variable', 'variation'), true)) {
            return $price_html;
        }

        if ('linear_meter' === $product->get_meta('_brodart_import_price_unit', true)) {
            return $price_html;
        }

        return $price_html . ' <span class="brodart-price-unit">' . esc_html__('/ metru liniar', 'brodart-measurement-calculator') . '</span>';
    }

    public function translate_add_to_cart(string $text, WC_Product $product): string
    {
        return $this->is_enabled($product) ? __('Adaugă în coș', 'brodart-measurement-calculator') : $text;
    }

    public function render_purchase_guidance(): void
    {
        global $product;

        if (! $product instanceof WC_Product || ! $this->is_enabled($product) || ! in_array($product->get_type(), array('simple', 'variable'), true)) {
            return;
        }

        $contact_url = home_url('/contact-us/');
        echo '<div class="brodart-purchase-guidance">';
        echo '<p>' . esc_html__('Preț calculat pe metru liniar', 'brodart-measurement-calculator') . '</p>';
        echo '<p>' . esc_html__('Produs realizat pe comandă', 'brodart-measurement-calculator') . '</p>';
        echo '<p>' . esc_html__('Asistență la măsurare: ', 'brodart-measurement-calculator') . '<a href="tel:+40723137399">0723 137 399</a></p>';
        echo '<p><a href="#livrare-retur">' . esc_html__('Livrare și retur', 'brodart-measurement-calculator') . '</a></p>';
        echo '<p><a href="' . esc_url($contact_url) . '">' . esc_html__('Contact și solicitări', 'brodart-measurement-calculator') . '</a></p>';
        echo '</div>';
    }

    public function append_product_information(string $content): string
    {
        $product = wc_get_product(get_queried_object_id());
        if (! is_product() || ! $product instanceof WC_Product || ! $this->is_in_curtain_category($product) || ! in_the_loop() || ! is_main_query()) {
            return $content;
        }

        $content .= '<section class="brodart-product-information">';
        $content .= '<h2>' . esc_html__('Întreținere', 'brodart-measurement-calculator') . '</h2>';
        $content .= '<p>' . esc_html__('Urmează instrucțiunile de întreținere indicate pe eticheta materialului. Evită temperaturile ridicate și produsele de curățare agresive. Pentru păstrarea formei, manipulează materialul cu grijă și lasă-l să se usuce natural, conform recomandărilor producătorului.', 'brodart-measurement-calculator') . '</p>';
        $content .= '<h2>' . esc_html__('Cum se calculează prețul', 'brodart-measurement-calculator') . '</h2>';
        $content .= '<p>' . esc_html__('Prețul afișat este tariful pentru un metru liniar de material. Totalul estimat se calculează astfel: tarif material × lățime × număr de bucăți. Înălțimea este colectată pentru execuție și nu modifică totalul materialului în configurația actuală.', 'brodart-measurement-calculator') . '</p>';
        $content .= '<h2>' . esc_html__('Execuție și livrare', 'brodart-measurement-calculator') . '</h2>';
        $content .= '<p>' . esc_html__('Termenul estimativ de execuție și livrare se confirmă după validarea dimensiunilor și a detaliilor comenzii. Vei primi confirmarea termenului înainte de începerea execuției.', 'brodart-measurement-calculator') . '</p>';
        $content .= '<h2>' . esc_html__('Produse realizate pe comandă', 'brodart-measurement-calculator') . '</h2>';
        $content .= '<p>' . esc_html__('Acest produs este realizat pe comandă, după dimensiunile transmise. Verifică atent măsurătorile înainte de plasarea comenzii. Pentru clarificări privind măsurarea, execuția sau eligibilitatea returului, contactează Brodart înainte de cumpărare.', 'brodart-measurement-calculator') . '</p>';
        $content .= '<h2 id="livrare-retur">' . esc_html__('Livrare și retur', 'brodart-measurement-calculator') . '</h2>';
        $content .= '<p>' . esc_html__('Condițiile exacte de livrare și retur pentru produsele realizate pe comandă se confirmă înainte de execuție. Pentru detalii aplicabile comenzii tale, contactează Brodart.', 'brodart-measurement-calculator') . '</p>';
        $content .= '</section>';

        return $content;
    }

    public function translate_description_tab(string $title): string
    {
        return 700 === get_queried_object_id() ? __('Descriere', 'brodart-measurement-calculator') : $title;
    }

    public function translate_additional_information_tab(string $title): string
    {
        return 700 === get_queried_object_id() ? __('Informații suplimentare', 'brodart-measurement-calculator') : $title;
    }

    public function translate_reviews_tab(string $title): string
    {
        return 700 === get_queried_object_id() ? __('Recenzii', 'brodart-measurement-calculator') : $title;
    }

    private function is_enabled(WC_Product $product): bool
    {
        if ($product->is_type('variation')) {
            $parent = wc_get_product($product->get_parent_id());
            return $parent instanceof WC_Product && $this->is_enabled($parent);
        }

        if (! in_array($product->get_type(), array('simple', 'variable'), true)) {
            return false;
        }

        return 'yes' === $product->get_meta(self::ENABLED_META, true) || $this->is_in_curtain_category($product);
    }

    private function is_in_curtain_category(WC_Product $product): bool
    {
        $product_id = $product->is_type('variation') ? $product->get_parent_id() : $product->get_id();
        $term_ids = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
        if (is_wp_error($term_ids)) {
            return false;
        }

        foreach ($term_ids as $term_id) {
            $term_ids_to_check = array_merge(array((int) $term_id), get_ancestors((int) $term_id, 'product_cat', 'taxonomy'));
            foreach ($term_ids_to_check as $ancestor_id) {
                $term = get_term((int) $ancestor_id, 'product_cat');
                if ($term && ! is_wp_error($term) && in_array($term->slug, array('perdele', 'draperii', 'curtains', 'draperies'), true)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function get_initial_price_per_meter(WC_Product $product): float
    {
        if ($product instanceof WC_Product_Variable) {
            return (float) $product->get_variation_price('min', true);
        }

        return (float) wc_get_price_to_display($product);
    }

    private function get_settings(WC_Product $product): array
    {
        return array(
            'width_min'  => (float) ($product->get_meta(self::WIDTH_MIN_META, true) ?: '0.01'),
            'width_max'  => (float) ($product->get_meta(self::WIDTH_MAX_META, true) ?: '2.50'),
            'height_min' => (float) ($product->get_meta(self::LENGTH_MIN_META, true) ?: '1'),
            'height_max' => (float) ($product->get_meta(self::LENGTH_MAX_META, true) ?: '5'),
            'step'       => (float) ($product->get_meta(self::STEP_META, true) ?: '0.01'),
            'accessories' => $this->get_accessories($product),
        );
    }

    private function get_accessories(WC_Product $product): array
    {
        $lines = preg_split('/\r\n|\r|\n/', (string) $product->get_meta(self::ACCESSORIES_META, true));
        $accessories = array();

        foreach ($lines as $line) {
            $parts = array_map('trim', explode('|', $line, 2));
            if (2 !== count($parts) || '' === $parts[0] || ! is_numeric($parts[1]) || (float) $parts[1] < 0) {
                continue;
            }

            $accessories[] = array(
                'key'   => sanitize_title($parts[0]),
                'label' => sanitize_text_field($parts[0]),
                'price' => (float) wc_format_decimal($parts[1], 4),
            );
        }

        return $accessories;
    }

    private function get_posted_measurements(WC_Product $product)
    {
        $settings = $this->get_settings($product);
        $width    = isset($_POST['brodart_width']) ? wc_format_decimal(wp_unslash($_POST['brodart_width']), 4) : '';
        $height   = isset($_POST['brodart_height']) ? wc_format_decimal(wp_unslash($_POST['brodart_height']), 4) : '';
        $accessory_key = isset($_POST['brodart_accessory']) ? sanitize_title(wp_unslash($_POST['brodart_accessory'])) : '';
        $pieces = isset($_POST['brodart_pieces']) ? absint($_POST['brodart_pieces']) : 0;

        if (! is_numeric($width) || ! is_numeric($height)) {
            return false;
        }

        $width  = (float) $width;
        $height = (float) $height;
        if (! $this->is_valid_measurement($width, $settings['width_min'], $settings['width_max'], $settings['step']) || ! $this->is_valid_measurement($height, $settings['height_min'], $settings['height_max'], $settings['step']) || ! in_array($pieces, array(1, 2), true)) {
            return false;
        }

        $accessory = array('key' => '', 'label' => __('Fără prindere', 'brodart-measurement-calculator'), 'price' => 0.0);
        $accessory_found = empty($settings['accessories']);
        foreach ($settings['accessories'] as $option) {
            if ($option['key'] === $accessory_key) {
                $accessory = $option;
                $accessory_found = true;
                break;
            }
        }

        if (! $accessory_found) {
            return false;
        }

        return array(
            'width'     => $width,
            'height'    => $height,
            'pieces'    => $pieces,
            'accessory' => $accessory,
        );
    }

    private function calculate_total(float $material_price, float $accessory_price, float $width, int $pieces): float
    {
        return ($material_price + $accessory_price) * $width * $pieces;
    }

    private function is_valid_measurement(float $value, float $minimum, float $maximum, float $step): bool
    {
        if ($value < $minimum || $value > $maximum || $step <= 0) {
            return false;
        }

        $steps = ($value - $minimum) / $step;
        return abs($steps - round($steps)) < 0.0001;
    }

    private function format_measurement(float $value): string
    {
        return rtrim(rtrim(number_format_i18n($value, 2), '0'), ',.');
    }
}

add_action(
    'plugins_loaded',
    static function (): void {
        if (class_exists('WooCommerce')) {
            new Brodart_Measurement_Calculator();
        }
    }
);
