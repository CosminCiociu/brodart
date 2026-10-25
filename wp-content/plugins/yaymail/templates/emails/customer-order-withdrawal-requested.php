<?php

/**
 * Template for customer order withdrawal request email.
 * @since 4.4.7
 */

use YayMail\Emails\CustomerOrderWithdrawalRequested;

defined( 'ABSPATH' ) || exit;

$template = CustomerOrderWithdrawalRequested::get_instance()->template;

if ( ! empty( $template ) ) {
    $content = $template->get_content( $args );
    yaymail_kses_post_e( $content );
}
