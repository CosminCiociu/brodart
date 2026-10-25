<?php

namespace YayMail\Emails;

use YayMail\Abstracts\BaseEmail;
use YayMail\Elements\ElementsLoader;
use YayMail\Utils\SingletonTrait;

/**
 * CustomerStockNotification Class
 * WC core Back-in-Stock "It's back in stock!" email.
 * From WC 10.2 (registered only when the `customer_stock_notifications` feature is enabled)
 *
 * @since 4.4.7
 * @method static CustomerStockNotification get_instance()
 */
class CustomerStockNotification extends BaseEmail {
    use SingletonTrait;

    public $email_types = [ YAYMAIL_NON_ORDER_EMAILS ];

    protected function __construct() {
        $emails = \WC_Emails::instance()->get_emails();
        $email  = $emails['WC_Email_Customer_Stock_Notification'] ?? null;
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
        $email_title = __( 'It\'s back in stock!', 'woocommerce' );
        $email_text  = esc_html__( 'Great news: "[yaymail_stock_notification_product_name]" is now available for purchase.', 'yaymail' );
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
                    'type'       => 'Button',
                    'attributes' => [
                        'text'  => _x( 'Shop Now', 'Email notification', 'woocommerce' ),
                        'url'   => '[yaymail_stock_notification_product_url]',
                        'align' => 'left',
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
        return yaymail_get_template( 'emails/customer-stock-notification.php', '', YAYMAIL_PLUGIN_PATH . 'templates/' );
    }
}
