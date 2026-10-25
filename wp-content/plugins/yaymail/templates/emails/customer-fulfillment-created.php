<?php

/**
 * Template for fulfillment created email.
 * @since 4.4.7
 */

use YayMail\Emails\CustomerFulfillmentCreated;

defined( 'ABSPATH' ) || exit;

$template = CustomerFulfillmentCreated::get_instance()->template;

if ( ! empty( $template ) ) {
    $content = $template->get_content( $args );
    yaymail_kses_post_e( $content );
}
