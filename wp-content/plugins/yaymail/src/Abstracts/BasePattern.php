<?php
namespace YayMail\Abstracts;

/**
 * BasePattern Class
 */
abstract class BasePattern {
    protected $id     = null;
    public const TYPE = '';

    protected $section = null;

    protected $available   = true;
    protected $position    = 10;
    protected $name        = '';
    protected $elements    = [];

    public function get_data() {
        return [
            'id'          => $this->id,
            'type'        => static::TYPE,
            'section'     => $this->section,
            'available'   => $this->available,
            'position'    => $this->position,
            'elements'    => $this->elements,
            'name'        => $this->name,
        ];
    }

    public function get_type() {
        return static::TYPE;
    }

    /**
     * @return string
     */
    public function get_section() {
        return $this->section;
    }

    /**
     * Element keys only the sidebar element registry reads (builder elements list).
     * Elements placed from a pattern never use them, yet the same "Third party
     * elements" SVG icon was repeated on every nested element, ~150KB of the inline
     * yaymailData script. `name` is kept: the element editor and history labels use it.
     */
    private const SIDEBAR_ONLY_ELEMENT_KEYS = [ 'icon', 'group' ];

    public function get_raw_data() {
        $data             = $this->get_data();
        $data['elements'] = self::strip_sidebar_only_keys( $data['elements'] );
        return $data;
    }

    /**
     * Recursively drop SIDEBAR_ONLY_ELEMENT_KEYS from elements and their children.
     *
     * @param array $elements Pattern elements.
     * @return array
     */
    private static function strip_sidebar_only_keys( $elements ) {
        if ( ! is_array( $elements ) ) {
            return $elements;
        }

        return array_map(
            function( $element ) {
                if ( ! is_array( $element ) ) {
                    return $element;
                }
                $element = array_diff_key( $element, array_flip( self::SIDEBAR_ONLY_ELEMENT_KEYS ) );
                if ( ! empty( $element['children'] ) ) {
                    $element['children'] = self::strip_sidebar_only_keys( $element['children'] );
                }
                return $element;
            },
            $elements
        );
    }

}
