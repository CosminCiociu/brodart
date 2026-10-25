<?php

namespace YayMail\Emails;

use YayMail\Abstracts\BaseEmail;
use YayMail\Elements\ElementsLoader;
use YayMail\Utils\SingletonTrait;

/**
 * CustomerOrderWithdrawalRequested Class
 * WC core "Order withdrawal request received" email (customer copy).
 * From WC 11.2 (registered only when the `order_withdrawal` feature is enabled)
 *
 * The email object is the submitted form data, not a WC_Order, so it is a non-order email.
 *
 * @since 4.4.7
 * @method static CustomerOrderWithdrawalRequested get_instance()
 */
class CustomerOrderWithdrawalRequested extends BaseEmail {
    use SingletonTrait;

    public $email_types = [ YAYMAIL_NON_ORDER_EMAILS ];

    protected function __construct() {
        $emails = \WC_Emails::instance()->get_emails();
        $email  = $emails['WC_Email_Customer_Order_Withdrawal_Requested'] ?? null;
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
        $email_title     = __( 'We received your withdrawal request', 'woocommerce' );
        $email_text      = esc_html__( 'We have received your request to withdraw from the order below.', 'woocommerce' );
        $additional_text = esc_html__( 'We will review your request and contact you about next steps, including any refund due.', 'woocommerce' );

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
                        'rich_text' => '[yaymail_withdrawal_details]',
                    ],
                ],
                [
                    'type'       => 'Text',
                    'attributes' => [
                        'rich_text' => '<p><span>' . $additional_text . '</span></p>',
                    ],
                ],
                [
                    'type' => 'Footer',
                ],
            ]
        );
    }

    public function get_template_path() {
        return yaymail_get_template( 'emails/customer-order-withdrawal-requested.php', '', YAYMAIL_PLUGIN_PATH . 'templates/' );
    }
}
