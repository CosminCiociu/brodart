<?php
/**
 * Plugin Name: YayMail - WooCommerce Email Customizer
 * Plugin URI: https://yaycommerce.com/yaymail-woocommerce-email-customizer/
 * Description: Create awesome transactional emails with a drag and drop email builder
 * Version: 4.4.7
 * Author: YayCommerce
 * Author URI: https://yaycommerce.com
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: yaymail
 * Requires at least: 4.7
 * Requires PHP: 5.4
 * WC requires at least: 3.0
 * WC tested up to: 11.2
 * Domain Path: /i18n/languages/
 *
 * @package YayMail
 */

namespace YayMail;

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'YAYMAIL_PREFIX' ) ) {
    define( 'YAYMAIL_PREFIX', 'yaymail' );
}

if ( ! defined( 'YAYMAIL_DEBUG' ) ) {
    define( 'YAYMAIL_DEBUG', false );
}

if ( ! defined( 'YAYMAIL_VERSION' ) ) {
    define( 'YAYMAIL_VERSION', '4.4.7' );
}

if ( ! defined( 'YAYMAIL_PLUGIN_URL' ) ) {
    define( 'YAYMAIL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'YAYMAIL_PLUGIN_PATH' ) ) {
    define( 'YAYMAIL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YAYMAIL_PLUGIN_BASENAME' ) ) {
    define( 'YAYMAIL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YAYMAIL_IS_DEVELOPMENT' ) ) {
    define( 'YAYMAIL_IS_DEVELOPMENT', false );
}

if ( ! defined( 'YAYMAIL_REST_NAMESPACE' ) ) {
    define( 'YAYMAIL_REST_NAMESPACE', 'yaymail/v1' );
}

if ( ! defined( 'YAYMAIL_MENU_PRIORITY' ) ) {
    define( 'YAYMAIL_MENU_PRIORITY', 100 );
}

// Lite customizer JS expects a single { global_header_elements, global_footer_elements }
// object, not the multi-language array used by pro. SettingsPage gates on this flag.
if ( ! defined( 'YAYMAIL_LITE_LEGACY_GHF_SHAPE' ) ) {
    define( 'YAYMAIL_LITE_LEGACY_GHF_SHAPE', true );
}

$yaymail_has_required_deps = true;
if ( function_exists( 'YayMail\\init' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'templates/fallbacks/fallback-exists.php';
    $yaymail_has_required_deps = false;
}

if ( version_compare( PHP_VERSION, '7.2', '<' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'templates/fallbacks/fallback-minimum-php.php';
    $yaymail_has_required_deps = false;
}

if ( version_compare( $GLOBALS['wp_version'], '5.2', '<' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'templates/fallbacks/fallback-minimum-wp.php';
    $yaymail_has_required_deps = false;
}

if ( ! $yaymail_has_required_deps ) {
    add_action(
        'admin_init',
        function() {
            deactivate_plugins( plugin_basename( __FILE__ ) );
        }
    );

    // Return early to prevent loading the plugin.
    return;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/YaymailPluginAdapter.php';

spl_autoload_register(
    function ( $class ) {
        $prefix = 'YayMail\\';

        if ( strpos( $class, $prefix ) !== 0 ) {
            return;
        }

        $relative      = substr( $class, strlen( $prefix ) );
        $relative_path = str_replace( '\\', '/', $relative ) . '.php';

        $core_file = YAYMAIL_PLUGIN_PATH . 'src/' . $relative_path;

        // Check if yay-wp-pro active
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $yaymail_wp_pro_plugin_file = 'yaymagic-email-builder-pro/yaymagic-email-builder.php';
        $yaymail_wp_pro_active      = is_plugin_active( $yaymail_wp_pro_plugin_file ) || is_plugin_active_for_network( $yaymail_wp_pro_plugin_file );

        if ( $yaymail_wp_pro_active && defined( 'YAYWP_PLUGIN_PATH' ) ) {
            $core_file = YAYWP_PLUGIN_PATH . 'src/' . $relative_path;
        }

        if ( file_exists( $core_file ) ) {
            require $core_file;
        }
    },
    true,
    true
);

// Declare this platform so shared core reads platform-specific values from the
// registry instead of detecting the platform inline. Registered after the core
// autoloader above so PlatformRegistry/PlatformInterface resolve, and before Initialize.
\YayMail\Platform\PlatformRegistry::register( new \YayMail\Platform\YaymailPlatform() );

/**
 * Initialize constants
 */
Constants\ConstantsHandler::get_instance();

if ( ! function_exists( 'install_yaymail_admin_notice' ) ) {
    function install_yaymail_admin_notice() {
        ?>
<div class="error">
    <p>
        <?php
                // translators: %s: search WooCommerce plugin link
                printf( 'YayMail ' . esc_html__( 'is enabled but not effective. It requires %1$sWooCommerce%2$s in order to work.', 'yaymail' ), '<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '">', '</a>' );
        ?>
    </p>
</div>
        <?php
    }
}

if ( ! function_exists( 'YayMail\\init' ) ) {
    function init() {
        \YayMailScoped\YayCommerce\AdminShell\AdminShell::boot();
        \YayMailScoped\YayCommerce\AdminShell\AdminShell::register_plugin(
            new \YaymailPluginAdapter()
        );
        if ( ! function_exists( 'WC' ) ) {
            add_action( 'admin_notices', 'YayMail\\install_yaymail_admin_notice' );
        } else {
            add_action( 'before_woocommerce_init', 'YayMail\\yaymail_enable_compatible_hpos' );
            do_action( 'yaymail_before_init' );

            \YayMail\Initialize::get_instance();
        }//end if
    }
}//end if

if ( ! function_exists( 'yaymail_enable_compatible_hpos' ) ) {
    function yaymail_enable_compatible_hpos() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );

            // Set compatible for active addons. Read the active-plugin options instead of
            // scanning the plugin directory: this hook fires on every request and that scans and
            // parses every plugin header on disk.
            $active_plugins = array_merge(
                (array) get_option( 'active_plugins', [] ),
                array_keys( (array) get_site_option( 'active_sitewide_plugins', [] ) )
            );
            foreach ( $active_plugins as $key ) {
                $is_yaymail_addon = strpos( $key, 'yaymail-addon' ) !== false || strpos( $key, 'email-customizer' ) !== false;
                if ( $is_yaymail_addon ) {
                    \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $key, true );
                }
            }
        }
    }
}

if ( ! wp_installing() ) {
    add_action( 'plugins_loaded', 'YayMail\\init' );
}

register_activation_hook( __FILE__, [ \YayMail\Engine\ActDeact::class, 'activate' ] );
register_deactivation_hook( __FILE__, [ \YayMail\Engine\ActDeact::class, 'deactivate' ] );