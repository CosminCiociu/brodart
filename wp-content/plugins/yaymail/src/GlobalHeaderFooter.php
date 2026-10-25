<?php

namespace YayMail;

use YayMail\Models\TemplateModel;
use YayMail\Platform\PlatformRegistry;
use YayMail\Platform\YaymailPlatform;
use YayMail\Utils\Helpers;

/**
 * GlobalHeaderFooter Class
 *
 * This is an YayMail element, not an email template. But its customizer page (for editing, saving, etc...) shares the same logic as email template customizer.
 *
 * @since 4.1.0
 * @method static GlobalHeaderFooter get_instance()
 */
class GlobalHeaderFooter {

    /**
     * Get global header and footer elements
     * Each result is an array of elements
     * If empty, it means the global header or footer is hidden or not enabled
     *
     * @param string|YayMailTemplate $template
     *
     * @return array
     */
    public static function get_elements( $template ) {

        $fallback_result = [
            'global_header_elements' => [],
            'global_footer_elements' => [],
        ];

        $template_name = $template->get_name();

        if ( ! self::is_global_header_footer_enabled( $template_name ) ) {
            return $fallback_result;
        }

        $template = self::get_template_from_input( $template );

        if ( empty( $template ) ) {
            return $fallback_result;
        }

        $ghf_template_name = self::resolve_platform( $template_name )->ghf_option_key();

        $global_header_footer_elements = TemplateModel::get_instance()->get_global_header_and_footer( $template->get_language(), $ghf_template_name );

        if ( empty( $global_header_footer_elements ) ) {
            return $fallback_result;
        }

        if ( self::is_global_header_hidden( $template ) ) {
            $global_header_footer_elements['global_header_elements'] = [];
        }

        if ( self::is_global_footer_hidden( $template ) ) {
            $global_header_footer_elements['global_footer_elements'] = [];
        }

        return $global_header_footer_elements;
    }

    /**
     * Resolve the platform that owns a given global-header-footer template name.
     *
     * Template naming is the only signal available at this call depth ('wp-core*'
     * belongs to the yaymagic-email-builder product, everything else to yaymail), so this
     * is the single place that maps it to a registered platform instead of each
     * caller re-deriving option keys inline.
     *
     * @param string $template_name
     *
     * @return \YayMail\Platform\PlatformInterface
     */
    private static function resolve_platform( $template_name ) {
        $platform_key = str_starts_with( $template_name, 'wp-core' ) ? 'yaymagic-email-builder' : 'yaymail';

        return PlatformRegistry::get( $platform_key ) ?? PlatformRegistry::get( 'yaymail' ) ?? new YaymailPlatform();
    }

    /**
     * Get template from input
     *
     * @param string|YayMailTemplate $template
     *
     * @return YayMailTemplate|null
     */
    private static function get_template_from_input( $template ) {

        if ( is_string( $template ) ) {
            $template = new YayMailTemplate( $template );
        }

        if ( ! ( $template instanceof YayMailTemplate ) ) {
            return null;
        }

        if ( empty( $template ) ) {
            return null;
        }

        return $template;
    }

    /**
     * Check if global header is hidden
     *
     * @param string|YayMailTemplate $template
     *
     * @return bool
     */
    public static function is_global_header_hidden( $template ) {
        $template = self::get_template_from_input( $template );

        if ( empty( $template ) ) {
            return true;
        }

        $global_header_settings = $template->get_global_header_settings();
        $hidden_value           = $global_header_settings['hidden'];

        // validate boolean
        return filter_var( $hidden_value, FILTER_VALIDATE_BOOLEAN ); // phpcs:ignore
    }

    /**
     * Check if global footer is hidden
     *
     * @param string|YayMailTemplate $template
     *
     * @return bool
     */
    public static function is_global_footer_hidden( $template ) {
        $template = self::get_template_from_input( $template );

        if ( empty( $template ) ) {
            return true;
        }

        $global_footer_settings = $template->get_global_footer_settings();
        $hidden_value           = $global_footer_settings['hidden'];

        // validate boolean
        return filter_var( $hidden_value, FILTER_VALIDATE_BOOLEAN ); // phpcs:ignore
    }

    /**
     * Get global header override heading content
     *
     * @param string|YayMailTemplate $template
     *
     * @return string|null
     */
    public static function get_global_header_override_heading_content( $template ) {

        $template_name = $template->get_name();
        if ( ! self::is_global_header_footer_enabled( $template_name ) ) {
            return null;
        }

        $template = self::get_template_from_input( $template );

        if ( empty( $template ) ) {
            return null;
        }

        $global_header_settings = $template->get_global_header_settings();

        if ( ! Helpers::is_true( $global_header_settings['content_override'] ) ) {
            return null;
        }

        $default_heading = self::resolve_platform( $template_name )->is_woo_product()
            ? YayMailTemplate::DEFAULT_DATA['global_header_settings']['heading_content']
            : '<h1 style="font-size: 30px; font-weight: 300; line-height: normal; margin: 0px; color: inherit;">[yaymail_site_name]</h1>';
        return $global_header_settings['heading_content'] ?? apply_filters( 'yaymail_default_global_header_override_content', $default_heading );
    }

    /**
     * Get global footer override content
     *
     * @param string|YayMailTemplate $template
     *
     * @return string|null
     */
    public static function get_global_footer_override_content( $template ) {

        $template_name = $template->get_name();
        if ( ! self::is_global_header_footer_enabled( $template_name ) ) {
            return null;
        }

        $template = self::get_template_from_input( $template );

        if ( empty( $template ) ) {
            return null;
        }

        $global_footer_settings = $template->get_global_footer_settings();

        if ( ! Helpers::is_true( $global_footer_settings['content_override'] ) ) {
            return null;
        }

        $default_footer = self::resolve_platform( $template_name )->is_woo_product()
            ? YayMailTemplate::DEFAULT_DATA['global_footer_settings']['footer_content']
            : '<p style="font-size: 14px;margin: 0px 0px 16px; text-align: center;">[yaymail_site_name]</p>';
        return $global_footer_settings['footer_content'] ?? apply_filters( 'yaymail_default_global_footer_override_content', $default_footer );
    }

    /**
     * Check if element is in global header
     *
     * @param array                  $element
     * @param string|YayMailTemplate $template
     *
     * @return bool
     */
    public static function is_element_in_global_header( $element, $template ) {
        $elements = self::get_elements( $template );

        if ( empty( $elements['global_header_elements'] ) ) {
            return false;
        }

        return count(
            array_filter(
                $elements['global_header_elements'],
                function( $el ) use ( $element ) {
                    return $el['id'] === $element['id'];
                }
            )
        ) > 0;
    }

    /**
     * Check if element is in global footer
     *
     * @param array                  $element
     * @param string|YayMailTemplate $template
     *
     * @return bool
     */
    public static function is_element_in_global_footer( $element, $template ) {
        $elements = self::get_elements( $template );

        if ( empty( $elements['global_footer_elements'] ) ) {
            return false;
        }

        return count(
            array_filter(
                $elements['global_footer_elements'],
                function( $el ) use ( $element ) {
                    return $el['id'] === $element['id'];
                }
            )
        ) > 0;
    }

    public static function is_global_header_footer_enabled( $template_name = '' ) {
        if ( empty( $template_name ) ) {
            return false;
        }

        $key = self::resolve_platform( $template_name )->ghf_option_key( 'enabled' );

        return (bool) ( yaymail_settings()[ $key ] ?? false );
    }
}
