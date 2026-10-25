<?php

namespace YayMail\Emails;

use YayMail\Abstracts\BaseEmail;
use YayMail\Elements\ElementsLoader;
use YayMail\Utils\SingletonTrait;

/**
 * CustomerStockNotificationVerify Class
 * WC core Back-in-Stock double opt-in "Confirm sign-up" email.
 * From WC 10.2 (registered only when the `customer_stock_notifications` feature is enabled)
 *
 * @since 4.4.7
 * @method static CustomerStockNotificationVerify get_instance()
 */
class CustomerStockNotificationVerify extends BaseEmail {
    use SingletonTrait;

    public $email_types = [ YAYMAIL_NON_ORDER_EMAILS ];

    protected function __construct() {
        $emails = \WC_Emails::instance()->get_emails();
        $email  = $emails['WC_Email_Customer_Stock_Notification_Verify'] ?? null;
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
        $email_title = __( 'Confirm sign-up', 'woocommerce' );
        $email_text  = esc_html__( 'Please follow the link below to complete the sign-up process and join the "[yaymail_stock_notification_product_name]" waitlist.', 'yaymail' );
        // translators: %s: verification expiration duration.
        $expiration_text = sprintf( esc_html__( 'This link will remain active for %s.', 'woocommerce' ), '[yaymail_stock_notification_verification_expiration]' );
        $footer_text     = esc_html__( 'You have received this message because your e-mail address was used to sign up for stock notifications on our store. Wasn\'t you? Please get in touch with us if you keep receiving these messages.', 'woocommerce' );

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
                    'type'       => 'Button',
                    'attributes' => [
                        'text'  => _x( 'Confirm', 'Stock Notification confirm notification', 'woocommerce' ),
                        'url'   => '[yaymail_stock_notification_verification_url]',
                        'align' => 'left',
                    ],
                ],
                [
                    'type'       => 'Text',
                    'attributes' => [
                        'rich_text' => '<p><span>' . $expiration_text . '</span></p>',
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
        return yaymail_get_template( 'emails/customer-stock-notification-verify.php', '', YAYMAIL_PLUGIN_PATH . 'templates/' );
    }
}
