<?php

/**
 * Template for back in stock notification email.
 * @since 4.4.7
 */

use YayMail\Emails\CustomerStockNotification;

defined( 'ABSPATH' ) || exit;

$template = CustomerStockNotification::get_instance()->template;

if ( ! empty( $template ) ) {
    $content = $template->get_content( $args );
    yaymail_kses_post_e( $content );
}
