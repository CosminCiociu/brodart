<?php

/**
 * Plugin Name: Brodart Menu Images
 * Description: Adds image selectors to navigation menu items and displays them in image-led dropdowns.
 * Version: 1.0.0
 * Requires at least: 5.4
 * Requires PHP: 8.0
 * Text Domain: brodart-menu-images
 */

if (! defined('ABSPATH')) {
    exit;
}

final class Brodart_Menu_Images
{
    private const IMAGE_META = '_brodart_menu_image_id';

    public function __construct()
    {
        add_action('wp_nav_menu_item_custom_fields', array($this, 'render_image_field'), 10, 5);
        add_action('wp_update_nav_menu_item', array($this, 'save_image_field'), 10, 3);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_filter('wp_nav_menu_objects', array($this, 'mark_image_submenus'), 20, 2);
        add_filter('nav_menu_item_title', array($this, 'prepend_menu_image'), 20, 4);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_styles'));
    }

    public function render_image_field(int $item_id, WP_Post $item): void
    {
        $image_id = absint(get_post_meta($item_id, self::IMAGE_META, true));
?>
        <p class="description description-wide brodart-menu-image-field">
            <label for="edit-menu-item-brodart-image-<?php echo esc_attr((string) $item_id); ?>">
                <?php esc_html_e('Imagine pentru meniu', 'brodart-menu-images'); ?><br>
                <input type="hidden" class="brodart-menu-image-id" name="menu-item-brodart-image[<?php echo esc_attr((string) $item_id); ?>]" value="<?php echo esc_attr((string) $image_id); ?>">
                <span class="brodart-menu-image-preview">
                    <?php
                    if ($image_id && wp_attachment_is_image($image_id)) {
                        echo wp_get_attachment_image($image_id, 'thumbnail', false, array('alt' => ''));
                    }
                    ?>
                </span>
                <button type="button" class="button brodart-menu-image-select">
                    <?php echo esc_html($image_id ? __('Schimbă imaginea', 'brodart-menu-images') : __('Alege imaginea', 'brodart-menu-images')); ?>
                </button>
                <button type="button" class="button-link-delete brodart-menu-image-remove" <?php disabled(! $image_id); ?>>
                    <?php esc_html_e('Elimină', 'brodart-menu-images'); ?>
                </button>
            </label>
        </p>
<?php
    }

    public function save_image_field(int $menu_id, int $menu_item_id, array $args): void
    {
        if (! current_user_can('edit_theme_options') || ! isset($_POST['update-nav-menu-nonce'])) {
            return;
        }

        $nonce = sanitize_text_field(wp_unslash($_POST['update-nav-menu-nonce']));
        if (! wp_verify_nonce($nonce, 'update-nav_menu')) {
            return;
        }

        $submitted_images = isset($_POST['menu-item-brodart-image']) && is_array($_POST['menu-item-brodart-image'])
            ? wp_unslash($_POST['menu-item-brodart-image'])
            : array();
        $image_id = isset($submitted_images[$menu_item_id]) ? absint($submitted_images[$menu_item_id]) : 0;

        if ($image_id && wp_attachment_is_image($image_id)) {
            update_post_meta($menu_item_id, self::IMAGE_META, $image_id);
        } else {
            delete_post_meta($menu_item_id, self::IMAGE_META);
        }
    }

    public function enqueue_admin_assets(string $hook): void
    {
        if ('nav-menus.php' !== $hook) {
            return;
        }

        wp_enqueue_media();
        wp_add_inline_script(
            'jquery-core',
            <<<'JS'
jQuery(function ($) {
    $(document).on('click', '.brodart-menu-image-select', function (event) {
        event.preventDefault();
        const field = $(this).closest('.brodart-menu-image-field');
        const frame = wp.media({
            title: 'Alege imaginea pentru meniu',
            button: { text: 'Folosește imaginea' },
            library: { type: 'image' },
            multiple: false
        });

        frame.on('select', function () {
            const image = frame.state().get('selection').first().toJSON();
            field.find('.brodart-menu-image-id').val(image.id);
            field.find('.brodart-menu-image-preview').empty().append(
                $('<img>', { src: image.sizes.thumbnail ? image.sizes.thumbnail.url : image.url, alt: '' })
            );
            field.find('.brodart-menu-image-select').text('Schimbă imaginea');
            field.find('.brodart-menu-image-remove').prop('disabled', false);
        });

        frame.open();
    });

    $(document).on('click', '.brodart-menu-image-remove', function (event) {
        event.preventDefault();
        const field = $(this).closest('.brodart-menu-image-field');
        field.find('.brodart-menu-image-id').val('');
        field.find('.brodart-menu-image-preview').empty();
        field.find('.brodart-menu-image-select').text('Alege imaginea');
        $(this).prop('disabled', true);
    });
});
JS
        );
    }

