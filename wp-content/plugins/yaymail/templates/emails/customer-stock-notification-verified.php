<?php

/**
 * Template for stock notification sign-up confirmation email.
 * @since 4.4.7
 */

use YayMail\Emails\CustomerStockNotificationVerified;

defined( 'ABSPATH' ) || exit;

$template = CustomerStockNotificationVerified::get_instance()->template;

if ( ! empty( $template ) ) {
    $content = $template->get_content( $args );
    yaymail_kses_post_e( $content );
}
