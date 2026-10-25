<?php

/**
 * Template for fulfillment deleted email.
 * @since 4.4.7
 */

use YayMail\Emails\CustomerFulfillmentDeleted;

defined( 'ABSPATH' ) || exit;

$template = CustomerFulfillmentDeleted::get_instance()->template;

if ( ! empty( $template ) ) {
    $content = $template->get_content( $args );
    yaymail_kses_post_e( $content );
}
