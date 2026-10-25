<?php

namespace YayMail\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Unserialize backup/import values without instantiating gadgets.
 * stdClass is allowed (YayMail exports); any other class is rejected.
 */
class SafeUnserializer {

    /**
     * Max recursion depth.
     */
    const MAX_DEPTH = 128;

    /**
     * Unserialize with allowed_classes => false.
     *
     * @param mixed $value Raw value, usually a serialized string.
     * @return mixed
     */
    public static function decode( $value ) {
        if ( ! is_string( $value ) ) {
            return $value;
        }

        if ( ! function_exists( 'is_serialized' ) || ! is_serialized( $value ) ) {
            return $value;
        }

        $unserialized = @unserialize( $value, [ 'allowed_classes' => false ] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.Security.SafeUnserialize.unserialize_unserialize -- no class is instantiated, and callers reject leftover objects.

        if ( false === $unserialized && 'b:0;' !== $value ) {
            return $value;
        }

        return $unserialized;
    }

    /**
     * Unserialize a stored backup option. Backup rows from $wpdb are stdClass,
     * so stdClass must stay a real object for restore to read its properties.
     *
     * @param mixed $value Raw value, usually a serialized string.
     * @return mixed
     */
    public static function decode_backup( $value ) {
        if ( ! is_string( $value ) || ! is_serialized( $value ) ) {
            return $value;
        }

        $unserialized = @unserialize( $value, [ 'allowed_classes' => [ 'stdClass' ] ] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.Security.SafeUnserialize.unserialize_unserialize -- only stdClass may be instantiated.

        if ( false === $unserialized && 'b:0;' !== $value ) {
            return $value;
        }

        return $unserialized;
    }

    /**
     * Unserialize a value that is about to be written to the database.
     *
     * @param mixed $value Raw value, usually a serialized string.
     * @return mixed
     * @throws \Exception When the payload carries a non-stdClass object at any depth.
     */
    public static function decode_strict( $value ) {
        $decoded = self::decode( $value );

        if ( self::contains_object( $decoded ) ) {
            throw new \Exception( 'Serialized PHP objects are not allowed in imported data.' );
        }

        return $decoded;
    }

    /**
     * True if a non-stdClass object exists at any depth.
     *
     * @param mixed $value Decoded value.
     * @param int   $depth Current recursion depth.
     * @return bool
     */
    public static function contains_object( $value, $depth = 0 ) {
        if ( $depth >= self::MAX_DEPTH ) {
            return true;
        }

        if ( is_object( $value ) ) {
            if ( ! self::is_stdclass( $value ) ) {
                return true;
            }

            $value = self::object_vars( $value );
        }

        if ( ! is_array( $value ) ) {
            return false;
        }

        foreach ( $value as $item ) {
            if ( self::contains_object( $item, $depth + 1 ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Real stdClass or its incomplete stand-in.
     *
     * @param object $value Decoded object.
     * @return bool
     */
    private static function is_stdclass( $value ) {
        if ( $value instanceof \stdClass ) {
            return true;
        }

        if ( ! $value instanceof \__PHP_Incomplete_Class ) {
            return false;
        }

        $vars = self::object_vars( $value );

        return isset( $vars['__PHP_Incomplete_Class_Name'] ) && 'stdClass' === $vars['__PHP_Incomplete_Class_Name'];
    }

    /**
     * Properties of an object, including incomplete objects.
     *
     * @param object $value Object to inspect.
     * @return array
     */
    private static function object_vars( $value ) {
        if ( function_exists( 'get_mangled_object_vars' ) ) {
            return get_mangled_object_vars( $value );
        }

        return get_object_vars( $value );
    }

    /**
     * True if the serialized value contains a non-stdClass object.
     *
     * @param mixed $value Raw value, usually a serialized string.
     * @return bool
     */
    public static function has_object_payload( $value ) {
        return self::contains_object( self::decode( $value ) );
    }
}