    public function mark_image_submenus(array $items, stdClass $args): array
    {
        $items_by_id = array();
        foreach ($items as $item) {
            $items_by_id[(int) $item->ID] = $item;
        }

        foreach ($items as $item) {
            if (! get_post_meta($item->ID, self::IMAGE_META, true)) {
                continue;
            }

            if (! in_array('brodart-menu-image-item', $item->classes, true)) {
                $item->classes[] = 'brodart-menu-image-item';
            }

            $parent_id = (int) $item->menu_item_parent;
            while ($parent_id && isset($items_by_id[$parent_id])) {
                $parent = $items_by_id[$parent_id];
                if (! in_array('brodart-image-submenu', $parent->classes, true)) {
                    $parent->classes[] = 'brodart-image-submenu';
                }
                $parent_id = (int) $parent->menu_item_parent;
            }
        }

        return $items;
    }

    public function prepend_menu_image(string $title, WP_Post $item, stdClass $args, int $depth): string
    {
        $image_id = absint(get_post_meta($item->ID, self::IMAGE_META, true));
        if (! $image_id || ! wp_attachment_is_image($image_id)) {
            return $title;
        }

        $image = wp_get_attachment_image(
            $image_id,
            'medium',
            false,
            array(
                'class' => 'brodart-menu-image',
                'alt' => '',
                'loading' => 'lazy',
                'decoding' => 'async',
            )
        );

        return '<span class="brodart-menu-card__image">' . $image . '</span><span class="brodart-menu-card__title">' . wp_kses_post($title) . '</span>';
    }

    public function enqueue_frontend_styles(): void
    {
        wp_register_style('brodart-menu-images', false, array(), '1.0.0');
        wp_enqueue_style('brodart-menu-images');
        wp_add_inline_style('brodart-menu-images', '
            @media (min-width: 768px) {
                nav.header-menu-1 li.animated-submenu-block.brodart-image-submenu {
                    position: static;
                }
            }
            nav.header-menu-1 .brodart-image-submenu > .sub-menu {
                --dropdown-background-color: var(--theme-palette-color-8, #fff);
                --dropdown-divider: none;
                --theme-link-initial-color: var(--theme-text-color, #1a1a1a);
                --theme-link-hover-color: var(--theme-palette-color-2, #7f715c);
                --theme-link-active-color: var(--theme-palette-color-2, #7f715c);
                box-sizing: border-box;
                display: grid;
                grid-template-columns: repeat(6, minmax(0, 1fr));
                gap: 20px 16px;
                width: min(1080px, calc(100vw - 48px));
                max-width: calc(100vw - 48px);
                padding: 24px;
                left: 50%;
                translate: -50% 0;
                color: var(--theme-text-color, #1a1a1a);
                background-color: var(--theme-palette-color-8, #fff);
                border: 1px solid var(--theme-palette-color-5, #eaeaec);
                border-radius: 2px;
            }
            .brodart-image-submenu > .sub-menu > li {
                min-width: 0;
                border-top: 0;
            }
            .brodart-image-submenu > .sub-menu > li > .ct-menu-link {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 9px;
                padding: 0;
                white-space: normal;
                color: var(--theme-text-color, #1a1a1a) !important;
                font-size: 13px;
                font-weight: 500;
            }
            .brodart-image-submenu > .sub-menu > li > .ct-menu-link:hover {
                color: var(--theme-palette-color-2, #7f715c) !important;
            }
            .brodart-menu-card__image,
            .brodart-menu-image {
                display: block;
                width: 100%;
            }
            .brodart-menu-card__image {
                overflow: hidden;
                aspect-ratio: 3 / 2;
                background: var(--theme-palette-color-6, #f4f4f5);
            }
            .brodart-image-submenu > .sub-menu > li:not(.brodart-menu-image-item) > .ct-menu-link::before {
                content: "";
                display: block;
                width: 100%;
                aspect-ratio: 3 / 2;
                background-color: var(--theme-palette-color-6, #f4f4f5);
            }
            .brodart-menu-image {
                height: 100%;
                object-fit: cover;
                transition: opacity 250ms ease;
            }
            .brodart-image-submenu > .sub-menu > li > .ct-menu-link:hover .brodart-menu-image {
                opacity: .82;
            }
            .brodart-menu-card__title {
                display: block;
                line-height: 1.35;
            }
            @media (max-width: 900px) and (min-width: 768px) {
                nav.header-menu-1 .brodart-image-submenu > .sub-menu {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                    width: min(760px, calc(100vw - 32px));
                    max-width: calc(100vw - 32px);
                    gap: 16px 12px;
                    padding: 20px;
                }
            }
            @media (max-width: 767px) {
                .mobile-menu .brodart-image-submenu > .sub-menu {
                    position: static;
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    width: 100%;
                    max-width: none;
                    padding: 12px 0;
                    gap: 16px 12px;
                    translate: none;
                }
                .mobile-menu .brodart-image-submenu > .sub-menu > li > .ct-menu-link {
                    white-space: normal;
                }
            }
        ');
    }
}

new Brodart_Menu_Images();
