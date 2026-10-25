<?php

namespace YayMail\Controllers;

use YayMail\Abstracts\BaseController;
use YayMail\Models\PostModel;
use YayMail\Utils\SingletonTrait;

/**
 * Post Controller
 *
 * @method static PostController get_instance()
 */
class PostController extends BaseController {
    use SingletonTrait;

    /**
     * @var PostModel|null
     */
    private $model = null;

    protected function __construct() {
        $this->model = PostModel::get_instance();
        $this->init_hooks();
    }

    protected function init_hooks() {
        register_rest_route(
            YAYMAIL_REST_NAMESPACE,
            '/post/featured-post',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'exec_get_featured_posts' ],
                    'permission_callback' => [ $this, 'permission_callback' ],
                ],
            ]
        );

        register_rest_route(
            YAYMAIL_REST_NAMESPACE,
            '/post/categories',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'exec_get_categories' ],
                    'permission_callback' => [ $this, 'permission_callback' ],
                ],
            ]
        );

        register_rest_route(
            YAYMAIL_REST_NAMESPACE,
            '/post/tags',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'exec_get_tags' ],
                    'permission_callback' => [ $this, 'permission_callback' ],
                ],
            ]
        );

        register_rest_route(
            YAYMAIL_REST_NAMESPACE,
            '/post',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'exec_get_posts' ],
                    'permission_callback' => [ $this, 'permission_callback' ],
                ],
            ]
        );
    }

    /**
     * Handle get featured posts.
     *
     * @param \WP_REST_Request $request The request object.
     * @return \WP_REST_Response|\WP_Error
     */
    public function exec_get_featured_posts( \WP_REST_Request $request ) {
        return $this->exec( [ $this, 'get_featured_posts' ], $request );
    }

    /**
     * Get featured posts.
     *
     * @param \WP_REST_Request $request The request object.
     * @return array
     */
    public function get_featured_posts( \WP_REST_Request $request ) {
        $params['number_of_posts'] = sanitize_text_field( $request->get_param( 'number_of_posts' ) );
        $params['post_type']       = sanitize_text_field( $request->get_param( 'post_type' ) );
        $params['sorted_by']       = sanitize_text_field( $request->get_param( 'sorted_by' ) );

        $params['category_ids'] = json_decode( sanitize_text_field( $request->get_param( 'category_ids' ) ) );
        $params['tag_ids']      = json_decode( sanitize_text_field( $request->get_param( 'tag_ids' ) ) );
        $params['post_ids']     = json_decode( sanitize_text_field( $request->get_param( 'post_ids' ) ) );

        return $this->model->get_featured_posts( $params );
    }

    /**
     * Handle get post categories.
     *
     * @param \WP_REST_Request $request The request object.
     * @return \WP_REST_Response|\WP_Error
     */
    public function exec_get_categories( \WP_REST_Request $request ) {
        return $this->exec( [ $this, 'get_categories' ], $request );
    }

    /**
     * Get post categories.
     *
     * @param \WP_REST_Request $request The request object.
     * @return array
     */
    public function get_categories( \WP_REST_Request $request ) {
        $params['search_string'] = sanitize_text_field( $request->get_param( 'search_string' ) );
        $params['page_num']      = sanitize_text_field( $request->get_param( 'page_num' ) );
        $params['page_size']     = sanitize_text_field( $request->get_param( 'page_size' ) );
        $params['term_type']     = 'category';

        return $this->model->get_terms( $params, [ 'id' => 'term_id' ] );
    }

    /**
     * Handle get post tags.
     *
     * @param \WP_REST_Request $request The request object.
     * @return \WP_REST_Response|\WP_Error
     */
    public function exec_get_tags( \WP_REST_Request $request ) {
        return $this->exec( [ $this, 'get_tags' ], $request );
    }

    /**
     * Get post tags.
     *
     * @param \WP_REST_Request $request The request object.
     * @return array
     */
    public function get_tags( \WP_REST_Request $request ) {
        $params['search_string'] = sanitize_text_field( $request->get_param( 'search_string' ) );
        $params['page_num']      = sanitize_text_field( $request->get_param( 'page_num' ) );
        $params['page_size']     = sanitize_text_field( $request->get_param( 'page_size' ) );
        $params['term_type']     = 'post_tag';

        return $this->model->get_terms( $params, [ 'id' => 'term_id' ] );
    }

    /**
     * Handle get posts list.
     *
     * @param \WP_REST_Request $request The request object.
     * @return \WP_REST_Response|\WP_Error
     */
    public function exec_get_posts( \WP_REST_Request $request ) {
        return $this->exec( [ $this, 'get_posts' ], $request );
    }

    /**
     * Get posts for entity selector.
     *
     * @param \WP_REST_Request $request The request object.
     * @return array
     */
    public function get_posts( \WP_REST_Request $request ) {
        $params['search_string'] = sanitize_text_field( $request->get_param( 'search_string' ) );
        $params['page_num']      = sanitize_text_field( $request->get_param( 'page_num' ) );
        $params['page_size']     = sanitize_text_field( $request->get_param( 'page_size' ) );

        return $this->model->get_terms( $params );
    }
}
