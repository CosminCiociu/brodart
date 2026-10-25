<?php
namespace YayMail;

use YayMail\Utils\Helpers;
use YayMail\Emails\EmailsLoader;
use YayMail\Engine\Backend\SettingsPage;
use YayMail\Engine\RestAPI;
use YayMail\Integrations\IntegrationsLoader;
use YayMail\PostTypes\TemplatePostType;
use YayMail\Shortcodes\ShortcodesLoader;
use YayMail\Utils\SingletonTrait;
use YayMail\TemplatePatterns\PatternsLoader;
use YayMail\TemplatePatterns\SectionTemplatesLoader;
use YayMail\PreviewEmail\PreviewEmailsLoader;
use YayMail\Notices\NoticeMain;
use YayMail\SocialIcons\SocialIconEndpoint;
use YayMail\TemplateLibrary\LibraryTemplateSchema;
use YayMail\TemplateLibrary\TemplateLibraryLoader;
/**
 * YayMail Plugin Initializer
 *
 * @method static Initialize get_instance()
 */
class Initialize {

    use SingletonTrait;

    /**
     * The Constructor that load the engine classes
     */
    protected function __construct() {
        // Procedural helpers (yaymail_version(), yaymail_is_wc_installed(), ...) are used by
        // every hook callback below, so load them here rather than inside init_core(), whose
        // hook is filterable and may run after init_modules().
        require_once Helpers::get_plugin_path() . 'src/Functions.php';

        I18n::get_instance();

        /**
         * Handle init core
         * Emails, Templates, Elements, Shortcodes, Integrations
         * $hook_name => $priority
         */
        $initialization_core_hooks = [
            apply_filters( 'yaymail_temp_init_hook_name', 'init' ) => 10,
            // Integrate for Mastercard Gateway plugin
            'woocommerce_api_mastercard_gateway' => 10,
        ];
        foreach ( $initialization_core_hooks as $hook => $priority ) {
            add_action( $hook, [ $this, 'init_core' ], $priority ?? 10 );
        }

        add_action( 'init', [ $this, 'init_modules' ] );
    }

    public function init_core() {
        do_action( 'yaymail_init_start' );

        /**
         * Core Integrations
         */
        IntegrationsLoader::get_instance();

        EmailsLoader::get_instance();
        ShortcodesLoader::get_instance();
    }

    public function init_modules() {

        $version_current        = yaymail_version();
        $version_old            = get_option( 'yaymail_version' );
        $version_current_backup = get_option( 'yaymail_version_backup' );

        if ( $version_current !== $version_old && yaymail_is_wc_installed() ) {
            if ( $version_current_backup !== $version_current ) {
                \YayMail\Migrations\MainMigration::get_instance()->migrate();

                update_option( 'yaymail_version', $version_current );
                update_option( 'yaymail_version_backup', $version_current );
            }
        }

        if ( yaymail_is_wc_installed() ) {
            WooHandler::get_instance();
        }

        /**
         * Preview Email loader
         */

        PreviewEmailsLoader::get_instance();

        /**
         * Core core filters
         */

        SectionTemplatesLoader::get_instance();
        PatternsLoader::get_instance();

        // Template Library only ships WooCommerce order-email designs (Completed,
        // RefundedOrder, NewOrder, ...). Skip its directory scan/class instantiation
        // (41 files, ~23k lines) and the per-request "SHOW TABLES" check when
        // WooCommerce isn't active -- it noticeably slows down every admin page load
        // on WP-only (yaymagic-email-builder) sites for a feature they can't use.
        if ( yaymail_is_wc_installed() ) {
            TemplateLibraryLoader::get_instance();
            LibraryTemplateSchema::maybe_create_table();
        }

        /**
         * Initialize rest api
         */
        RestAPI::get_instance();

        /**
         * Initialize pages
         */
        SettingsPage::get_instance();

        TemplatePostType::get_instance();
        Ajax::get_instance();

        /**
         * Notices
         */
        NoticeMain::get_instance();

        /**
         * Social icon dynamic tint endpoint
         */
        SocialIconEndpoint::get_instance();

        do_action( 'yaymail_loaded' );
    }

    public function current_yaymail_version() {
        $version = 'yaymail/yaymail.php';
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $yaymail_files = [
            'yaymail/yaymail.php',
            'yaymail-pro/yaymail.php',
            'email-customizer-for-woocommerce/yaymail.php',
        ];

        foreach ( $yaymail_files as $file ) {
            if ( is_plugin_active( $file ) || is_plugin_active_for_network( $file ) ) {
                $version = $file;
                break;
            }
        }

        return $version;
    }
}
