<?php
/**
 * Platform contract.
 *
 * Each product (YayMail for WooCommerce, Yay WP Email Customizer) provides one
 * implementation and registers it with the PlatformRegistry at bootstrap. Shared
 * core code reads platform-specific values from the resolved platform instead of
 * detecting the platform inline via defined()/screen-id/'yaymagic-email-builder' branches.
 *
 * @package YayMail\Platform
 */

namespace YayMail\Platform;

defined( 'ABSPATH' ) || exit;

interface PlatformInterface {

    /**
     * Stable platform key used across REST params, localize data, and lookups.
     *
     * @return string 'yaymail' | 'yaymagic-email-builder'
     */
    public function key(): string;

    /**
     * Absolute filesystem path of the plugin that hosts this platform's assets/core.
     *
     * @return string
     */
    public function plugin_path(): string;

    /**
     * Public URL of the plugin that hosts this platform's assets.
     *
     * @return string
     */
    public function plugin_url(): string;

    /**
     * WordPress admin screen id (hook suffix) of this platform's settings page.
     *
     * @return string
     */
    public function hook_suffix(): string;

    /**
     * Option name prefix for this platform's general attachments.
     *
     * @return string
     */
    public function attachment_option_name(): string;

    /**
     * Global header/footer option key for this platform.
     *
     * Literals are not derivable by a common suffix rule, so each platform returns
     * its own exact values.
     *
     * @param string $variant 'base' | 'enabled'.
     * @return string
     */
    public function ghf_option_key( string $variant = 'base' ): string;

    /**
     * The plugin adapter instance for this platform (license/menu config), or null.
     *
     * @return object|null
     */
    public function adapter();

    /**
     * Whether this platform IS the WooCommerce product (product identity).
     *
     * Distinct from woo_available(): identity is fixed per install, availability is
     * a runtime capability check.
     *
     * @return bool
     */
    public function is_woo_product(): bool;

    /**
     * Whether WooCommerce is active at runtime (capability, not identity).
     *
     * @return bool
     */
    public function woo_available(): bool;
}
