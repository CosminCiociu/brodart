<?php

namespace YayMail\Shortcodes;

use YayMail\Utils\SingletonTrait;
use YayMail\Abstracts\BaseShortcode;

/**
 * Shortcodes for WC core customer order withdrawal request email (WC 11.2, `order_withdrawal` feature).
 * Values come from the WC template args: withdrawal_data (normalized form fields) and detail_rows.
 *
 * @since 4.4.7
 * @method: static OrderWithdrawalShortcodes get_instance()
 */
class OrderWithdrawalShortcodes extends BaseShortcode {
    use SingletonTrait;

    protected function __construct() {
        $this->available_email_ids = [ 'customer_order_withdrawal_requested' ];
        parent::__construct();
    }

    public function get_shortcodes() {
        $shortcodes   = [];
        $shortcodes[] = [
            'name'        => 'yaymail_withdrawal_details',
            'description' => __( 'Withdrawal Request Details', 'yaymail' ),
            'group'       => 'order_withdrawal',
            'callback'    => [ $this, 'yaymail_withdrawal_details' ],
        ];
        $shortcodes[] = [
            'name'        => 'yaymail_withdrawal_customer_name',
            'description' => __( 'Withdrawal Customer Name', 'yaymail' ),
            'group'       => 'order_withdrawal',
            'callback'    => [ $this, 'yaymail_withdrawal_customer_name' ],
        ];
        $shortcodes[] = [
            'name'        => 'yaymail_withdrawal_order_number',
            'description' => __( 'Withdrawal Order Number', 'yaymail' ),
            'group'       => 'order_withdrawal',
            'callback'    => [ $this, 'yaymail_withdrawal_order_number' ],
        ];
        return $shortcodes;
    }

    /**
     * Same label/value list WC prints (Submitted, Name, Email address, Order number, Withdrawing, Additional details).
     */
    public function yaymail_withdrawal_details( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        $rows        = ! empty( $render_data['detail_rows'] ) && is_array( $render_data['detail_rows'] ) ? $render_data['detail_rows'] : $this->get_sample_rows();

        $html = '<ul>';
        foreach ( $rows as $label => $value ) {
            $html .= '<li><strong>' . esc_html( $label ) . ':</strong> ' . nl2br( esc_html( (string) $value ) ) . '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function yaymail_withdrawal_customer_name( $data ) {
        $withdrawal = $this->get_withdrawal_data( $data );
        if ( null === $withdrawal ) {
            return esc_html( 'John Doe' );
        }
        return esc_html( trim( ( $withdrawal['first_name'] ?? '' ) . ' ' . ( $withdrawal['last_name'] ?? '' ) ) );
    }

    public function yaymail_withdrawal_order_number( $data ) {
        $withdrawal = $this->get_withdrawal_data( $data );
        if ( null === $withdrawal ) {
            return esc_html( '1234' );
        }
        return esc_html( (string) ( $withdrawal['order_number'] ?? '' ) );
    }

    private function get_withdrawal_data( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        return ! empty( $render_data['withdrawal_data'] ) && is_array( $render_data['withdrawal_data'] ) ? $render_data['withdrawal_data'] : null;
    }

    /**
     * Editor preview rows, same labels as WC's OrderWithdrawalEmailDataFormatter::get_detail_rows().
     */
    private function get_sample_rows() {
        return [
            __( 'Submitted', 'woocommerce' )          => wp_date( trim( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ),
            __( 'Name', 'woocommerce' )               => 'John Doe',
            __( 'Email address', 'woocommerce' )      => 'customer@example.com',
            __( 'Order number', 'woocommerce' )       => '1234',
            __( 'Withdrawing', 'woocommerce' )        => __( 'The full order', 'woocommerce' ),
            __( 'Additional details', 'woocommerce' ) => __( 'None provided', 'woocommerce' ),
        ];
    }
}
