<?php

/**
 * Template for fulfillment updated email.
 * @since 4.4.7
 */

use YayMail\Emails\CustomerFulfillmentUpdated;

defined( 'ABSPATH' ) || exit;

$template = CustomerFulfillmentUpdated::get_instance()->template;

if ( ! empty( $template ) ) {
    $content = $template->get_content( $args );
    yaymail_kses_post_e( $content );
}
