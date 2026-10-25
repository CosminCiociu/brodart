<?php

/**
 * Template for review request email.
 * @since 4.4.7
 */

use YayMail\Emails\CustomerReviewRequest;

defined( 'ABSPATH' ) || exit;

$template = CustomerReviewRequest::get_instance()->template;

if ( ! empty( $template ) ) {
    $content = $template->get_content( $args );
    yaymail_kses_post_e( $content );
}
