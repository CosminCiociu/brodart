<?php
defined( 'ABSPATH' ) || exit;
use YayMail\Utils\TemplateHelpers;
use YayMail\Models\PostModel;

/**
 * $args includes
 * $element
 * $render_data
 * $is_nested
 */
if ( empty( $args['element'] ) ) {
    return;
}

$element  = $args['element'];
$settings = $args['settings'];
$data     = $element['data'];

$showing_items = isset( $data['showing_items'] ) ? $data['showing_items'] : [];
$top_content   = isset( $data['top_content'] ) ? $data['top_content'] : '';

$params['number_of_posts'] = isset( $data['number_of_posts'] ) ? $data['number_of_posts'] : PostModel::DEFAULT_LIMIT;
$params['post_type']       = isset( $data['post_type'] ) ? $data['post_type'] : 'newest';
$params['sorted_by']       = isset( $data['sorted_by'] ) ? $data['sorted_by'] : 'none';
$params['category_ids']    = isset( $data['categories'] ) ? array_map(
    function( $entity ) {
        return $entity['id'];
    },
    $data['categories']
) : [];
$params['tag_ids']         = isset( $data['tags'] ) ? array_map(
    function( $entity ) {
        return $entity['id'];
    },
    $data['tags']
) : [];
$params['post_ids']        = isset( $data['posts'] ) ? array_map(
    function( $entity ) {
        return $entity['id'];
    },
    $data['posts']
) : [];

$post_model = PostModel::get_instance();
$posts      = $post_model->get_featured_posts( $params );

usort(
    $posts,
    function( $a, $b ) use ( $params ) {
        switch ( $params['sorted_by'] ) {
            case 'name_a_z':
                $title_a = isset( $a['title'] ) ? $a['title'] : '';
                $title_b = isset( $b['title'] ) ? $b['title'] : '';
                return strnatcasecmp( $title_a, $title_b );

            case 'name_z_a':
                $title_a = isset( $a['title'] ) ? $a['title'] : '';
                $title_b = isset( $b['title'] ) ? $b['title'] : '';
                return strnatcasecmp( $title_b, $title_a );

            case 'date_ascending':
                $date_a = isset( $a['date_timestamp'] ) ? (int) $a['date_timestamp'] : 0;
                $date_b = isset( $b['date_timestamp'] ) ? (int) $b['date_timestamp'] : 0;
                return $date_a - $date_b;

            case 'date_descending':
                $date_a = isset( $a['date_timestamp'] ) ? (int) $a['date_timestamp'] : 0;
                $date_b = isset( $b['date_timestamp'] ) ? (int) $b['date_timestamp'] : 0;
                return $date_b - $date_a;

            default:
                return 0;
        }//end switch
    }
);

$wrapper_style = TemplateHelpers::get_style(
    [
        'word-break'       => 'break-word',
        'background-color' => $data['background_color'],
        'font-family'      => isset( $data['font_family'] ) ? TemplateHelpers::get_font_family_value( $data['font_family'] ) : 'initial',
        'padding'          => TemplateHelpers::get_spacing_value( isset( $data['padding'] ) ? $data['padding'] : [] ),
    ]
);

$text_color = isset( $data['text_color'] ) ? $data['text_color'] : 'initial';

$top_content_styles = TemplateHelpers::get_style(
    [
        'color' => $text_color,
    ]
);

$items_container_styles = TemplateHelpers::get_style(
    [
        'width'      => '100%',
        'text-align' => 'center',
    ]
);

$posts_per_row   = isset( $data['posts_per_row'] ) ? $data['posts_per_row'] : 2;
$button_label    = isset( $data['button_label'] ) ? $data['button_label'] : __( 'READ MORE', 'yaymail' );
$container_width = isset( $settings['container_width'] ) ? $settings['container_width'] : 605;
$items_width     = ( ( $container_width - 100 ) / $posts_per_row ) - 30;
$items_styles    = TemplateHelpers::get_style(
    [
        'width'          => "{$items_width}px",
        'padding'        => '10px',
        'text-align'     => 'center',
        'vertical-align' => 'top',
    ]
);

$item_image_styles = TemplateHelpers::get_style(
    [
        'width'      => '100%',
        'object-fit' => 'cover',
    ]
);

$post_title_styles = TemplateHelpers::get_style(
    [
        'margin-top'  => '5px',
        'font-weight' => 'bold',
        'color'       => $text_color,
    ]
);

$post_meta_styles = TemplateHelpers::get_style(
    [
        'margin-top' => '5px',
        'color'      => $text_color,
        'font-size'  => '13px',
    ]
);

