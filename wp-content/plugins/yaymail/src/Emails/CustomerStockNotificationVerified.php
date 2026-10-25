<?php

namespace YayMail\Emails;

use YayMail\Abstracts\BaseEmail;
use YayMail\Elements\ElementsLoader;
use YayMail\Utils\SingletonTrait;

/**
 * CustomerStockNotificationVerified Class
 * WC core Back-in-Stock "Sign-up successful" email.
 * From WC 10.2 (registered only when the `customer_stock_notifications` feature is enabled)
 *
 * @since 4.4.7
 * @method static CustomerStockNotificationVerified get_instance()
 */
class CustomerStockNotificationVerified extends BaseEmail {
    use SingletonTrait;

    public $email_types = [ YAYMAIL_NON_ORDER_EMAILS ];

    protected function __construct() {
        $emails = \WC_Emails::instance()->get_emails();
        $email  = $emails['WC_Email_Customer_Stock_Notification_Verified'] ?? null;
        if ( ! $email ) {
            return;
        }
        $this->id         = $email->id;
        $this->title      = $email->get_title();
        $this->root_email = $email;
        $this->recipient  = function_exists( 'yaymail_get_email_recipient_zone' ) ? yaymail_get_email_recipient_zone( $email ) : '';

        $this->render_priority = apply_filters( 'yaymail_email_render_priority', $this->render_priority, $this->id );
        add_filter( 'wc_get_template', [ $this, 'get_template_file' ], $this->render_priority ?? 10, 3 );
        $this->maybe_disable_block_email_editor();
    }

    public function get_default_elements() {
        $email_title = __( 'Sign-up successful', 'woocommerce' );
        $email_text  = esc_html__( 'Thanks for joining the waitlist! You will hear from us again when "[yaymail_stock_notification_product_name]" is back in stock.', 'yaymail' );
        $footer_text = esc_html__( 'You have received this message because your e-mail address was used to sign up for stock notifications on our store.', 'woocommerce' ) . ' [yaymail_stock_notification_unsubscribe_text]';

        return ElementsLoader::load_elements(
            [
                [
                    'type' => 'Logo',
                ],
                [
                    'type'       => 'Heading',
                    'attributes' => [
                        'rich_text' => $email_title,
                    ],
                ],
                [
                    'type'       => 'Text',
                    'attributes' => [
                        'rich_text' => '<p><span>' . $email_text . '</span></p>',
                    ],
                ],
                [
                    'type'       => 'Text',
                    'attributes' => [
                        'rich_text' => '[yaymail_stock_notification_product]',
                    ],
                ],
                [
                    'type'       => 'Text',
                    'attributes' => [
                        'rich_text' => '<p><span style="font-size: 12px;">' . $footer_text . '</span></p>',
                    ],
                ],
                [
                    'type' => 'Footer',
                ],
            ]
        );
    }

    public function get_template_path() {
        return yaymail_get_template( 'emails/customer-stock-notification-verified.php', '', YAYMAIL_PLUGIN_PATH . 'templates/' );
    }
}
