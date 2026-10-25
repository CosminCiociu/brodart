<?php

namespace YayMail\Shortcodes;

use YayMail\Utils\Helpers;
use YayMail\Utils\SingletonTrait;
use YayMail\Abstracts\BaseShortcode;

/**
 * Shortcodes for WC core fulfillment emails (WC 10.1, `fulfillments` feature).
 *
 * @since 4.4.7
 * @method: static FulfillmentShortcodes get_instance()
 */
class FulfillmentShortcodes extends BaseShortcode {
    use SingletonTrait;

    protected function __construct() {
        $this->available_email_ids = [ 'customer_fulfillment_created', 'customer_fulfillment_updated', 'customer_fulfillment_deleted' ];
        parent::__construct();
    }

    public function get_shortcodes() {
        $shortcodes   = [];
        $shortcodes[] = [
            'name'        => 'yaymail_fulfillment_details',
            'description' => __( 'Fulfillment Details', 'yaymail' ),
            'group'       => 'fulfillments',
            'callback'    => [ $this, 'yaymail_fulfillment_details' ],
        ];
        $shortcodes[] = [
            'name'        => 'yaymail_fulfillment_note',
            'description' => __( 'Fulfillment Note From The Store', 'yaymail' ),
            'group'       => 'fulfillments',
            'callback'    => [ $this, 'yaymail_fulfillment_note' ],
        ];
        return $shortcodes;
    }

    /**
     * Render WC's own fulfillment details (tracking info + items) and fulfillment meta,
     * via the same hooks the WC template fires.
     */
    public function yaymail_fulfillment_details( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        $order       = Helpers::get_order_from_shortcode_data( $render_data );
        $fulfillment = $render_data['fulfillment'] ?? null;

        if ( ! empty( $render_data['is_sample'] ) || empty( $order ) || empty( $fulfillment ) ) {
            return $this->get_sample_fulfillment_details();
        }

        $email = $render_data['email'] ?? '';

        ob_start();
        do_action( 'woocommerce_email_fulfillment_details', $order, $fulfillment, false, false, $email );
        do_action( 'woocommerce_email_fulfillment_meta', $order, $fulfillment, false, false, $email );
        return ob_get_clean();
    }

    /**
     * Store note passed to the fulfillment updated email. Empty when no note was given.
     */
    public function yaymail_fulfillment_note( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];

        if ( ! empty( $render_data['is_sample'] ) ) {
            $note = __( 'This is a sample note from the store. Your order has been updated with new tracking information.', 'woocommerce' );
        } else {
            $note = is_scalar( $render_data['customer_note'] ?? null ) ? trim( (string) $render_data['customer_note'] ) : '';
        }

        if ( '' === $note ) {
            return '';
        }

        return '<p><strong>' . esc_html__( 'Note from the store:', 'woocommerce' ) . '</strong></p><blockquote>' . wp_kses_post( wpautop( wptexturize( $note ) ) ) . '</blockquote>';
    }

    /**
     * Static preview for the editor, where no real fulfillment exists.
     */
    private function get_sample_fulfillment_details() {
        $rows = [
            __( 'Tracking number', 'yaymail' )   => '1Z999AA10123456784',
            __( 'Shipping provider', 'yaymail' ) => 'UPS',
            __( 'Items', 'yaymail' )             => __( 'Sample product × 1', 'yaymail' ),
        ];

        $html = '<table cellspacing="0" cellpadding="6" border="1" style="width: 100%; border-collapse: collapse;">';
        foreach ( $rows as $label => $value ) {
            $html .= '<tr><th scope="row" style="text-align: left;">' . esc_html( $label ) . '</th><td>' . esc_html( $value ) . '</td></tr>';
        }
        $html .= '</table>';

        return $html;
    }
}
