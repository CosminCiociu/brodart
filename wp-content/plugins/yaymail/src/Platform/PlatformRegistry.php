<?php
/**
 * Platform registry.
 *
 * Holds the platforms registered by each active product's bootstrap and resolves
 * the right platform for a given context. There is intentionally no ambiguous
 * "current platform" accessor: every consumer resolves by an explicit context
 * (registered key, admin screen, install check, or on-disk host).
 *
 * @package YayMail\Platform
 */

namespace YayMail\Platform;

defined( 'ABSPATH' ) || exit;

final class PlatformRegistry {

    /**
     * Registered platforms keyed by PlatformInterface::key().
     *
     * @var array<string, PlatformInterface>
     */
    private static $platforms = [];

    /**
     * Register a platform. Idempotent: last registration wins for a given key, so
     * both products registering (both-active) is safe.
     *
     * @param PlatformInterface $platform Platform to register.
     * @return void
     */
    public static function register( PlatformInterface $platform ) {
        self::$platforms[ $platform->key() ] = $platform;
    }

    /**
     * Whether a platform is registered for the given key.
     *
     * @param string $key Platform key.
     * @return bool
     */
    public static function has( string $key ): bool {
        return isset( self::$platforms[ $key ] );
    }

    /**
     * Get a registered platform by key, or null when not registered.
     *
     * @param string $key Platform key.
     * @return PlatformInterface|null
     */
    public static function get( string $key ) {
        return self::$platforms[ $key ] ?? null;
    }

    /**
     * Resolve the platform whose settings screen matches the given admin screen.
     *
     * @param \WP_Screen|null $screen Current admin screen.
     * @return PlatformInterface|null
     */
    public static function from_screen( $screen ) {
        if ( ! $screen || empty( $screen->id ) ) {
            return null;
        }

        foreach ( self::$platforms as $platform ) {
            if ( $platform->hook_suffix() === $screen->id ) {
                return $platform;
            }
        }

        return null;
    }

    /**
     * The platform that physically hosts the loaded core on disk.
     *
     * Preserves the historical behavior of Helpers::get_plugin_path/url: the WP
     * plugin hosts the core when YayMail lite + Yay WP Pro are both active,
     * otherwise the WooCommerce plugin does. Consumed only by get_plugin_path/url.
     *
     * @return PlatformInterface
     */
    public static function host_platform(): PlatformInterface {
        if ( self::wp_hosts_core() && self::has( 'yaymagic-email-builder' ) ) {
            return self::$platforms['yaymagic-email-builder'];
        }

        if ( self::has( 'yaymail' ) ) {
            return self::$platforms['yaymail'];
        }

        // No 'yaymail' platform registered. Historically get_plugin_path/url returned
        // the YAYMAIL_PLUGIN_* constants in this case, which YaymailPlatform mirrors.
        // Only warn on a genuinely empty registry (a too-early read before bootstrap).
        if ( empty( self::$platforms ) && defined( 'YAYMAIL_IS_DEVELOPMENT' ) && YAYMAIL_IS_DEVELOPMENT && function_exists( '_doing_it_wrong' ) ) {
            _doing_it_wrong( __METHOD__, 'PlatformRegistry read before any platform was registered.', '4.4.2' );
        }

        return new YaymailPlatform();
    }

    /**
     * Whether the Yay WP Email Customizer plugin hosts the shared core on disk.
     *
     * True when YayMail lite is active alongside the Yay WP Pro plugin; in that
     * setup the WP plugin provides the authoritative core files, so paths/URLs
     * resolve to it. Preserves the exact detection formerly in
     * Helpers::is_yaymail_lite_and_yay_wp_pro_active().
     *
     * @return bool
     */
    private static function wp_hosts_core(): bool {
        // Set by this addon's bootstrap: true whenever the active yaymail-core
        // variant (free "yaymail", or paid "yaymail-pro" / "email-customizer-
        // for-woocommerce") is <= 4.4.2, i.e. too old to provide the shared
        // src/ files this addon's own classes now depend on. Takes priority
        // over the legacy free-lite-only check below.
        if ( defined( 'YAYWP_HOSTS_CORE' ) ) {
            return YAYWP_HOSTS_CORE;
        }

        $is_yaymail_lite_active = function_exists( 'is_plugin_active' ) && is_plugin_active( 'yaymail/yaymail.php' );
        $is_yay_wp_pro_active   = function_exists( 'YayMail\wp_mail_init' )
            && class_exists( 'YaymailWpPluginAdapter', false )
            && method_exists( 'YaymailWpPluginAdapter', 'is_licensed' );

        return $is_yaymail_lite_active && $is_yay_wp_pro_active;
    }
}
