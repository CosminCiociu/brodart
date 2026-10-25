<?php

namespace YayMail\Emails;

use YayMail\Abstracts\BaseEmail;
use YayMail\Elements\ElementsLoader;
use YayMail\Utils\SingletonTrait;

/**
 * CustomerFulfillmentUpdated Class
 * From WC 10.1 (registered only when the `fulfillments` feature is enabled)
 *
 * @since 4.4.7
 * @method static CustomerFulfillmentUpdated get_instance()
 */
class CustomerFulfillmentUpdated extends BaseEmail {
    use SingletonTrait;

    protected function __construct() {
        $emails = \WC_Emails::instance()->get_emails();
        $email  = $emails['WC_Email_Customer_Fulfillment_Updated'] ?? null;
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
        $email_title = __( 'Your shipment has been updated', 'woocommerce' );
        // translators: customer name.
        $email_hi   = sprintf( esc_html__( 'Hi %s,', 'woocommerce' ), '[yaymail_billing_first_name]' );
        $email_text = esc_html__( 'Some details of your shipment have recently been updated. This may include tracking information, item contents, or delivery status.', 'woocommerce' ) . '</span></p><p><span>' . esc_html__( 'Here’s the latest info we have:', 'woocommerce' );

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
                        'rich_text' => '<p><span>' . $email_hi . '<br /><br /></span></p><p><span>' . $email_text . '</span></p>',
                    ],
                ],
                [
                    'type'       => 'Text',
                    'attributes' => [
                        'rich_text' => '[yaymail_fulfillment_note]',
                    ],
                ],
                [
                    'type'       => 'Text',
                    'attributes' => [
                        'rich_text' => '[yaymail_fulfillment_details]',
                    ],
                ],
                [
                    'type' => 'BillingShippingAddress',
                ],
                [
                    'type' => 'Footer',
                ],
            ]
        );
    }

    public function get_template_path() {
        return yaymail_get_template( 'emails/customer-fulfillment-updated.php', '', YAYMAIL_PLUGIN_PATH . 'templates/' );
    }
}
