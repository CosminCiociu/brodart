<?php

/**
 * Template for confirm email address email.
 * @since 4.4.7
 */

use YayMail\Emails\CustomerVerifyEmail;

defined( 'ABSPATH' ) || exit;

$template = CustomerVerifyEmail::get_instance()->template;

if ( ! empty( $template ) ) {
    $content = $template->get_content( $args );
    yaymail_kses_post_e( $content );
}
