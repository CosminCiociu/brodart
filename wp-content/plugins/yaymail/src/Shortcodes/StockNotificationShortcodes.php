<?php

namespace YayMail\Shortcodes;

use YayMail\Utils\SingletonTrait;
use YayMail\Abstracts\BaseShortcode;

/**
 * Shortcodes for WC core Back-in-Stock emails (WC 10.2, `customer_stock_notifications` feature).
 * Values come from the WC template args: notification, product, verification_link,
 * verification_expiration_threshold, button_link, unsubscribe_link, is_guest.
 *
 * @since 4.4.7
 * @method: static StockNotificationShortcodes get_instance()
 */
class StockNotificationShortcodes extends BaseShortcode {
    use SingletonTrait;

    protected function __construct() {
        $this->available_email_ids = [ 'customer_stock_notification_verify', 'customer_stock_notification_verified', 'customer_stock_notification' ];
        parent::__construct();
    }

    public function get_shortcodes() {
        $shortcodes   = [];
        $shortcodes[] = [
            'name'        => 'yaymail_stock_notification_product',
            'description' => __( 'Product (Image, Title, Attributes, Price)', 'yaymail' ),
            'group'       => 'stock_notifications',
            'callback'    => [ $this, 'yaymail_stock_notification_product' ],
        ];
        $shortcodes[] = [
            'name'        => 'yaymail_stock_notification_product_name',
            'description' => __( 'Product Name', 'yaymail' ),
            'group'       => 'stock_notifications',
            'callback'    => [ $this, 'yaymail_stock_notification_product_name' ],
        ];
        $shortcodes[] = [
            'name'        => 'yaymail_stock_notification_product_url',
            'description' => __( 'Product URL', 'yaymail' ),
            'group'       => 'stock_notifications',
            'callback'    => [ $this, 'yaymail_stock_notification_product_url' ],
        ];
        $shortcodes[] = [
            'name'        => 'yaymail_stock_notification_verification_url',
            'description' => __( 'Sign-up Verification URL', 'yaymail' ),
            'group'       => 'stock_notifications',
            'callback'    => [ $this, 'yaymail_stock_notification_verification_url' ],
        ];
        $shortcodes[] = [
            'name'        => 'yaymail_stock_notification_verification_expiration',
            'description' => __( 'Verification Link Expiration', 'yaymail' ),
            'group'       => 'stock_notifications',
            'callback'    => [ $this, 'yaymail_stock_notification_verification_expiration' ],
        ];
        $shortcodes[] = [
            'name'        => 'yaymail_stock_notification_unsubscribe_url',
            'description' => __( 'Unsubscribe URL', 'yaymail' ),
            'group'       => 'stock_notifications',
            'callback'    => [ $this, 'yaymail_stock_notification_unsubscribe_url' ],
        ];
        $shortcodes[] = [
            'name'        => 'yaymail_stock_notification_unsubscribe_text',
            'description' => __( 'Unsubscribe / Manage Notifications Text', 'yaymail' ),
            'group'       => 'stock_notifications',
            'callback'    => [ $this, 'yaymail_stock_notification_unsubscribe_text' ],
        ];
        return $shortcodes;
    }

    /**
     * Real product from the email args, or any published product for the editor preview.
     *
     * @return \WC_Product|null
     */
    private function get_product( $render_data ) {
        if ( ! empty( $render_data['product'] ) && $render_data['product'] instanceof \WC_Product ) {
            return $render_data['product'];
        }
        if ( empty( $render_data['is_sample'] ) ) {
            return null;
        }
        $products = wc_get_products(
            [
                'limit'  => 1,
                'status' => 'publish',
            ]
        );
        return ! empty( $products ) ? $products[0] : null;
    }

    public function yaymail_stock_notification_product( $data ) {
        $render_data  = isset( $data['render_data'] ) ? $data['render_data'] : [];
        $product      = $this->get_product( $render_data );
        $notification = $render_data['notification'] ?? null;

        if ( empty( $product ) ) {
            return '';
        }

        if ( ! empty( $notification ) ) {
            // WC's own product block: image, title, variation attributes, price.
            ob_start();
            do_action( 'woocommerce_email_stock_notification_product', $product, $notification, false, $render_data['email'] ?? null );
            return ob_get_clean();
        }

        // Editor preview: no notification object, so build a simple equivalent.
        $image = wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' );
        $html  = '<div class="yaymail-stock-notification-product">';
        if ( $image ) {
            $html .= '<p><img src="' . esc_url( $image ) . '" alt="' . esc_attr( $product->get_name() ) . '" width="160" /></p>';
        }
        $html .= '<p><strong>' . esc_html( $product->get_name() ) . '</strong></p>';
        $html .= '<p>' . wp_kses_post( $product->get_price_html() ) . '</p>';
        $html .= '</div>';
        return $html;
    }

    public function yaymail_stock_notification_product_name( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        $product     = $this->get_product( $render_data );
        return $product ? esc_html( $product->get_name() ) : '';
    }

    public function yaymail_stock_notification_product_url( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        if ( ! empty( $render_data['button_link'] ) ) {
            return esc_url( $render_data['button_link'] );
        }
        $product = $this->get_product( $render_data );
        return $product ? esc_url( $product->get_permalink() ) : esc_url( home_url() );
    }

    public function yaymail_stock_notification_verification_url( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        return ! empty( $render_data['verification_link'] ) ? esc_url( $render_data['verification_link'] ) : esc_url( home_url() );
    }

    public function yaymail_stock_notification_verification_expiration( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        if ( ! empty( $render_data['verification_expiration_threshold'] ) ) {
            return esc_html( $render_data['verification_expiration_threshold'] );
        }
        // translators: %s is the time duration in minutes.
        return esc_html( sprintf( _n( '%s minute', '%s minutes', 60, 'woocommerce' ), 60 ) );
    }

    public function yaymail_stock_notification_unsubscribe_url( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        return ! empty( $render_data['unsubscribe_link'] ) ? esc_url( $render_data['unsubscribe_link'] ) : esc_url( home_url() );
    }

    /**
     * Same sentence WC prints: guests get an unsubscribe link, customers a manage-notifications link.
     */
    public function yaymail_stock_notification_unsubscribe_text( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        if ( empty( $render_data['unsubscribe_link'] ) && empty( $render_data['is_sample'] ) ) {
            return '';
        }

        $link     = $this->yaymail_stock_notification_unsubscribe_url( $data );
        $is_guest = ! empty( $render_data['is_guest'] );

        if ( ! $is_guest ) {
            $link_tag = sprintf( '<a href="%1$s">%2$s</a>', $link, esc_html( _x( 'click here', 'unsubscribe cta for stock notifications for existing customers', 'woocommerce' ) ) );
            // translators: %s: unsubscribe link.
            return wp_kses_post( sprintf( __( 'To manage your notifications, %s to log in to your account.', 'woocommerce' ), $link_tag ) );
        }

        $link_tag = sprintf( '<a href="%1$s">%2$s</a>', $link, esc_html( _x( 'click here', 'unsubscribe cta for stock notifications for guests', 'woocommerce' ) ) );
        // translators: %s: unsubscribe link.
        return wp_kses_post( sprintf( __( 'To stop receiving these messages, %s to unsubscribe.', 'woocommerce' ), $link_tag ) );
    }
}
