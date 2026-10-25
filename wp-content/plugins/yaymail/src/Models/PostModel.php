<?php

namespace YayMail\Models;

use YayMail\Utils\SingletonTrait;

/**
 * Post Model
 *
 * @method static PostModel get_instance()
 */
class PostModel {

    use SingletonTrait;

    const DEFAULT_LIMIT = 4;

    const COMMON_WP_QUERY_ARGUMENTS = [
        'post_type'   => 'post',
        'post_status' => 'publish',
    ];

    /**
     * Retrieves terms (categories, tags, or posts) for the entities selector.
     *
     * @param array $params Query parameters.
     * @param array $field_mapping Field mapping for term IDs.
     * @return array
     */
    public function get_terms( $params, $field_mapping = [
        'id'   => 'id',
        'name' => 'name',
    ] ) {
        $page_data = $this->get_terms_page(
            isset( $params['term_type'] ) ? $params['term_type'] : '',
            $params['search_string'] ?? '',
            $params['page_num'] ?? 1,
            $params['page_size'] ?? 20
        );

        $result = [
            'list'      => array_map(
                function( $item ) use ( $field_mapping ) {
                    $id_field   = $field_mapping['id'] ?? 'id';
                    $name_field = $field_mapping['name'] ?? 'name';
                    return [
                        'id'   => strval( isset( $item->{$id_field} ) ? $item->{$id_field} : $item->id ),
                        'name' => isset( $item->{$name_field} ) ? $item->{$name_field} : $item->name,
                    ];
                },
                $page_data['list']
            ),
            'next_page' => $page_data['next_page'],
        ];

        return $result;
    }

    /**
     * Retrieves featured posts based on the provided parameters.
     *
     * @param array $params Query parameters.
     * @return array
     */
    public function get_featured_posts( $params ) {
        $post_type = isset( $params['post_type'] ) ? $params['post_type'] : 'newest';
        unset( $params['post_type'] );

        switch ( $post_type ) {
            case 'newest':
                $posts = $this->get_newest_posts( $params );
                break;
            case 'category_selections':
                $posts = $this->get_by_categories( $params );
                break;
            case 'tag_selections':
                $posts = $this->get_by_tags( $params );
                break;
            case 'post_selections':
                $posts = $this->get_by_post_ids( $params );
                break;
            default:
                $posts = [];
                break;
        }

        $posts_response = array_map( [ $this, 'get_post_response' ], $posts );

        $result = [];

        foreach ( $posts_response as $post_response ) {
            if ( ! empty( $post_response ) ) {
                $result[] = $post_response;
            }
        }

        return $result;
    }

    /**
     * Maps a WP_Post to API response format.
     *
     * @param \WP_Post $post Post object.
     * @return array
     */
    public function get_post_response( $post ) {
        if ( ! $post instanceof \WP_Post ) {
            return [];
        }

        $thumbnail_src = get_the_post_thumbnail_url( $post, 'medium' );
        $excerpt       = has_excerpt( $post )
            ? $post->post_excerpt
            : wp_trim_words( wp_strip_all_tags( $post->post_content ), 55, '...' );

        return [
            'id'             => $post->ID,
            'title'          => get_the_title( $post ),
            'excerpt'        => $excerpt,
            'date'           => date_i18n( get_option( 'date_format' ), strtotime( $post->post_date ) ),
            'date_timestamp' => strtotime( $post->post_date ),
            'author'         => get_the_author_meta( 'display_name', $post->post_author ),
            'permalink'      => get_permalink( $post ),
            'thumbnail_src'  => $thumbnail_src ? $thumbnail_src : '',
        ];
    }

    /**
     * Retrieves newest published posts.
     *
     * @param array $criteria Query criteria.
     * @param array $optional_args Optional WP_Query arguments.
     * @return \WP_Post[]
     */
    private function get_newest_posts( $criteria, $optional_args = [] ) {
        $args = array_merge(
            self::COMMON_WP_QUERY_ARGUMENTS,
            [
                'posts_per_page' => isset( $criteria['number_of_posts'] ) ? (int) $criteria['number_of_posts'] : self::DEFAULT_LIMIT,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]
        );

        if ( isset( $criteria['sorted_by'] ) && 'random' === $criteria['sorted_by'] ) {
            $args['orderby'] = 'rand';
        }

        if ( ! empty( $optional_args ) ) {
            $args = wp_parse_args( $args, $optional_args );
        }

        $query = new \WP_Query( $args );
        return $query->posts;
    }

