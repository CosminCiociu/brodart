<?php
/**
 * WooCommerce platform implementation (the "yaymail" product).
 *
 * @package YayMail\Platform
 */

namespace YayMail\Platform;

defined( 'ABSPATH' ) || exit;

class YaymailPlatform implements PlatformInterface {

    public function key(): string {
        return 'yaymail';
    }

    public function plugin_path(): string {
        return YAYMAIL_PLUGIN_PATH;
    }

    public function plugin_url(): string {
        return YAYMAIL_PLUGIN_URL;
    }

    public function hook_suffix(): string {
        $prefix = defined( 'YAYMAIL_PREFIX' ) ? YAYMAIL_PREFIX : 'yaymail';
        return 'yaycommerce_page_' . $prefix . '-settings';
    }

    public function attachment_option_name(): string {
        return YAYMAIL_ATTACHMENT_OPTION_NAME;
    }

    public function ghf_option_key( string $variant = 'base' ): string {
        return 'enabled' === $variant ? 'global_header_footer_enabled' : 'yaymail_global_header_footer';
    }

    public function adapter() {
        return class_exists( 'YaymailPluginAdapter', false ) ? new \YaymailPluginAdapter() : null;
    }

    public function is_woo_product(): bool {
        return true;
    }

    public function woo_available(): bool {
        return class_exists( 'WooCommerce' );
    }
}
