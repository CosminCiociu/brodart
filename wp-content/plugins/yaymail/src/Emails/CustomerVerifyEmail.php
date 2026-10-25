<?php

namespace YayMail\Emails;

use YayMail\Abstracts\BaseEmail;
use YayMail\Elements\ElementsLoader;
use YayMail\Utils\SingletonTrait;

/**
 * CustomerVerifyEmail Class
 * WC core "Confirm email address" email: asks a customer to verify their email
 * so past guest orders get linked to their account.
 * From WC 11.0 (always registered)
 *
 * @since 4.4.7
 * @method static CustomerVerifyEmail get_instance()
 */
class CustomerVerifyEmail extends BaseEmail {
    use SingletonTrait;

    public $email_types = [ YAYMAIL_NON_ORDER_EMAILS ];

    protected function __construct() {
        $emails = \WC_Emails::instance()->get_emails();
        $email  = $emails['WC_Email_Customer_Verify_Email'] ?? null;
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
        $email_title = __( 'Confirm your email address', 'woocommerce' );
        // translators: %s: customer first name, or username if name is not available.
        $email_hi = sprintf( esc_html__( 'Hi %s,', 'woocommerce' ), '[yaymail_verify_email_user_display_name]' );
        // translators: %s: the customer's email address.
        $email_text   = sprintf( esc_html__( "Once you've confirmed that %s is your email address, we'll link any past orders to your account.", 'woocommerce' ), '<b>[yaymail_verify_email_user_email]</b>' );
        $ignore_text  = esc_html__( "If you didn't request this email, there's nothing to worry about, and you can safely ignore it.", 'woocommerce' );
        $closing_text = __( 'Thanks for reading.', 'woocommerce' );

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
                        'text'  => __( 'Confirm email address', 'woocommerce' ),
                        'url'   => '[yaymail_verify_email_url]',
                        'align' => 'left',
                    ],
                ],
                [
                    'type'       => 'Text',
                    'attributes' => [
                        'rich_text' => '<p><span>' . $ignore_text . '</span></p><p><span>' . esc_html( $closing_text ) . '</span></p>',
                    ],
                ],
                [
                    'type' => 'Footer',
                ],
            ]
        );
    }

    public function get_template_path() {
        return yaymail_get_template( 'emails/customer-verify-email.php', '', YAYMAIL_PLUGIN_PATH . 'templates/' );
    }
}
