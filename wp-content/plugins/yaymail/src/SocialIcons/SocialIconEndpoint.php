<?php

namespace YayMail\SocialIcons;

use YayMail\Utils\SingletonTrait;

/**
 * Serves dynamically tinted social icon PNGs for the Custom theme.
 *
 * Query params:
 * - action: yaymail_social_icon
 * - icon: social slug (whitelist)
 * - color: hex without # (3 or 6 chars)
 * - size: output size in px (optional)
 */
class SocialIconEndpoint {

    use SingletonTrait;

    private const ACTION = 'yaymail_social_icon';

    private const ICONS = [
        'behance',
        'discord',
        'dribble',
        'facebook',
        'github',
        'google',
        'instagram',
        'linkedin',
        'medium',
        'messenger',
        'pinterest',
        'reddit',
        'skype',
        'snapchat',
        'spotify',
        'telegram',
        'tiktok',
        'twitch',
        'twitter',
        'viber',
        'vimeo',
        'website',
        'wechat',
        'whatsapp',
        'youtube',
        'zillow',
    ];

    public function __construct() {
        $action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

        if ( self::ACTION !== $action ) {
            return;
        }

        $this->serve();
    }

    /**
     * Build a public URL for a tinted social icon.
     *
     * @param string $icon Social slug.
     * @param string $color Hex color with or without #.
     * @param int    $size Output size in px.
     */
    public static function get_url( $icon, $color, $size = 64 ) {
        $color = strtolower( ltrim( (string) $color, '#' ) );
        // Extra safety: strip any non-hex leftovers if a bad value slips through.
        $color = preg_replace( '/[^0-9a-f]/', '', $color );
        if ( 3 !== strlen( $color ) && 6 !== strlen( $color ) ) {
            $color = '333333';
        }
        $size = max( 16, min( 256, absint( $size ) ) );

        return add_query_arg(
            [
                'action' => self::ACTION,
                'icon'   => sanitize_key( $icon ),
                'color'  => $color,
                'size'   => $size,
            ],
            home_url( '/' )
        );
    }

    private function serve() {
        if ( ! function_exists( 'imagecreatefrompng' ) || ! function_exists( 'imagepng' ) ) {
            status_header( 503 );
            exit;
        }

        $icon  = isset( $_GET['icon'] ) ? sanitize_key( wp_unslash( $_GET['icon'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $color = isset( $_GET['color'] ) ? sanitize_text_field( wp_unslash( $_GET['color'] ) ) : '333333'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $size  = isset( $_GET['size'] ) ? absint( wp_unslash( $_GET['size'] ) ) : 64; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

        $color = ltrim( $color, '#' );
        if ( ! in_array( $icon, self::ICONS, true ) || ! preg_match( '/^[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/', $color ) ) {
            status_header( 404 );
            exit;
        }

        if ( 3 === strlen( $color ) ) {
            $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
        }
        $color = strtolower( $color );
        $size  = max( 16, min( 256, $size ) );

        $mask_path = YAYMAIL_PLUGIN_PATH . 'assets/images/social-icons/' . $icon . '/custom.png';
        if ( ! file_exists( $mask_path ) ) {
            status_header( 404 );
            exit;
        }

        $cache_key = md5( $icon . '-' . $color . '-' . $size . '-' . (string) filemtime( $mask_path ) );
        $etag      = '"' . $cache_key . '"';
        if ( isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) && trim( wp_unslash( $_SERVER['HTTP_IF_NONE_MATCH'] ) ) === $etag ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
            status_header( 304 );
            exit;
        }

        $this->send_image_headers( $etag );

        // Disk cache: (icon, color, size, mask-mtime) always renders identical
        // bytes, so once generated once on this server, skip GD entirely and
        // stream the cached file straight from disk.
        $cache_path = SocialIconImageCache::get_path( $cache_key );
        if ( $cache_path && file_exists( $cache_path ) ) {
            // Not user-controlled: uploads/<cache dir>/<md5>.png, hashed from the whitelisted icon, hex-validated color and clamped size.
            // nosemgrep: audit.php.lang.security.file.read-write-delete
            readfile( $cache_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
            exit;
        }

        $rgb = [
            hexdec( substr( $color, 0, 2 ) ),
            hexdec( substr( $color, 2, 2 ) ),
            hexdec( substr( $color, 4, 2 ) ),
        ];

        $mask = imagecreatefrompng( $mask_path );
        if ( false === $mask ) {
            status_header( 500 );
            exit;
        }

        imagealphablending( $mask, false );
        imagesavealpha( $mask, true );

        $src_w = imagesx( $mask );
        $src_h = imagesy( $mask );

        // Source masks are solid white icons whose shape lives entirely in the
        // alpha channel, so a single native colorize call recolors every opaque
        // pixel to the target color (255 + offset = target) while leaving the
        // alpha untouched — equivalent to, but far faster than, a per-pixel loop.
        imagefilter( $mask, IMG_FILTER_COLORIZE, $rgb[0] - 255, $rgb[1] - 255, $rgb[2] - 255 );

        $out = imagecreatetruecolor( $size, $size );
        imagealphablending( $out, false );
        imagesavealpha( $out, true );
        $transparent = imagecolorallocatealpha( $out, 0, 0, 0, 127 );
        imagefilledrectangle( $out, 0, 0, $size, $size, $transparent );

        imagecopyresampled( $out, $mask, 0, 0, 0, 0, $size, $size, $src_w, $src_h );

        imagedestroy( $mask );

        // Capture the PNG bytes once so they can both be cached to disk and
        // streamed back, instead of calling imagepng() twice.
        ob_start();
        imagepng( $out );
        $png_data = ob_get_clean();
        imagedestroy( $out );

        if ( $cache_path ) {
            SocialIconImageCache::write( $cache_path, $png_data );
        }

        echo $png_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        exit;
    }

    private function send_image_headers( $etag ) {
        header( 'Content-Type: image/png' );
        header( 'Cache-Control: public, max-age=31536000, immutable' );
        header( 'ETag: ' . $etag );
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + YEAR_IN_SECONDS ) . ' GMT' );
    }
}
