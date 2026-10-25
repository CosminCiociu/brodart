<?php

namespace YayMail\Engine\Backend;

use YayMail\Controllers\RevisionController;
use YayMail\TemplatePatterns\PatternService;
use YayMail\Models\MigrationModel;
use YayMail\SupportedPlugins;
use YayMail\TemplatePatterns\SectionTemplateService;
use YayMail\Utils\SingletonTrait;
use YayMail\Utils\YayMailViteApp;
use YayMail\Utils\Localize;
use YayMail\Utils\Helpers;
/**
 *  YayMail Page
 */
class SettingsPage {
    use SingletonTrait;

    private $yaymail_hook_surfix = null;

    private $yay_wp_hook_surfix = null;

    /**
     * Constructor
     */
    protected function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks when class init
     */
    protected function init_hooks() {
        // Submenu is registered by YayCommerce AdminShell; keep each platform's
        // settings-screen hook id (sourced from the registered platform) for the
        // enqueue/screen checks below.
        $yaymail_platform = \YayMail\Platform\PlatformRegistry::get( 'yaymail' );
        if ( $yaymail_platform ) {
            $this->yaymail_hook_surfix = $yaymail_platform->hook_suffix();
        }
        $wp_platform = \YayMail\Platform\PlatformRegistry::get( 'yaymagic-email-builder' );
        if ( $wp_platform ) {
            $this->yay_wp_hook_surfix = $wp_platform->hook_suffix();
        }
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 30 );
        add_filter( 'mce_external_plugins', [ $this, 'register_wp_editor_plugins_script' ] );

        // Add Column YayMail Customizer on Setting email of WooCommerce (only when WC is active)
        if ( yaymail_is_wc_installed() ) {
            add_filter( 'woocommerce_email_setting_columns', [ $this, 'woocommerce_email_setting_columns' ] );
            add_action( 'woocommerce_email_setting_column_yaymail_customizer', [ $this, 'woocommerce_email_setting_column_yaymail_customizer' ] );
        }

