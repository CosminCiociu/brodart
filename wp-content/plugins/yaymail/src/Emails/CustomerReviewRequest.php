<?php

namespace YayMail\Emails;

use YayMail\Abstracts\BaseEmail;
use YayMail\Elements\ElementsLoader;
use YayMail\Utils\SingletonTrait;

/**
 * CustomerReviewRequest Class
 * From WC 10.x (registered only when the `customer_review_request` feature is enabled)
 *
 * @since 4.4.7
 * @method static CustomerReviewRequest get_instance()
 */
class CustomerReviewRequest extends BaseEmail {
    use SingletonTrait;

    protected function __construct() {
        $emails = \WC_Emails::instance()->get_emails();
        $email  = $emails['WC_Email_Customer_Review_Request'] ?? null;
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
        $email_title = $this->root_email ? $this->root_email->get_default_heading() : '';
        // translators: customer name.
        $email_hi   = sprintf( esc_html__( 'Hi %s,', 'woocommerce' ), '[yaymail_billing_first_name]' );
        $email_text = esc_html__( 'We’d love to know what you thought of the products you ordered. Your review helps other shoppers make better decisions and helps us improve.', 'woocommerce' );
        // translators: 1: order number, 2: order date.
        $order_text = sprintf( esc_html__( 'Order #%1$s (%2$s)', 'woocommerce' ), '[yaymail_order_number]', '[yaymail_order_date]' );

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
                    'type'       => 'Button',
                    'attributes' => [
                        'text'  => esc_html__( 'Leave a review', 'woocommerce' ),
                        'url'   => '[yaymail_review_order_url]',
                        'align' => 'left',
                    ],
                ],
                [
                    'type'       => 'Text',
                    'attributes' => [
                        'rich_text' => '<p><span style="font-size: 12px;">' . $order_text . '</span></p>',
                    ],
                ],
                [
                    'type' => 'Footer',
                ],
            ]
        );
    }

    public function get_template_path() {
        return yaymail_get_template( 'emails/customer-review-request.php', '', YAYMAIL_PLUGIN_PATH . 'templates/' );
    }
}
