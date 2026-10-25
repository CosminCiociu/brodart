<?php

/**
 * Template for stock notification sign-up verification email.
 * @since 4.4.7
 */

use YayMail\Emails\CustomerStockNotificationVerify;

defined( 'ABSPATH' ) || exit;

$template = CustomerStockNotificationVerify::get_instance()->template;

if ( ! empty( $template ) ) {
    $content = $template->get_content( $args );
    yaymail_kses_post_e( $content );
}
