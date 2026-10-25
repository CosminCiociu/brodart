<?php

namespace YayMail\Shortcodes;

use YayMail\Utils\SingletonTrait;
use YayMail\Abstracts\BaseShortcode;

/**
 * Shortcodes for WC core "Confirm email address" email (WC 11.0).
 * Values come from the WC template args: user_display_name, user_email, verify_url.
 *
 * @since 4.4.7
 * @method: static VerifyEmailShortcodes get_instance()
 */
class VerifyEmailShortcodes extends BaseShortcode {
    use SingletonTrait;

    protected function __construct() {
        $this->available_email_ids = [ 'customer_verify_email' ];
        parent::__construct();
    }

    public function get_shortcodes() {
        $shortcodes   = [];
        $shortcodes[] = [
            'name'        => 'yaymail_verify_email_url',
            'description' => __( 'Confirm Email Address URL', 'yaymail' ),
            'group'       => 'verify_email',
            'callback'    => [ $this, 'yaymail_verify_email_url' ],
        ];
        $shortcodes[] = [
            'name'        => 'yaymail_verify_email_user_display_name',
            'description' => __( 'Customer Display Name', 'yaymail' ),
            'group'       => 'verify_email',
            'callback'    => [ $this, 'yaymail_verify_email_user_display_name' ],
        ];
        $shortcodes[] = [
            'name'        => 'yaymail_verify_email_user_email',
            'description' => __( 'Email Address Being Confirmed', 'yaymail' ),
            'group'       => 'verify_email',
            'callback'    => [ $this, 'yaymail_verify_email_user_email' ],
        ];
        return $shortcodes;
    }

    public function yaymail_verify_email_url( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        return ! empty( $render_data['verify_url'] ) ? esc_url( $render_data['verify_url'] ) : esc_url( home_url() );
    }

    public function yaymail_verify_email_user_display_name( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        if ( ! empty( $render_data['user_display_name'] ) ) {
            return esc_html( $render_data['user_display_name'] );
        }
        // Editor preview: use the current admin.
        $user = wp_get_current_user();
        return esc_html( ! empty( $user->first_name ) ? $user->first_name : ( $user->user_login ? $user->user_login : 'John' ) );
    }

    public function yaymail_verify_email_user_email( $data ) {
        $render_data = isset( $data['render_data'] ) ? $data['render_data'] : [];
        if ( ! empty( $render_data['user_email'] ) ) {
            return esc_html( $render_data['user_email'] );
        }
        $user = wp_get_current_user();
        return esc_html( $user->user_email ? $user->user_email : 'customer@example.com' );
    }
}
