<?php

namespace YayMail\Abstracts;

use YayMail\Utils\TemplateHelpers;

/**
 * Base class for code-defined email templates in Template Library.
 */
abstract class BaseTemplate {
    /**
     * Unique identifier for this library template (slug style).
     *
     * @var string
     */
    protected $id = '';

    /**
     * YayMail email/template name that this library template applies to.
     *
     * Example: 'new_order', 'customer_completed_order', etc.
     *
     * @var string
     */
    protected $email_type;

    /**
     * Human-readable template name shown in the UI.
     *
     * @var string
     */
    protected $name;

    /**
     * Short description for the template card.
     *
     * @var string
     */
    protected $description;

    /**
     * Categories for filtering templates in the UI.
     *
     * @var array
     */
    protected $categories = [];

    /**
     * Optional position for ordering in the UI.
     *
     * @var int
     */
    protected $position = 10;

    /**
     * Whether this template is currently available.
     *
     * @var bool
     */
    protected $available = true;

    /**
     * Access level for this template.
     *
     * Supported values:
     * - 'free'        => always available
     * - 'pro'         => available only when a Pro variant of YayMail is installed
     * - 'coming_soon' => not yet available
     *
     * @var string
     */
    protected $access = 'free';

    /**
     * Elements for the template.
     *
     * @var array
     */
    protected $elements = [];

    /**
     * Template-level email settings applied when using this template.
     *
     * @var array
     */
    protected $template_settings = [];

    /**
     * Last updated date of the template.
     *
     * @var string
     */
    protected $modified_date = '';

    public function __construct() {
    }

    public function get_email_type() {
        return $this->email_type;
    }

    public function get_id() {
        return $this->id;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_description() {
        return $this->description;
    }

    public function get_categories() {
        return $this->categories;
    }

    public function get_available() {
        return $this->available;
    }

    public function get_position() {
        return $this->position;
    }

    public function get_elements() {
        return $this->elements;
    }

    public function get_access() {
        return $this->access;
    }

    public function get_email_settings() {
        return $this->template_settings;
    }

    public function get_modified_date() {
        return $this->modified_date;
    }

    /**
     * Get full template payload including elements.
     *
     * @return array
     */
    public function get_template_data() {
        // Always recompute availability before exposing data to REST.
        $this->available = $this->determine_availability();

        $elements = TemplateHelpers::normalize_elements_parent_ids( $this->get_elements() );

        $data = [
            'id'             => $this->get_id(),
            'email_type'     => $this->get_email_type(),
            'name'           => $this->get_name(),
            'description'    => $this->get_description(),
            'categories'     => $this->get_categories(),
            'position'       => $this->get_position(),
            'modified_date'  => $this->get_modified_date(),
            'available'      => $this->get_available(),
            'access'         => $this->get_access(),
            'elements'       => $elements,
            'email_settings' => $this->get_email_settings(),
        ];

        return $data;
    }

    /**
     * Determine whether this template is available
     *
     * @return bool
     */
    protected function determine_availability() {
        $access = $this->get_access();

        if ( 'coming_soon' === $access ) {
            return false;
        }

        if ( 'pro' === $access ) {
            return $this->is_pro_version_installed();
        }

        return true;
    }

    /**
     * Check whether a Pro variant of YayMail is installed.
     *
     * @return bool
     */
    protected function is_pro_version_installed() {
        static $is_pro_cache = null;

        if ( null !== $is_pro_cache ) {
            return (bool) $is_pro_cache;
        }

        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $all_plugins = function_exists( 'get_plugins' ) ? get_plugins() : [];

        if ( empty( $all_plugins ) || ! is_array( $all_plugins ) ) {
            $is_pro_cache = false;
            return false;
        }

        $is_pro_cache = array_key_exists( 'yaymail-pro/yaymail.php', $all_plugins )
            || array_key_exists( 'email-customizer-for-woocommerce/yaymail.php', $all_plugins );

        return (bool) $is_pro_cache;
    }
}