        // Fix conflict plugins styles in Settings page
        add_action( 'admin_enqueue_scripts', [ $this, 'fix_conflict_plugins_styles' ], PHP_INT_MAX );
    }

    /**
     * Enqueue scripts using in settings page
     */
    public function register_wp_editor_plugins_script( $plugin_array ) {
        // mce_external_plugins also runs on the frontend (e.g. Avada Live Builder
        // calling _WP_Editors::editor_settings() from wp_footer). get_current_screen()
        // is admin-only and is not defined there.
        if ( ! function_exists( 'get_current_screen' ) ) {
            return $plugin_array;
        }

        $plugin_url       = YAYMAIL_PLUGIN_URL;
        $screen           = get_current_screen();
        $yaymail_platform = \YayMail\Platform\PlatformRegistry::get( 'yaymail' );
        if ( ( ! $yaymail_platform || ! $screen || $screen->id !== $yaymail_platform->hook_suffix() ) && defined( 'YAYWP_PLUGIN_URL' ) ) {
            $plugin_url = YAYWP_PLUGIN_URL;
        }

        $plugin_array['advlist']        = $plugin_url . 'assets/scripts/wp-editor-plugins/advlist/plugin.min.js';
        $plugin_array['autolink']       = $plugin_url . 'assets/scripts/wp-editor-plugins/autolink/plugin.min.js';
        $plugin_array['searchreplace']  = $plugin_url . 'assets/scripts/wp-editor-plugins/searchreplace/plugin.min.js';
        $plugin_array['code']           = $plugin_url . 'assets/scripts/wp-editor-plugins/code/plugin.min.js';
        $plugin_array['visualblocks']   = $plugin_url . 'assets/scripts/wp-editor-plugins/visualblocks/plugin.min.js';
        $plugin_array['table']          = $plugin_url . 'assets/scripts/wp-editor-plugins/table/plugin.min.js';
        $plugin_array['insertdatetime'] = $plugin_url . 'assets/scripts/wp-editor-plugins/insertdatetime/plugin.min.js';

        return $plugin_array;
    }

    public function admin_enqueue_scripts( $hook_suffix ) {
        if ( in_array( $hook_suffix, [ $this->yaymail_hook_surfix, $this->yay_wp_hook_surfix ], true ) ) {
            do_action( 'yaymail_before_enqueue_settings_page_scripts' );
            // Enqueue script here
            YayMailViteApp::get_instance()->enqueue_entry( 'yaymail-main.tsx', [ 'react', 'react-dom', 'wp-i18n' ] );
            add_action( 'yaymail_after_enqueue_scripts', [ $this, 'localize_js_vars' ] );

            wp_enqueue_media();
            wp_enqueue_editor();
            wp_enqueue_script( 'accounting' );
            do_action( 'yaymail_after_enqueue_settings_page_scripts' );
        }
    }

    /**
     * Register localize data
     */
    public function localize_js_vars() {
        $screen           = get_current_screen();
        $current_platform = \YayMail\Platform\PlatformRegistry::from_screen( $screen );
        if ( $screen->id === $this->yaymail_hook_surfix ) {
            $_wc_emails = wc()->mailer()->emails;

            // override template base for wc emails
            foreach ( $_wc_emails as $email ) {
                $reflector            = new \ReflectionClass( $email );
                $email->template_base = $reflector->getFileName();
                unset( $reflector );
            }

            $_wc_emails = array_map(
                function( $email ) {
                    return (object) [
                        'id'               => $email->id,
                        'title'            => $email->title,
                        'enabled'          => $email->enabled,
                        'description'      => $email->description,
                        'template_base'    => $email->template_base,
                        'recipient'        => $email->recipient,
                        'content_type'     => $email->get_content_type(),
                        'setting_page_url' => Helpers::yaymail_get_url_email_setting_page( $email->id ),
                    ];
                },
                $_wc_emails
            );
        } else {
            $_wc_emails = [];
        }//end if

        $plugin_work = Helpers::get_plugin_work_info();

        wp_localize_script(
            'module/yaymail/yaymail-main.tsx',
            'yaymailData',
            array_merge(
                [
                    'is_rtl'                         => is_rtl(),
                    'urls'                           => [
                        'vite_dynamic_base'      => YAYMAIL_PLUGIN_URL . 'assets/dist/yaymail/',
                        // The screen's own plugin: YAYMAIL_PLUGIN_URL is whichever YayMail core
                        // defined it first, which lacks this bundle's images when it's Pro.
                        'asset_url'              => ( $current_platform ? $current_platform->plugin_url() : YAYMAIL_PLUGIN_URL ) . 'assets/images/',
                        'home_url'               => home_url(),
                        'wc_placeholder_img_src' => function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src() : '',
                    ],
                    'admin_ajax'                     => [
                        'url'   => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce( 'yaymail_frontend_nonce' ),
                    ],
                    'rest_path'                      => [
                        'root'  => esc_url_raw( rest_url() ),
                        'base'  => YAYMAIL_REST_NAMESPACE,
                        'nonce' => wp_create_nonce( 'wp_rest' ),
                    ],
                    'shared'                         => [
                        'util_functions'   => [],
                        'stores'           => [],
                        'core_components'  => [],
                        'activated_addons' => Localize::get_activated_addons(),
                    ],
                    'list_orders'                    => Localize::get_list_orders(),
                    'i18n'                           => apply_filters(
                        'yaymail_translations',
                        []
                    ),
                    'builder'                        => [
                        'font_families'          => [
                            '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                            'Georgia, serif',
                            '"Times New Roman", Times, Serif',
                            'Arial, Helvetica, sans-serif',
                            '"Arial Black", Gadget, sans-serif',
                            '"Comic Sans MS", cursive, sans-serif',
                            'Tahoma, Geneva, sans-serif',
                            '"Trebuchet MS", Helvetica, sans-serif',
                            'Verdana, Geneva, sans-serif',
                            '"Courier New", Courier, monospace',
                            '"Lucida Console", Monaco, monospace',
                            '"Outfit", "DM Sans", sans-serif',
                            '"Kodchasan", system-ui, sans-serif',
                            '"Fraunces", serif',
                            '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                        ],
                        'social_icons'           => Localize::get_social_icons_data(),
                        'revision_limit'         => RevisionController::YAYMAIL_TEMPLATE_REVISION_LIMIT,
                        // Two compiled JS bundles still read global_headers_footers as a single
                        // legacy { global_header_elements, global_footer_elements } object rather
                        // than the per-language array Localize::get_global_headers_footers()
                        // returns: (1) the "yaymagic-email-builder" (WP core mail) bundle -- confirmed
                        // byte-identical to the free "yaymail" lite bundle, it never received the
                        // multi-language upgrade -- and (2) the "yaymail" lite bundle itself when
                        // YAYMAIL_LITE_LEGACY_GHF_SHAPE is set. The paid yaymail-pro /
                        // email-customizer-for-woocommerce variants already expect the array.
                        // Feeding the array shape to a legacy bundle leaves globalHeaderElements/
                        // globalFooterElements undefined and crashes the customizer with "Cannot
                        // read properties of undefined (reading 'find')"; feeding the legacy shape
                        // to an array-expecting bundle breaks it with "global_headers_footers.find
                        // is not a function". Keep both conditions -- don't collapse into version
                        // checks, they gate an unrelated concern (see YAYWP_HOSTS_CORE above).
                        'global_headers_footers' => ( $current_platform && (
                            'yaymagic-email-builder' === $current_platform->key()
                            || ( 'yaymail' === $current_platform->key() && defined( 'YAYMAIL_LITE_LEGACY_GHF_SHAPE' ) && YAYMAIL_LITE_LEGACY_GHF_SHAPE )
                        ) )
                            ? \YayMail\Models\TemplateModel::get_global_header_and_footer( '', $current_platform->ghf_option_key() )
                            : Localize::get_global_headers_footers(
                                $current_platform ? $current_platform->ghf_option_key() : 'yaymail_global_header_footer'
                            ),
                        'section_templates'      => SectionTemplateService::get_instance()->get_list_data(),
                        'patterns'               => PatternService::get_instance()->get_list_data(),
                    ],
                    'colors'                         => [
                        'default_background_color'         => YAYMAIL_COLOR_BACKGROUND_DEFAULT,
                        'default_text_link_color'          => ( $current_platform && 'yaymagic-email-builder' === $current_platform->key() ) ? YAYMAIL_COLOR_WP_DEFAULT : YAYMAIL_COLOR_WC_DEFAULT,
                        'default_content_background_color' => YAYMAIL_COLOR_CONTENT_BACKGROUND_DEFAULT,
                        'default_content_text_color'       => YAYMAIL_COLOR_CONTENT_TEXT_DEFAULT,
                        'default_title_color'              => ( $current_platform && 'yaymagic-email-builder' === $current_platform->key() ) ? YAYMAIL_COLOR_WP_DEFAULT : YAYMAIL_COLOR_TITLE_DEFAULT,
                    ],
                    'smtp'                           => [
                        'link_detail' => self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=yaysmtp&section=description&TB_iframe=true&width=600&height=800' ),
                        'setting'     => admin_url( 'admin.php?page=yaysmtp' ),
                        'is_active'   => Helpers::check_plugin_installed( 'yaysmtp/yay-smtp.php' ) || Helpers::check_plugin_installed( 'yaysmtp-pro/yay-smtp.php' ),
                    ],
                    'translate_integrations'         => Localize::get_translate_integrations(),
                    'reviewed'                       => boolval( get_option( 'yaymail_review' ) ),
                    'ghf_tour'                       => get_option( 'yaymail_ghf_tour', 'initial' ),
                    'test_email_address'             => get_option( 'yaymail_default_email_test', wp_get_current_user()->user_email ),
                    'site_title'                     => get_option( 'blogname' ),
                    // TODO: legacy: use get_user_meta
                    'wc_emails'                      => $_wc_emails,
                    'is_critical_migration_required' => MigrationModel::get_instance()->check_if_critical_migration_required(),
                    'supported_plugins'              => SupportedPlugins::get_instance()->get_slug_name_supported_plugins(),
                    'show_multi_select_notice'       => get_option( 'yaymail_show_multi_select_notice', 'yes' ),
                    'viewed_new_elements'            => ! empty( get_option( 'yaymail_viewed_new_elements', [] ) ) ? get_option( 'yaymail_viewed_new_elements' ) : [],
                    'ghf_disallowed_element_types'   => yaymail_get_ghf_disallowed_element_types(),
                    'platform'                       => $current_platform ? $current_platform->key() : 'yaymail',
                    'version'                        => yaymail_version(),
                    'woocommerce_email_styles'       => $this->get_scoped_woocommerce_email_styles(),
                ],
                apply_filters( 'yaymail_additional_localized_variables', [] )
            )
        );

        // Printed after the localized yaymailData, before the bundle executes.
        wp_add_inline_script( 'module/yaymail/yaymail-main.tsx', $this->get_section_template_patterns_rehydrate_script(), 'before' );
    }

    /**
     * builder.section_templates[].patterns is localized as pattern types only (see
     * BaseSectionTemplate::get_raw_data()). Every compiled bundle -- including the
     * legacy yaymagic-email-builder and Pro ones -- still expects pattern objects
     * there, so resolve the types against builder.patterns in place. Objects are
     * shared by reference, so no data is duplicated in the page.
     *
     * @return string
     */
    private function get_section_template_patterns_rehydrate_script() {
        return '(function(){'
            . 'var b=window.yaymailData&&window.yaymailData.builder;'
            . 'if(!b||!Array.isArray(b.patterns)||!Array.isArray(b.section_templates)){return;}'
            . 'var byType={};'
            . 'b.patterns.forEach(function(p){byType[p.type]=p;});'
            . 'b.section_templates.forEach(function(s){'
            . 's.patterns=(s.patterns||[]).map(function(t){return typeof t==="string"?byType[t]:t;}).filter(Boolean);'
            . '});'
            . '})();';
    }

    private function get_scoped_woocommerce_email_styles() {
        if ( ! function_exists( 'WC' ) ) {
            return '';
        }

        $raw_styles = wp_unslash( wc_get_template_html( 'emails/email-styles.php' ) );
        return Helpers::scope_css_block( $raw_styles, '.yaymail-customizer-template-section' );
    }

    /**
     * Add new column to action column
     */
    public function woocommerce_email_setting_columns( $array ) {
        if ( isset( $array['actions'] ) ) {
            unset( $array['actions'] );
            return array_merge(
                $array,
                [
                    'yaymail_customizer' => '',
                    'actions'            => '',
                ]
            );
        }
        return $array;
    }

    /**
     * Add link to setting column
     */
    public function woocommerce_email_setting_column_yaymail_customizer( $email ) {
        $email_id = $email->id;
        if ( 'yith-coupon-email-system' === $email->id ) {
            if ( class_exists( 'YayMailYITHWooCouponEmailSystem\templateDefault\DefaultCouponEmailSystem' ) ) {
                $email_id = 'YWCES_register';
            }
        }

        echo '<td class="wc-email-settings-table-template">
				<a class="button alignright" target="_blank" href="' . esc_attr( admin_url( 'admin.php?page=yaymail-settings#/customizer' ) ) . '?template=' . esc_attr( $email_id ) . '">' . esc_html( __( 'Customize with YayMail', 'yaymail' ) ) . '</a></td>';
    }

    public function fix_conflict_plugins_styles() {
        if ( ! function_exists( 'get_current_screen' ) ) {
            return;
        }
        $screen = get_current_screen();
        if ( in_array( $screen->id, [ $this->yaymail_hook_surfix, $this->yay_wp_hook_surfix ], true ) ) {
            wp_dequeue_style( 'real-media-library-lite-rml' );
            wp_dequeue_script( 'real-media-library-lite-rml' );
            wp_dequeue_style( 'real-media-library-rml' );
            wp_dequeue_script( 'real-media-library-rml' );
            wp_dequeue_style( 'real-category-library-admin' );
        }
    }
}
