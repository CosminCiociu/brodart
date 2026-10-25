<?php

namespace YayMail\Emails;

use YayMail\Utils\Helpers;
use YayMail\Utils\SingletonTrait;
use YayMail\Utils\TemplateHelpers;
use YayMail\YayMailEmails;
use YayMail\YayMailTemplate;

/**
 * EmailsLoader Class
 *
 * @method static EmailsLoader get_instance()
 */
class EmailsLoader {

    use SingletonTrait;

    private function __construct() {
        $this->init_hooks();
        $this->load_emails();
    }

    private function init_hooks() {
        add_action( 'yaymail_before_email_content', [ $this, 'before_email_content' ], 10, 1 );
        add_action( 'yaymail_after_email_content', [ $this, 'after_email_content' ], 10, 1 );

        /**
         * Email references hooks
         */
        add_filter( 'safe_style_css', [ $this, 'filter_safe_style_css' ], 10, 1 );
        add_filter( 'woocommerce_email_styles', [ $this, 'inject_custom_css' ] );
    }

    private function load_emails() {

        $yaymail_emails = YayMailEmails::get_instance();

        if ( yaymail_is_wc_installed() ) {
            $yaymail_emails->register( \YayMail\Emails\NewOrder::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CancelledOrder::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerCancelledOrder::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\FailedOrder::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerFailedOrder::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerOnHoldOrder::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerProcessingOrder::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerCompletedOrder::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerRefundedOrder::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerInvoice::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerNote::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerResetPassword::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerNewAccount::get_instance() );
            /**
             * POS emails, WC 9.9.3
             *
             * @since 4.0.6
             */

            $yaymail_emails->register( \YayMail\Emails\CustomerPOSCompletedOrder::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerPOSRefundedOrder::get_instance() );

            /**
             * Fulfillment emails (WC 10.1, `fulfillments` feature) and
             * review request email (`customer_review_request` feature)
             *
             * @since 4.4.7
             */
            $yaymail_emails->register( \YayMail\Emails\CustomerFulfillmentCreated::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerFulfillmentUpdated::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerFulfillmentDeleted::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerReviewRequest::get_instance() );

            /**
             * Core Back-in-Stock emails (WC 10.2, `customer_stock_notifications` feature)
             *
             * @since 4.4.7
             */
            $yaymail_emails->register( \YayMail\Emails\CustomerStockNotificationVerify::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerStockNotificationVerified::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerStockNotification::get_instance() );

            /**
             * Confirm email address (WC 11.0) and
             * customer order withdrawal request (WC 11.2, `order_withdrawal` feature)
             *
             * @since 4.4.7
             */
            $yaymail_emails->register( \YayMail\Emails\CustomerVerifyEmail::get_instance() );
            $yaymail_emails->register( \YayMail\Emails\CustomerOrderWithdrawalRequested::get_instance() );
        }//end if

        $yaymail_emails->register( \YayMail\Emails\GlobalHeaderFooter::get_instance() );

        do_action( 'yaymail_register_emails', $yaymail_emails );

        $this->register_subject_shortcode_filters( $yaymail_emails );
    }

    /**
     * Wire one woocommerce_email_subject_{id} filter per registered YayMail email so
     * YayMail shortcodes typed into the subject resolve at send time.
     *
     * @param YayMailEmails $yaymail_emails Registered emails collection.
     */
    private function register_subject_shortcode_filters( $yaymail_emails ) {
        foreach ( $yaymail_emails->get_emails() as $email ) {
            $email_id = $email->get_id();
            if ( empty( $email_id ) ) {
                continue;
            }

            add_filter(
                'woocommerce_email_subject_' . $email_id,
                function( $subject, $object = null ) use ( $email_id ) {
                    return self::render_subject_shortcodes( $subject, $object, $email_id );
                },
                10,
                2
            );
        }
    }

    /**
     * Resolve YayMail shortcodes in a subject, but only when the email's YayMail
     * template is active. Uses real order context only - never the sample-data
     * fallback - so a real outgoing subject never leaks placeholder values.
     *
     * @param string $subject  Subject after WooCommerce format_string().
     * @param mixed  $object    Email object; a WC_Order for order emails.
     * @param string $email_id  WooCommerce email id (also the YayMail template name).
     * @return string
     */
    public static function render_subject_shortcodes( $subject, $object, $email_id ) {
        if ( ! is_string( $subject ) || false === strpos( $subject, '[' ) ) {
            return $subject;
        }

        $template = new YayMailTemplate( $email_id );
        if ( ! $template->is_enabled() ) {
            return $subject;
        }

        $order         = ( $object instanceof \WC_Order ) ? $object : null;
        $executor_data = [
            'template'       => $template,
            'render_data'    => $order ? [ 'order' => $order ] : [],
            'settings'       => yaymail_settings(),
            // Real send: resolve to actual values, never editor placeholder tokens.
            'is_placeholder' => false,
        ];

        return TemplateHelpers::process_email_subject( $subject, $template, $executor_data );
    }

    public function before_email_content( $template ) {
        include Helpers::get_plugin_path() . 'templates/emails/before-email-content.php';
    }

    public function after_email_content( $template ) {
        include Helpers::get_plugin_path() . 'templates/emails/after-email-content.php';
    }

    public function filter_safe_style_css( $default_array ) {
        $additional_allowed_css_attributes = [ 'display', 'background-repeat', 'word-wrap' ];
        return array_merge( $default_array, $additional_allowed_css_attributes );
    }

    public function inject_custom_css( $css = '' ) {
        $css             .= '.yaymail-element table { border-spacing: 0; }';
        $yaymail_settings = yaymail_settings();
        if ( ! boolval( $yaymail_settings['enable_custom_css'] ?? false ) ) {
            return $css;
        }
        $custom_css = isset( $yaymail_settings['custom_css'] ) ? $yaymail_settings['custom_css'] : '';
        $css       .= $custom_css;
        $css        = apply_filters( 'yaymail_email_styles', $css );
        return $css;
    }
}
