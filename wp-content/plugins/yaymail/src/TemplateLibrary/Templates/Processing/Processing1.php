<?php

namespace YayMail\TemplateLibrary\Templates\Processing;

use YayMail\Abstracts\BaseTemplate;
use YayMail\Elements\BillingAddress;
use YayMail\Elements\Button;
use YayMail\Elements\Column;
use YayMail\Elements\ColumnLayout;
use YayMail\Elements\Divider;
use YayMail\Elements\Heading;
use YayMail\Elements\Image;
use YayMail\Elements\Logo;
use YayMail\Elements\OrderDetails;
use YayMail\Elements\ShippingAddress;
use YayMail\Elements\SocialIcon;
use YayMail\Elements\Text;
use YayMail\Utils\SingletonTrait;

/**
 * Processing 1 template for Processing Order email.
 */
class Processing1 extends BaseTemplate {
    use SingletonTrait;

    public function __construct() {
        parent::__construct();
        $this->id                = 'processing_order_v1';
        $this->email_type        = 'customer_processing_order';
        $this->name              = 'Processing 1';
        $this->description       = 'Clean layout with plenty of whitespace';
        $this->template_settings = [
            'text_link_color' => '#1C672F',
        ];

        $this->elements = [
            ColumnLayout::get_object_data(
                4,
                [
                    'column_width'                       => [],
                    'column_spacing'                     => '0',
                    'vertical_align'                     => 'top',
                    'container_setting_breaker'          => [
                        'component' => 'LineBreaker',
                    ],
                    'container_setting_group_definition' => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Container layout settings',
                        'description' => 'Handle container layout settings',
                    ],
                    'padding'                            => [
                        'top'    => 20,
                        'right'  => 50,
                        'bottom' => 20,
                        'left'   => 50,
                    ],
                    'border_radius'                      => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'background_color'                   => '#ffffe1',
                    'background_image'                   => [
                        'url'        => '',
                        'position'   => 'default',
                        'x_position' => 0,
                        'y_position' => 0,
                        'repeat'     => 'default',
                        'size'       => 'default',
                    ],
                    'inner_setting_breaker'              => [
                        'component' => 'LineBreaker',
                    ],
                    'inner_setting_group_definition'     => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Inner layout settings',
                        'description' => 'Handle inner layout settings',
                    ],
                    'inner_border_radius'                => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'inner_background_color'             => '#ffffff00',
                    'column_borders'                     => [
                        'component' => 'ColumnBorders',
                        'title'     => 'Column borders',
                    ],
                    'children'                           => [
                        Column::get_object_data(
                            29,
                            [
                                'children' => [
                                    Logo::get_object_data(
                                        [
                                            'padding' => [
                                                'top'    => 10,
                                                'right'  => 10,
                                                'bottom' => 10,
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#f9f9f900',
                                            'src'     => 'https://images.wpbrandy.com/uploads/woo-green-logo.png',
                                            'align'   => 'left',
                                            'width'   => 116,
                                            'url'     => '#',
                                            'alt'     => '',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                        Column::get_object_data(
                            34.49,
                            [
                                'children' => [
                                    Text::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => 0,
                                                'bottom' => '15',
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#ffffff00',
                                            'border'      => [
                                                'side'   => 'none',
                                                'width'  => '1',
                                                'style'  => 'solid',
                                                'color'  => '#e5e5e5',
                                                'custom' => [
                                                    'top'  => '1',
                                                    'right' => '1',
                                                    'bottom' => '1',
                                                    'left' => '1',
                                                ],
                                            ],
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#1c672f',
                                            'rich_text'   => '<div style="text-align: right"><span style="font-size: 14px;font-weight: 500">My Account</span></div>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                        Column::get_object_data(
                            27.76,
                            [
                                'children' => [
                                    Text::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => 0,
                                                'bottom' => '15',
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#ffffff00',
                                            'border'      => [
                                                'side'   => 'none',
                                                'width'  => '1',
                                                'style'  => 'solid',
                                                'color'  => '#e5e5e5',
                                                'custom' => [
                                                    'top'  => '1',
                                                    'right' => '1',
                                                    'bottom' => '1',
                                                    'left' => '1',
                                                ],
                                            ],
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#1c672f',
                                            'rich_text'   => '<div style="text-align: center"><span style="font-size: 14px;font-weight: 500">Order Tracking</span></div>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                        Column::get_object_data(
                            8.75,
                            [
                                'children' => [
                                    Text::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => 2,
                                                'bottom' => '15',
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#ffffff00',
                                            'border'      => [
                                                'side'   => 'none',
                                                'width'  => '1',
                                                'style'  => 'solid',
                                                'color'  => '#e5e5e5',
                                                'custom' => [
                                                    'top'  => '1',
                                                    'right' => '1',
                                                    'bottom' => '1',
                                                    'left' => '1',
                                                ],
                                            ],
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#1c672f',
                                            'rich_text'   => '<div style="text-align: right"><span style="font-size: 14px;font-weight: 500">Contact</span></div>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                ]
            ),
            Divider::get_object_data(
                [
                    'align'            => 'center',
                    'padding'          => [
                        'top'    => 1,
                        'right'  => 0,
                        'bottom' => 1,
                        'left'   => 0,
                    ],
                    'width'            => '100',
                    'height'           => 1,
                    'background_color' => '#ffffe1',
                    'divider_color'    => '#1c672f33',
                    'divider_type'     => 'solid',
                ]
            ),
            ColumnLayout::get_object_data(
                1,
                [
                    'column_width'                       => [],
                    'column_spacing'                     => '0',
                    'vertical_align'                     => 'top',
                    'container_setting_breaker'          => [
                        'component' => 'LineBreaker',
                    ],
                    'container_setting_group_definition' => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Container layout settings',
                        'description' => 'Handle container layout settings',
                    ],
                    'padding'                            => [
                        'top'    => 50,
                        'right'  => '0',
                        'bottom' => 50,
                        'left'   => '0',
                    ],
                    'border_radius'                      => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'background_color'                   => '#ffffe1',
                    'background_image'                   => [
                        'url'        => '',
                        'position'   => 'default',
                        'x_position' => 0,
                        'y_position' => 0,
                        'repeat'     => 'default',
                        'size'       => 'default',
                    ],
                    'inner_setting_breaker'              => [
                        'component' => 'LineBreaker',
                    ],
                    'inner_setting_group_definition'     => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Inner layout settings',
                        'description' => 'Handle inner layout settings',
                    ],
                    'inner_border_radius'                => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'inner_background_color'             => '#ffffff00',
                    'column_borders'                     => [
                        'component' => 'ColumnBorders',
                        'title'     => 'Column borders',
                    ],
                    'children'                           => [
                        Column::get_object_data(
                            100,
                            [
                                'children' => [
                                    Image::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding' => [
                                                'top'    => 0,
                                                'right'  => '50',
                                                'bottom' => '15',
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#ffffff00',
                                            'image_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'image_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Image settings',
                                                'description' => 'Handle image settings',
                                            ],
                                            'src'     => 'https://images.wpbrandy.com/uploads/processing-icon-1.png',
                                            'align'   => 'center',
                                            'width'   => 114,
                                            'url'     => '#',
                                            'alt'     => '',
                                        ]
                                    ),
                                    Heading::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => 0,
                                                'right'  => '50',
                                                'bottom' => 12,
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#883eff00',
                                            'font_family' => '"Arial Black", Gadget, sans-serif',
                                            'text_color'  => '#1c672f',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 40px;font-weight: 800; line-height: normal">New Order Received</span></p><p style="text-align: center"><span style="font-size: 40px;font-weight: 800; line-height: normal">Please Review</span></p>',
                                        ]
                                    ),
                                    Text::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'     => [
                                                'top'    => 0,
                                                'right'  => 50,
                                                'bottom' => 0,
                                                'left'   => 50,
                                            ],
                                            'background_color' => '#ffffff00',
                                            'border'      => [
                                                'side'   => 'none',
                                                'width'  => '1',
                                                'style'  => 'solid',
                                                'color'  => '#e5e5e5',
                                                'custom' => [
                                                    'top'  => '1',
                                                    'right' => '1',
                                                    'bottom' => '1',
                                                    'left' => '1',
                                                ],
                                            ],
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#1c672f',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 16px">Order placed on [yaymail_order_date]</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                ]
            ),
            ColumnLayout::get_object_data(
                1,
                [
                    'column_width'                       => [],
                    'column_spacing'                     => '0',
                    'vertical_align'                     => 'top',
                    'container_setting_breaker'          => [
                        'component' => 'LineBreaker',
                    ],
                    'container_setting_group_definition' => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Container layout settings',
                        'description' => 'Handle container layout settings',
                    ],
                    'padding'                            => [
                        'top'    => '15',
                        'right'  => '0',
                        'bottom' => 50,
                        'left'   => '0',
                    ],
                    'border_radius'                      => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'background_color'                   => '#ffffe1',
                    'background_image'                   => [
                        'url'        => 'https://images.wpbrandy.com/uploads/processing-icon-2-scaled.png',
                        'position'   => 'default',
                        'x_position' => 0,
                        'y_position' => 0,
                        'repeat'     => 'default',
                        'size'       => 'cover',
                    ],
                    'inner_setting_breaker'              => [
                        'component' => 'LineBreaker',
                    ],
                    'inner_setting_group_definition'     => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Inner layout settings',
                        'description' => 'Handle inner layout settings',
                    ],
                    'inner_border_radius'                => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'inner_background_color'             => '#ffffff00',
                    'column_borders'                     => [
                        'component' => 'ColumnBorders',
                        'title'     => 'Column borders',
                    ],
                    'children'                           => [
                        Column::get_object_data(
                            100,
                            [
                                'children' => [
                                    Text::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => '50',
                                                'bottom' => 0,
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#ffffff00',
                                            'border'      => [
                                                'side'   => 'none',
                                                'width'  => '1',
                                                'style'  => 'solid',
                                                'color'  => '#e5e5e5',
                                                'custom' => [
                                                    'top'  => '1',
                                                    'right' => '1',
                                                    'bottom' => '1',
                                                    'left' => '1',
                                                ],
                                            ],
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#1c672f',
                                            'rich_text'   => '<p><span style="font-size: 18px">Hi [yaymail_billing_first_name] [yaymail_billing_last_name]<strong>,</strong></span></p><br /><p><span style="font-size: 18px">Thanks for your order!</span></p><p><span style="font-size: 18px">We\'ve successfully received it and it\'s now being processed. Your order will be shipped within 24-48 hours.</span></p>',
                                        ]
                                    ),
                                    ColumnLayout::get_object_data(
                                        2,
                                        [
                                            'column_width' => [],
                                            'column_spacing' => '0',
                                            'vertical_align' => 'middle',
                                            'container_setting_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'container_setting_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container layout settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'      => [
                                                'top'    => '15',
                                                'right'  => '0',
                                                'bottom' => 1,
                                                'left'   => '0',
                                            ],
                                            'border_radius' => [
                                                'top_left' => '0',
                                                'top_right' => '0',
                                                'bottom_left' => '0',
                                                'bottom_right' => '0',
                                            ],
                                            'background_color' => '#ffffff00',
                                            'background_image' => [
                                                'url'      => '',
                                                'position' => 'default',
                                                'x_position' => 0,
                                                'y_position' => 0,
                                                'repeat'   => 'default',
                                                'size'     => 'default',
                                            ],
                                            'inner_setting_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'inner_setting_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Inner layout settings',
                                                'description' => 'Handle inner layout settings',
                                            ],
                                            'inner_border_radius' => [
                                                'top_left' => '0',
                                                'top_right' => '0',
                                                'bottom_left' => '0',
                                                'bottom_right' => '0',
                                            ],
                                            'inner_background_color' => '#ffffff00',
                                            'column_borders' => [
                                                'component' => 'ColumnBorders',
                                                'title' => 'Column borders',
                                            ],
                                            'children'     => [
                                                Column::get_object_data(
                                                    50,
                                                    [
                                                        'children' => [
                                                            Text::get_object_data(
                                                                [
                                                                    'container_group_definition' => [
                                                                        'component' => 'GroupDefinition',
                                                                        'title' => 'Container settings',
                                                                        'description' => 'Handle container layout settings',
                                                                    ],
                                                                    'padding' => [
                                                                        'top' => 0,
                                                                        'right' => '50',
                                                                        'bottom' => 0,
                                                                        'left' => '50',
                                                                    ],
                                                                    'background_color' => '#ffffff00',
                                                                    'border' => [
                                                                        'side' => 'none',
                                                                        'width' => '1',
                                                                        'style' => 'solid',
                                                                        'color' => '#e5e5e5',
                                                                        'custom' => [
                                                                            'top' => '1',
                                                                            'right' => '1',
                                                                            'bottom' => '1',
                                                                            'left' => '1',
                                                                        ],
                                                                    ],
                                                                    'content_breaker' => [
                                                                        'component' => 'LineBreaker',
                                                                    ],
                                                                    'content_group_definition' => [
                                                                        'component' => 'GroupDefinition',
                                                                        'title' => 'Content settings',
                                                                        'description' => 'Handle content settings',
                                                                    ],
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color' => '#1c672f',
                                                                    'rich_text' => '<p style="text-align: left"><span style="font-size: 24px;font-weight: 800"><strong>Order Summary</strong></span></p>',
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                                Column::get_object_data(
                                                    50,
                                                    [
                                                        'children' => [
                                                            Text::get_object_data(
                                                                [
                                                                    'container_group_definition' => [
                                                                        'component' => 'GroupDefinition',
                                                                        'title' => 'Container settings',
                                                                        'description' => 'Handle container layout settings',
                                                                    ],
                                                                    'padding' => [
                                                                        'top' => 0,
                                                                        'right' => '50',
                                                                        'bottom' => 0,
                                                                        'left' => '50',
                                                                    ],
                                                                    'background_color' => '#ffffff00',
                                                                    'border' => [
                                                                        'side' => 'none',
                                                                        'width' => '1',
                                                                        'style' => 'solid',
                                                                        'color' => '#e5e5e5',
                                                                        'custom' => [
                                                                            'top' => '1',
                                                                            'right' => '1',
                                                                            'bottom' => '1',
                                                                            'left' => '1',
                                                                        ],
                                                                    ],
                                                                    'content_breaker' => [
                                                                        'component' => 'LineBreaker',
                                                                    ],
                                                                    'content_group_definition' => [
                                                                        'component' => 'GroupDefinition',
                                                                        'title' => 'Content settings',
                                                                        'description' => 'Handle content settings',
                                                                    ],
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color' => '#1c672f',
                                                                    'rich_text' => '<div style="text-align: right"><strong>Number ID: #[yaymail_order_id is_plain="true"]</strong></div>',
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                            ],
                                        ]
                                    ),
                                    OrderDetails::get_object_data(
                                        [
                                            'rich_text'    => '[yaymail_order_details]',
                                            'payment_instructions' => '[yaymail_payment_instructions]',
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'      => [
                                                'top'    => 0,
                                                'right'  => '50',
                                                'bottom' => 10,
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#ffffff00',
                                            'border'       => [
                                                'side'   => 'none',
                                                'width'  => '1',
                                                'style'  => 'solid',
                                                'color'  => '#e5e5e5',
                                                'custom' => [
                                                    'top'  => '1',
                                                    'right' => '1',
                                                    'bottom' => '1',
                                                    'left' => '1',
                                                ],
                                            ],
                                            'table_setting_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'table_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Table settings',
                                                'description' => 'Handle table settings',
                                            ],
                                            'layout_type'  => 'legacy',
                                            'title'        => '',
                                            'title_color'  => '#873EFF',
                                            'text_color'   => '#1c672f',
                                            'border_color' => '#23661b33',
                                            'font_family'  => '"Outfit", "DM Sans", sans-serif',
                                            'table_content_font_size' => 16,
                                            'table_heading_line_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'table_heading_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Table heading settings',
                                                'description' => 'Handle table heading settings',
                                            ],
                                            'table_heading_font_size' => 14,
                                            'show_table_header' => 1,
                                            'product_title' => 'Product',
                                            'cost_title'   => 'Cost',
                                            'quantity_title' => 'Quantity',
                                            'price_title'  => 'Price',
                                            'cart_subtotal_title' => 'Subtotal:',
                                            'payment_method_title' => 'Payment method:',
                                            'order_total_title' => 'Total:',
                                            'order_note_title' => 'Note:',
                                            'shipping_title' => 'Shipping: [yaymail_shipping_method]',
                                            'discount_title' => 'Discount:',
                                            'custom_footer_rows_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'custom_footer_rows_group' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Custom Footer Rows',
                                                'description' => 'Add custom rows to the order totals footer',
                                            ],
                                            'custom_footer_rows' => [],
                                        ]
                                    ),
                                    Button::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'      => [
                                                'top'    => 10,
                                                'right'  => '50',
                                                'bottom' => 15,
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#ffffff00',
                                            'button_setting_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'button_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Button settings',
                                                'description' => 'Handle button settings',
                                            ],
                                            'button_type'  => 'default',
                                            'align'        => 'center',
                                            'width'        => '100%',
                                            'custom_width' => '50',
                                            'height'       => '21',
                                            'button_padding' => [
                                                'top'    => '12',
                                                'right'  => '20',
                                                'bottom' => '12',
                                                'left'   => '20',
                                            ],
                                            'button_background_color' => '#1f6816',
                                            'text_color'   => '#ffffff',
                                            'border'       => [
                                                'side'   => 'none',
                                                'width'  => '1',
                                                'style'  => 'solid',
                                                'color'  => '#e5e5e5',
                                                'custom' => [
                                                    'top'  => '1',
                                                    'right' => '1',
                                                    'bottom' => '1',
                                                    'left' => '1',
                                                ],
                                            ],
                                            'border_radius' => [
                                                'top_left' => '5',
                                                'top_right' => '5',
                                                'bottom_right' => '5',
                                                'bottom_left' => '5',
                                            ],
                                            'button_content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Button content',
                                                'description' => 'Handle button content',
                                            ],
                                            'text'         => 'View Order Detail',
                                            'url'          => '[yaymail_order_url]',
                                            'font_size'    => 16,
                                            'weight'       => 'normal',
                                            'font_family'  => '"Outfit", "DM Sans", sans-serif',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                ]
            ),
            ColumnLayout::get_object_data(
                2,
                [
                    'column_width'                       => [],
                    'column_spacing'                     => 0,
                    'vertical_align'                     => 'top',
                    'container_setting_breaker'          => [
                        'component' => 'LineBreaker',
                    ],
                    'container_setting_group_definition' => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Container layout settings',
                        'description' => 'Handle container layout settings',
                    ],
                    'padding'                            => [
                        'top'    => 30,
                        'right'  => 50,
                        'bottom' => 30,
                        'left'   => 50,
                    ],
                    'border_radius'                      => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'background_color'                   => '#ffffe1',
                    'background_image'                   => [
                        'url'        => '',
                        'position'   => 'default',
                        'x_position' => 0,
                        'y_position' => 0,
                        'repeat'     => 'default',
                        'size'       => 'default',
                    ],
                    'inner_setting_breaker'              => [
                        'component' => 'LineBreaker',
                    ],
                    'inner_setting_group_definition'     => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Inner layout settings',
                        'description' => 'Handle inner layout settings',
                    ],
                    'inner_border_radius'                => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'inner_background_color'             => '#ffffff00',
                    'column_borders'                     => [
                        'component' => 'ColumnBorders',
                        'title'     => 'Column borders',
                    ],
                    'children'                           => [
                        Column::get_object_data(
                            50,
                            [
                                'children' => [
                                    Text::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'     => [
                                                'top'    => 0,
                                                'right'  => 20,
                                                'bottom' => 0,
                                                'left'   => 20,
                                            ],
                                            'background_color' => '#ffffff00',
                                            'border'      => [
                                                'side'   => 'none',
                                                'width'  => '1',
                                                'style'  => 'solid',
                                                'color'  => '#e5e5e5',
                                                'custom' => [
                                                    'top'  => '1',
                                                    'right' => '1',
                                                    'bottom' => '1',
                                                    'left' => '1',
                                                ],
                                            ],
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'font_family' => '"Arial Black", Gadget, sans-serif',
                                            'text_color'  => '#1c672f',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 20px;font-weight: 800"><strong>SHIPPING ADDRESS</strong></span></p>',
                                        ]
                                    ),
                                    ShippingAddress::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'      => [
                                                'top'    => 0,
                                                'right'  => 0,
                                                'bottom' => 0,
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#fafafa00',
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'layout_type'  => 'legacy',
                                            'title_color'  => '#1a1a1a',
                                            'text_color'   => '#1c672f',
                                            'border_color' => '#23661b33',
                                            'font_family'  => '"Outfit", "DM Sans", sans-serif',
                                            'title'        => '',
                                            'shipping_content_font_size' => '14',
                                            'shipping_content_alignment' => 'center',
                                            'shipping_content_text_format' => [
                                                'bold'   => false,
                                                'italic' => false,
                                                'underline' => false,
                                            ],
                                            'rich_text'    => '[yaymail_shipping_address]',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                        Column::get_object_data(
                            50,
                            [
                                'children' => [
                                    Text::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'     => [
                                                'top'    => 0,
                                                'right'  => 20,
                                                'bottom' => 0,
                                                'left'   => 20,
                                            ],
                                            'background_color' => '#ffffff00',
                                            'border'      => [
                                                'side'   => 'none',
                                                'width'  => '1',
                                                'style'  => 'solid',
                                                'color'  => '#e5e5e5',
                                                'custom' => [
                                                    'top'  => '1',
                                                    'right' => '1',
                                                    'bottom' => '1',
                                                    'left' => '1',
                                                ],
                                            ],
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'font_family' => '"Arial Black", Gadget, sans-serif',
                                            'text_color'  => '#1c672f',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 20px"><strong>BILLING ADDRESS</strong></span></p>',
                                        ]
                                    ),
                                    BillingAddress::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'      => [
                                                'top'    => 0,
                                                'right'  => 0,
                                                'bottom' => 0,
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#fafafa00',
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'layout_type'  => 'legacy',
                                            'title_color'  => '#1a1a1a',
                                            'text_color'   => '#1c672f',
                                            'border_color' => '#23661b33',
                                            'font_family'  => '"Outfit", "DM Sans", sans-serif',
                                            'title'        => '',
                                            'billing_content_font_size' => '14',
                                            'billing_content_alignment' => 'center',
                                            'billing_content_text_format' => [
                                                'bold'   => false,
                                                'italic' => false,
                                                'underline' => false,
                                            ],
                                            'rich_text'    => '[yaymail_billing_address]',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                ]
            ),
            Text::get_object_data(
                [
                    'container_group_definition' => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Container settings',
                        'description' => 'Handle container layout settings',
                    ],
                    'padding'                    => [
                        'top'    => 20,
                        'right'  => 50,
                        'bottom' => 24,
                        'left'   => 50,
                    ],
                    'background_color'           => '#ffffe1',
                    'border'                     => [
                        'side'   => 'none',
                        'width'  => '1',
                        'style'  => 'solid',
                        'color'  => '#e5e5e5',
                        'custom' => [
                            'top'    => '1',
                            'right'  => '1',
                            'bottom' => '1',
                            'left'   => '1',
                        ],
                    ],
                    'content_breaker'            => [
                        'component' => 'LineBreaker',
                    ],
                    'content_group_definition'   => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Content settings',
                        'description' => 'Handle content settings',
                    ],
                    'font_family'                => '"Outfit", "DM Sans", sans-serif',
                    'text_color'                 => '#1c672f',
                    'rich_text'                  => '<p><span style="font-size: 16px;font-weight: 400">Thanks again for shopping with us!</span></p><p><span style="font-size: 16px;font-weight: 400">Got questions? Check out our <u>Returns</u> &amp; <u>Refunds Policy</u> or contact <u>Here</u> for support. We\'re happy to help!</span></p>',
                ]
            ),
            Text::get_object_data(
                [
                    'container_group_definition' => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Container settings',
                        'description' => 'Handle container layout settings',
                    ],
                    'padding'                    => [
                        'top'    => 0,
                        'right'  => 50,
                        'bottom' => 50,
                        'left'   => 50,
                    ],
                    'background_color'           => '#ffffe1',
                    'border'                     => [
                        'side'   => 'none',
                        'width'  => '1',
                        'style'  => 'solid',
                        'color'  => '#e5e5e5',
                        'custom' => [
                            'top'    => '1',
                            'right'  => '1',
                            'bottom' => '1',
                            'left'   => '1',
                        ],
                    ],
                    'content_breaker'            => [
                        'component' => 'LineBreaker',
                    ],
                    'content_group_definition'   => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Content settings',
                        'description' => 'Handle content settings',
                    ],
                    'font_family'                => '"Outfit", "DM Sans", sans-serif',
                    'text_color'                 => '#1c672f',
                    'rich_text'                  => '<p><span style="font-size: 16px;font-weight: 400">Best regards,</span></p><p><span style="font-size: 16px;font-weight: 400">The [yaymail_site_name] Team</span></p>',
                ]
            ),
            ColumnLayout::get_object_data(
                1,
                [
                    'column_width'                       => [],
                    'column_spacing'                     => '0',
                    'vertical_align'                     => 'top',
                    'container_setting_breaker'          => [
                        'component' => 'LineBreaker',
                    ],
                    'container_setting_group_definition' => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Container layout settings',
                        'description' => 'Handle container layout settings',
                    ],
                    'padding'                            => [
                        'top'    => 35,
                        'bottom' => 10,
                        'left'   => 0,
                        'right'  => 0,
                    ],
                    'border_radius'                      => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'background_color'                   => '#ffffe1',
                    'background_image'                   => [
                        'url'         => 'https://images.wpbrandy.com/uploads/processing-icon-3-scaled.png',
                        'position'    => 'default',
                        'x_position'  => 0,
                        'y_position'  => 0,
                        'repeat'      => 'default',
                        'size'        => 'cover',
                        'custom_size' => 100,
                    ],
                    'inner_setting_breaker'              => [
                        'component' => 'LineBreaker',
                    ],
                    'inner_setting_group_definition'     => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Inner layout settings',
                        'description' => 'Handle inner layout settings',
                    ],
                    'inner_border_radius'                => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'inner_background_color'             => '#ffffff00',
                    'column_borders'                     => [
                        'component' => 'ColumnBorders',
                        'title'     => 'Column borders',
                    ],
                    'children'                           => [
                        Column::get_object_data(
                            100,
                            [
                                'children' => [
                                    ColumnLayout::get_object_data(
                                        2,
                                        [
                                            'column_width' => [],
                                            'column_spacing' => '0',
                                            'vertical_align' => 'middle',
                                            'container_setting_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'container_setting_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container layout settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'      => [
                                                'top'    => 10,
                                                'right'  => '0',
                                                'bottom' => '15',
                                                'left'   => '0',
                                            ],
                                            'border_radius' => [
                                                'top_left' => '0',
                                                'top_right' => '0',
                                                'bottom_left' => '0',
                                                'bottom_right' => '0',
                                            ],
                                            'background_color' => '#ffffff00',
                                            'background_image' => [
                                                'url'      => '',
                                                'position' => 'default',
                                                'x_position' => 0,
                                                'y_position' => 0,
                                                'repeat'   => 'default',
                                                'size'     => 'default',
                                            ],
                                            'inner_setting_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'inner_setting_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Inner layout settings',
                                                'description' => 'Handle inner layout settings',
                                            ],
                                            'inner_border_radius' => [
                                                'top_left' => '0',
                                                'top_right' => '0',
                                                'bottom_left' => '0',
                                                'bottom_right' => '0',
                                            ],
                                            'inner_background_color' => '#ffffff00',
                                            'column_borders' => [
                                                'component' => 'ColumnBorders',
                                                'title' => 'Column borders',
                                            ],
                                            'children'     => [
                                                Column::get_object_data(
                                                    50,
                                                    [
                                                        'children' => [
                                                            Text::get_object_data(
                                                                [
                                                                    'container_group_definition' => [
                                                                        'component' => 'GroupDefinition',
                                                                        'title' => 'Container settings',
                                                                        'description' => 'Handle container layout settings',
                                                                    ],
                                                                    'padding' => [
                                                                        'top' => 0,
                                                                        'right' => 50,
                                                                        'bottom' => 0,
                                                                        'left' => 50,
                                                                    ],
                                                                    'background_color' => '#ffffff00',
                                                                    'border' => [
                                                                        'side' => 'none',
                                                                        'width' => '1',
                                                                        'style' => 'solid',
                                                                        'color' => '#e5e5e5',
                                                                        'custom' => [
                                                                            'top' => '1',
                                                                            'right' => '1',
                                                                            'bottom' => '1',
                                                                            'left' => '1',
                                                                        ],
                                                                    ],
                                                                    'content_breaker' => [
                                                                        'component' => 'LineBreaker',
                                                                    ],
                                                                    'content_group_definition' => [
                                                                        'component' => 'GroupDefinition',
                                                                        'title' => 'Content settings',
                                                                        'description' => 'Handle content settings',
                                                                    ],
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color' => '#1c672f',
                                                                    'rich_text' => '<p style="margin: 0px;font-weight: 300;text-align: left"><span style="font-size: 12px">© 2025 YayCommerce.com</span></p>',
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                                Column::get_object_data(
                                                    50,
                                                    [
                                                        'children' => [
                                                            SocialIcon::get_object_data(
                                                                [
                                                                    'container_group_definition' => [
                                                                        'component' => 'GroupDefinition',
                                                                        'title' => 'Container settings',
                                                                        'description' => 'Handle container layout settings',
                                                                    ],
                                                                    'padding' => [
                                                                        'top' => 0,
                                                                        'right' => 50,
                                                                        'bottom' => 0,
                                                                        'left' => 0,
                                                                    ],
                                                                    'background_color' => '#ffffff00',
                                                                    'align' => 'right',
                                                                    'content_breaker' => [
                                                                        'component' => 'LineBreaker',
                                                                    ],
                                                                    'content_group_definition' => [
                                                                        'component' => 'GroupDefinition',
                                                                        'title' => 'Content settings',
                                                                        'description' => 'Handle content settings',
                                                                    ],
                                                                    'width_icon' => 24,
                                                                    'spacing' => 10,
                                                                    'theme' => 'Colorful',
                                                                    'icon_list' => [
                                                                        '0' => [
                                                                            'icon' => 'facebook',
                                                                            'url' => '#',
                                                                        ],
                                                                        '1' => [
                                                                            'icon' => 'instagram',
                                                                            'url' => '#',
                                                                        ],
                                                                        '2' => [
                                                                            'icon' => 'tiktok',
                                                                            'url' => '#',
                                                                        ],
                                                                        '3' => [
                                                                            'icon' => 'twitter',
                                                                            'url' => '#',
                                                                        ],
                                                                    ],
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                            ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                ]
            ),
        ];
    }
}