    /**
     * Retrieves posts by category IDs.
     *
     * @param array $criteria Query criteria.
     * @param array $optional_args Optional WP_Query arguments.
     * @return \WP_Post[]
     */
    private function get_by_categories( $criteria, $optional_args = [] ) {
        if ( empty( $criteria['category_ids'] ) ) {
            return [];
        }

        $args = array_merge(
            self::COMMON_WP_QUERY_ARGUMENTS,
            [
                'posts_per_page' => isset( $criteria['number_of_posts'] ) ? (int) $criteria['number_of_posts'] : self::DEFAULT_LIMIT,
                'category__in'   => array_map( 'intval', $criteria['category_ids'] ),
            ]
        );

        if ( isset( $criteria['sorted_by'] ) && 'random' === $criteria['sorted_by'] ) {
            $args['orderby'] = 'rand';
        }

        if ( ! empty( $optional_args ) ) {
            $args = wp_parse_args( $args, $optional_args );
        }

        $query = new \WP_Query( $args );
        return $query->posts;
    }

    /**
     * Retrieves posts by tag IDs.
     *
     * @param array $criteria Query criteria.
     * @param array $optional_args Optional WP_Query arguments.
     * @return \WP_Post[]
     */
    private function get_by_tags( $criteria, $optional_args = [] ) {
        if ( empty( $criteria['tag_ids'] ) ) {
            return [];
        }

        $args = array_merge(
            self::COMMON_WP_QUERY_ARGUMENTS,
            [
                'posts_per_page' => isset( $criteria['number_of_posts'] ) ? (int) $criteria['number_of_posts'] : self::DEFAULT_LIMIT,
                'tag__in'        => array_map( 'intval', $criteria['tag_ids'] ),
            ]
        );

        if ( isset( $criteria['sorted_by'] ) && 'random' === $criteria['sorted_by'] ) {
            $args['orderby'] = 'rand';
        }

        if ( ! empty( $optional_args ) ) {
            $args = wp_parse_args( $args, $optional_args );
        }

        $query = new \WP_Query( $args );
        return $query->posts;
    }

    /**
     * Retrieves posts by specific post IDs.
     *
     * @param array $criteria Query criteria.
     * @param array $optional_args Optional WP_Query arguments.
     * @return \WP_Post[]
     */
    private function get_by_post_ids( $criteria, $optional_args = [] ) {
        if ( empty( $criteria['post_ids'] ) ) {
            return [];
        }

        $args = array_merge(
            self::COMMON_WP_QUERY_ARGUMENTS,
            [
                'posts_per_page' => -1,
                'post__in'       => array_map( 'intval', $criteria['post_ids'] ),
                'orderby'        => 'post__in',
            ]
        );

        if ( isset( $criteria['sorted_by'] ) && 'random' === $criteria['sorted_by'] ) {
            $args['orderby'] = 'rand';
        }

        if ( ! empty( $optional_args ) ) {
            $args = wp_parse_args( $args, $optional_args );
        }

        $query = new \WP_Query( $args );
        return $query->posts;
    }

    /**
     * Retrieves a paginated list of terms or posts.
     *
     * @param string $taxonomy Taxonomy slug or empty string for posts.
     * @param string $search_string Search string.
     * @param int    $page_num Page number.
     * @param int    $page_size Page size.
     * @return array
     */
    private function get_terms_page( $taxonomy, $search_string = '', $page_num = 1, $page_size = 10 ) {
        $limit  = $page_size + 1;
        $offset = ( $page_num - 1 ) * $page_size;

        if ( empty( $taxonomy ) ) {
            global $wpdb;
            $query = $wpdb->prepare(
                "SELECT id, post_title AS name
                FROM {$wpdb->prefix}posts
                WHERE {$wpdb->prefix}posts.post_type = 'post'
                AND {$wpdb->prefix}posts.post_status = 'publish'
                AND {$wpdb->prefix}posts.post_title LIKE %s
                ORDER BY post_title ASC
                LIMIT %d OFFSET %d",
                "%{$search_string}%",
                $limit,
                $offset
            );
            $list  = $wpdb->get_results( $query ); //phpcs:ignore
        } else {
            $args = [
                'taxonomy'     => $taxonomy,
                'orderby'      => 'name',
                'show_count'   => 0,
                'pad_counts'   => 0,
                'hierarchical' => 1,
                'hide_empty'   => 0,
                'number'       => $limit,
                'offset'       => $offset,
            ];

            if ( ! empty( $search_string ) ) {
                $args['name__like'] = $search_string;
            }

            $list = array_values( \get_categories( $args ) );
        }//end if

        $next_page = count( $list ) > $page_size ? $page_num + 1 : false;

        if ( $next_page ) {
            array_pop( $list );
        }

        return [
            'list'      => $list,
            'next_page' => $next_page,
        ];
    }
}
