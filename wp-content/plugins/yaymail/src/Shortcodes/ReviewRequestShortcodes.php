<?php

namespace YayMail\Shortcodes;

use YayMail\Utils\Helpers;
use YayMail\Utils\SingletonTrait;
use YayMail\Abstracts\BaseShortcode;

/**
 * Shortcodes for WC core review request email (`customer_review_request` feature).
 *
 * @since 4.4.7
 * @method: static ReviewRequestShortcodes get_instance()
 */
class ReviewRequestShortcodes extends BaseShortcode {
    use SingletonTrait;

    protected function __construct() {
        $this->available_email_ids = [ 'customer_review_request' ];
        parent::__construct();
    }

    public function get_shortcodes() {
        $shortcodes   = [];
        $shortcodes[] = [
            'name'        => 'yaymail_review_order_url',
            'description' => __( 'Review Order URL', 'yaymail' ),
            'group'       => 'review_request',
            'callback'    => [ $this, 'yaymail_review_order_url' ],
        ];
        return $shortcodes;
    }

    public function yaymail_review_order_url( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];

        if ( ! empty( $render_data['review_order_url'] ) ) {
            return esc_url( $render_data['review_order_url'] );
        }

        $order = Helpers::get_order_from_shortcode_data( $render_data );
        if ( ! empty( $order ) && function_exists( 'wc_get_review_order_url' ) ) {
            return esc_url( wc_get_review_order_url( $order ) );
        }

        return esc_url( home_url() );
    }
}