$post_excerpt_styles = TemplateHelpers::get_style(
    [
        'margin-top' => '5px',
        'color'      => $text_color,
        'font-size'  => '14px',
    ]
);

$button_styles = TemplateHelpers::get_style(
    [
        'background-color' => isset( $data['button_background_color'] ) ? $data['button_background_color'] : 'initial',
        'color'            => isset( $data['button_text_color'] ) ? $data['button_text_color'] : 'initial',
        'line-height'      => '21px',
        'font-family'      => TemplateHelpers::get_font_family_value( isset( $data['font_family'] ) ? $data['font_family'] : 'inherit' ),
        'margin'           => 0,
        'padding'          => '10px 15px',
        'text-align'       => 'center',
        'text-decoration'  => 'none',
        'display'          => 'inline-block',
    ]
);
ob_start();
?>

    <?php if ( in_array( 'top_content', $showing_items, true ) ) : ?>
    <div style="<?php echo esc_attr( $top_content_styles ); ?>">
        <?php echo wp_kses_post( $top_content ); ?>
    </div>
    <?php endif; ?>

    <table style="<?php echo esc_attr( $items_container_styles ); ?>">
        <tbody>
        <tr>
            <td>
                <?php
                $post_count          = 0;
                $total_rows          = ceil( count( $posts ) / $posts_per_row );
                $last_row_post_count = count( $posts ) % $posts_per_row;
                $current_row         = 0;
                foreach ( $posts as $post ) :
                    if ( $post_count % $posts_per_row === 0 ) {
                        if ( (int) $current_row === (int) $total_rows - 1 && $last_row_post_count > 0 ) {
                            echo '<table width="' . esc_attr( $last_row_post_count * $items_width ) . 'px" align="center">';
                        } else {
                            echo '<table width="100%">';
                        }
                        echo '<tr>';
                        ++$current_row;
                    }
                    ?>
                    <td style="<?php echo esc_attr( $items_styles ); ?>">
                        <?php if ( in_array( 'post_image', $showing_items, true ) && ! empty( $post['thumbnail_src'] ) ) : ?>
                            <a href="<?php echo esc_url( isset( $post['permalink'] ) ? $post['permalink'] : '#' ); ?>" target="_blank" rel="noreferrer">
                                <img style="<?php echo esc_attr( $item_image_styles ); ?>" src="<?php echo esc_attr( $post['thumbnail_src'] ); ?>" alt="<?php echo esc_attr( isset( $post['title'] ) ? $post['title'] : '' ); ?>"></img>
                            </a>
                        <?php endif; ?>

                        <?php if ( in_array( 'post_title', $showing_items, true ) ) : ?>
                            <div style="<?php echo esc_attr( $post_title_styles ); ?>">
                                <?php echo esc_html( isset( $post['title'] ) ? $post['title'] : '' ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( in_array( 'post_excerpt', $showing_items, true ) && ! empty( $post['excerpt'] ) ) : ?>
                            <div style="<?php echo esc_attr( $post_excerpt_styles ); ?>">
                                <?php echo wp_kses_post( $post['excerpt'] ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( in_array( 'post_date', $showing_items, true ) && ! empty( $post['date'] ) ) : ?>
                            <div style="<?php echo esc_attr( $post_meta_styles ); ?>">
                                <?php echo esc_html( $post['date'] ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( in_array( 'post_author', $showing_items, true ) && ! empty( $post['author'] ) ) : ?>
                            <div style="<?php echo esc_attr( $post_meta_styles ); ?>">
                                <?php echo esc_html( $post['author'] ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( in_array( 'button', $showing_items, true ) ) : ?>
                            <div style="margin-top: 10px;">
                                <a style="<?php echo esc_attr( $button_styles ); ?>" href="<?php echo esc_url( isset( $post['permalink'] ) ? $post['permalink'] : '#' ); ?>" target="_blank" rel="noreferrer" role="button">
                                    <?php echo esc_html( $button_label ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </td>
                    <?php
                    ++$post_count;
                    if ( $post_count % $posts_per_row === 0 ) {
                        echo '</tr>';
                        echo '</table>';
                    }
                endforeach;

                if ( $post_count % $posts_per_row !== 0 ) {
                    echo '</tr>';
                    echo '</table>';
                }
                ?>
            </td>
        </tr>
        </tbody>
    </table>

<?php
$element_content = ob_get_clean();

TemplateHelpers::wrap_element_content( $element_content, $element, $wrapper_style );
