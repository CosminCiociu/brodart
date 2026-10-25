<?php

namespace YayMail\TemplateLibrary\Templates\Processing;

use YayMail\Abstracts\BaseTemplate;
use YayMail\Elements\BillingAddress;
use YayMail\Elements\Button;
use YayMail\Elements\Column;
use YayMail\Elements\ColumnLayout;
use YayMail\Elements\Divider;
use YayMail\Elements\Heading;
use YayMail\Elements\Logo;
use YayMail\Elements\OrderDetails;
use YayMail\Elements\ShippingAddress;
use YayMail\Elements\SocialIcon;
use YayMail\Elements\Text;
use YayMail\Utils\SingletonTrait;

/**
 * Neon Glow template for Processing Order email.
 */
class NeonGlow extends BaseTemplate {
    use SingletonTrait;

    public function __construct() {
        parent::__construct();
        $this->id                = 'processing_order_v3';
        $this->email_type        = 'customer_processing_order';
        $this->name              = 'Neon Glow';
        $this->description       = 'Modern gradient design with vibrant colors.';
        $this->categories        = [ 'Purple Collection' ];
        $this->template_settings = [
            'text_link_color' => '#EFEDF3',
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
                    'background_color'                   => '#5d19e0',
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
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'      => [
                                                'top'    => 10,
                                                'right'  => 10,
                                                'bottom' => 10,
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#f9f9f900',
                                            'logo_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'logo_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Logo settings',
                                                'description' => 'Handle logo settings',
                                            ],
                                            'src'          => 'https://images.wpbrandy.com/uploads/woo-white-logo.png',
                                            'align'        => 'left',
                                            'width'        => 117,
                                            'url'          => '#',
                                            'alt'          => '',
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
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#efedf3',
                                            'rich_text'   => '<p style="text-align: right"><span style="font-size: 14px;font-weight: 400">My Account</span></p>',
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
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#efedf3',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 14px;font-weight: 400">Order Tracking</span></p>',
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
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#efedf3',
                                            'rich_text'   => '<p style="text-align: right"><span style="font-size: 14px;font-weight: 400">Contact</span></p>',
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
                    'column_width'           => [],
                    'column_spacing'         => '0',
                    'vertical_align'         => 'top',
                    'padding'                => [
                        'top'    => '15',
                        'right'  => 50,
                        'bottom' => '15',
                        'left'   => 50,
                    ],
                    'border_radius'          => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'background_color'       => '#5d19e0',
                    'background_image'       => [
                        'url'        => '',
                        'position'   => 'default',
                        'x_position' => 0,
                        'y_position' => 0,
                        'repeat'     => 'default',
                        'size'       => 'default',
                    ],
                    'inner_border_radius'    => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'inner_background_color' => '#ffffff00',
                    'children'               => [
                        Column::get_object_data(
                            100,
                            [
                                'children' => [
                                    ColumnLayout::get_object_data(
                                        1,
                                        [
                                            'column_width' => [],
                                            'column_spacing' => '0',
                                            'vertical_align' => 'top',
                                            'padding'      => [
                                                'top'    => '15',
                                                'right'  => 50,
                                                'bottom' => '15',
                                                'left'   => 50,
                                            ],
                                            'border_radius' => [
                                                'top_left' => '0',
                                                'top_right' => '0',
                                                'bottom_left' => '0',
                                                'bottom_right' => '0',
                                            ],
                                            'background_color' => '#723af5',
                                            'background_image' => [
                                                'url'      => 'https://images.wpbrandy.com/uploads/processing-order-3-img-1.png',
                                                'position' => 'center-right',
                                                'x_position' => 100,
                                                'y_position' => 0,
                                                'repeat'   => 'no-repeat',
                                                'size'     => 'cover',
                                            ],
                                            'inner_border_radius' => [
                                                'top_left' => '0',
                                                'top_right' => '0',
                                                'bottom_left' => '0',
                                                'bottom_right' => '0',
                                            ],
                                            'inner_background_color' => '#ffffff00',
                                            'children'     => [
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
                                                                    'padding' => [
                                                                        'top' => 10,
                                                                        'right' => '50',
                                                                        'bottom' => 0,
                                                                        'left' => 0,
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
                                                                    'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                                    'text_color' => '#84ff7c',
                                                                    'rich_text' => '<p><span style="font-weight: bold">ORDER CONFIRMED</span></p>',
                                                                ]
                                                            ),
                                                            Heading::get_object_data(
                                                                [
                                                                    'container_group_definition' => [
                                                                        'component' => 'GroupDefinition',
                                                                        'title' => 'Container settings',
                                                                        'description' => 'Handle container layout settings',
                                                                    ],
                                                                    'padding' => [
                                                                        'top' => 20,
                                                                        'right' => 0,
                                                                        'bottom' => 0,
                                                                        'left' => 0,
                                                                    ],
                                                                    'background_color' => '#ffffff00',
                                                                    'content_breaker' => [
                                                                        'component' => 'LineBreaker',
                                                                    ],
                                                                    'content_group_definition' => [
                                                                        'component' => 'GroupDefinition',
                                                                        'title' => 'Content settings',
                                                                        'description' => 'Handle content settings',
                                                                    ],
                                                                    'font_family' => '"Courier New", Courier, monospace',
                                                                    'text_color' => '#efedf3',
                                                                    'rich_text' => '<p><span style="font-size: 30px;font-weight: 600;line-height: 1.2">New Order Received</span></p><p><span style="font-size: 30px;font-weight: 600;line-height: 1.2">Please Review</span></p>',
                                                                ]
                                                            ),
                                                            Text::get_object_data(
                                                                [
                                                                    'container_group_definition' => [
                                                                        'component' => 'GroupDefinition',
                                                                        'title' => 'Container settings',
                                                                        'description' => 'Handle container layout settings',
                                                                    ],
                                                                    'padding' => [
                                                                        'top' => '15',
                                                                        'right' => 0,
                                                                        'bottom' => '15',
                                                                        'left' => 0,
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
                                                                    'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                                    'text_color' => '#efedf3',
                                                                    'rich_text' => '<p style="text-align: left">Order placed on [yaymail_order_date]</p><p style="text-align: left">Order ID #[yaymail_order_id is_plain="true"]</p>',
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
            Text::get_object_data(
                [
                    'container_group_definition' => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Container settings',
                        'description' => 'Handle container layout settings',
                    ],
                    'padding'                    => [
                        'top'    => '15',
                        'right'  => '50',
                        'bottom' => 0,
                        'left'   => '50',
                    ],
                    'background_color'           => '#5d19e0',
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
                    'font_family'                => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                    'text_color'                 => '#efedf3',
                    'rich_text'                  => '<p><span style="font-size: 16px;font-weight: 400">Hi [yaymail_billing_first_name] [yaymail_billing_last_name]<strong>,</strong></span></p><br/><p><span style="font-size: 16px">Thanks for your order!</span></p><p><span style="font-size: 16px">We\'ve successfully received it and it\'s now being processed. We\'ll notify you as soon as your order is shipped.</span></p>',
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
                        'top'    => 40,
                        'right'  => '50',
                        'bottom' => 0,
                        'left'   => '50',
                    ],
                    'background_color'           => '#5d19e0',
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
                    'font_family'                => '"Courier New", Courier, monospace',
                    'text_color'                 => '#efedf3',
                    'rich_text'                  => '<p style="text-align: left"><span style="font-size: 24px;font-weight: 800"><strong>Order Summary</strong></span></p>',
                ]
            ),
            OrderDetails::get_object_data(
                [
                    'rich_text'                      => '[yaymail_order_details]',
                    'payment_instructions'           => '[yaymail_payment_instructions]',
                    'container_group_definition'     => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Container settings',
                        'description' => 'Handle container layout settings',
                    ],
                    'padding'                        => [
                        'top'    => 0,
                        'right'  => '50',
                        'bottom' => 10,
                        'left'   => '50',
                    ],
                    'background_color'               => '#5d19e0',
                    'border'                         => [
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
                    'table_setting_breaker'          => [
                        'component' => 'LineBreaker',
                    ],
                    'table_group_definition'         => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Table settings',
                        'description' => 'Handle table settings',
                    ],
                    'layout_type'                    => 'legacy',
                    'title'                          => '',
                    'title_color'                    => '#873EFF',
                    'text_color'                     => '#efedf3',
                    'border_color'                   => '#efedf3',
                    'font_family'                    => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                    'table_content_font_size'        => 16,
                    'table_heading_line_breaker'     => [
                        'component' => 'LineBreaker',
                    ],
                    'table_heading_group_definition' => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Table heading settings',
                        'description' => 'Handle table heading settings',
                    ],
                    'table_heading_font_size'        => 14,
                    'show_table_header'              => 1,
                    'product_title'                  => 'Product',
                    'cost_title'                     => 'Cost',
                    'quantity_title'                 => 'Quantity',
                    'price_title'                    => 'Price',
                    'cart_subtotal_title'            => 'Subtotal:',
                    'payment_method_title'           => 'Payment method:',
                    'order_total_title'              => 'Total:',
                    'order_note_title'               => 'Note:',
                    'shipping_title'                 => 'Shipping: [yaymail_shipping_method]',
                    'discount_title'                 => 'Discount:',
                    'custom_footer_rows_breaker'     => [
                        'component' => 'LineBreaker',
                    ],
                    'custom_footer_rows_group'       => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Custom Footer Rows',
                        'description' => 'Add custom rows to the order totals footer',
                    ],
                    'custom_footer_rows'             => [],
                ]
            ),
            Button::get_object_data(
                [
                    'container_group_definition'      => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Container settings',
                        'description' => 'Handle container layout settings',
                    ],
                    'padding'                         => [
                        'top'    => 10,
                        'right'  => '50',
                        'bottom' => 15,
                        'left'   => '50',
                    ],
                    'background_color'                => '#5d19e0',
                    'button_setting_breaker'          => [
                        'component' => 'LineBreaker',
                    ],
                    'button_group_definition'         => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Button settings',
                        'description' => 'Handle button settings',
                    ],
                    'button_type'                     => 'default',
                    'align'                           => 'left',
                    'width'                           => '50%',
                    'custom_width'                    => '50',
                    'height'                          => '21',
                    'button_padding'                  => [
                        'top'    => '12',
                        'right'  => '20',
                        'bottom' => '12',
                        'left'   => '20',
                    ],
                    'button_background_color'         => '#84ff7c',
                    'text_color'                      => '#3d1889',
                    'border'                          => [
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
                    'border_radius'                   => [
                        'top_left'     => 0,
                        'top_right'    => 0,
                        'bottom_right' => 0,
                        'bottom_left'  => 0,
                    ],
                    'button_content_group_definition' => [
                        'component'   => 'GroupDefinition',
                        'title'       => 'Button content',
                        'description' => 'Handle button content',
                    ],
                    'text'                            => 'View Your Order',
                    'url'                             => '[yaymail_order_url]',
                    'font_size'                       => 16,
                    'weight'                          => 'normal',
                    'font_family'                     => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                ]
            ),
            ColumnLayout::get_object_data(
                2,
                [
                    'column_width'                       => [],
                    'column_spacing'                     => 20,
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
                    'background_color'                   => '#5d19e0',
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
                                            'padding'     => [
                                                'top'    => 15,
                                                'right'  => 0,
                                                'bottom' => 0,
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#0000000d',
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
                                            'font_family' => '"Courier New", Courier, monospace',
                                            'text_color'  => '#efedf3',
                                            'rich_text'   => '<p style="text-align: center"><strong style="font-size: 20px;text-align: center">Shipping Address</strong></p>',
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
                                                'right'  => 30,
                                                'bottom' => 30,
                                                'left'   => 30,
                                            ],
                                            'background_color' => '#0000000d',
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'layout_type'  => 'modern',
                                            'title_color'  => '#1a1a1a',
                                            'text_color'   => '#efedf3',
                                            'border_color' => '#c7ccc7',
                                            'font_family'  => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
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
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => 0,
                                                'bottom' => 0,
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#0000000d',
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
                                            'font_family' => '"Courier New", Courier, monospace',
                                            'text_color'  => '#efedf3',
                                            'rich_text'   => '<p style="text-align: center"><strong style="font-size: 20px;text-align: center">Billing Address</strong></p>',
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
                                                'right'  => 30,
                                                'bottom' => 30,
                                                'left'   => 30,
                                            ],
                                            'background_color' => '#0000000d',
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'layout_type'  => 'modern',
                                            'title_color'  => '#1a1a1a',
                                            'text_color'   => '#efedf3',
                                            'border_color' => '#23661b33',
                                            'font_family'  => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
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
            Divider::get_object_data(
                [
                    'align'            => 'center',
                    'padding'          => [
                        'top'    => '15',
                        'right'  => '50',
                        'bottom' => '15',
                        'left'   => '50',
                    ],
                    'width'            => '100',
                    'height'           => 1,
                    'background_color' => '#5d19e0',
                    'divider_color'    => '#723af5',
                    'divider_type'     => 'solid',
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
                    'background_color'           => '#5d19e0',
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
                    'font_family'                => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                    'text_color'                 => '#efedf3',
                    'rich_text'                  => '<p><span style="font-size: 16px">Thanks again for shopping with us!</span></p><p><span style="font-size: 16px">Have any questions? Feel free to contact us anytime <u>here</u>. We\'re happy to help.</span></p>',
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
                        'bottom' => 30,
                        'left'   => 50,
                    ],
                    'background_color'           => '#5d19e0',
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
                    'font_family'                => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                    'text_color'                 => '#efedf3',
                    'rich_text'                  => '<p><span style="font-size: 16px;font-weight: 400">Best regards,</span></p><p><span style="font-size: 16px;font-weight: 400">The <strong>[yaymail_site_name]</strong> Team</span></p>',
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
                        'top'    => 30,
                        'bottom' => 30,
                        'left'   => 30,
                        'right'  => 30,
                    ],
                    'border_radius'                      => [
                        'top_left'     => '0',
                        'top_right'    => '0',
                        'bottom_left'  => '0',
                        'bottom_right' => '0',
                    ],
                    'background_color'                   => '#723af5',
                    'background_image'                   => [
                        'url'         => '',
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
                                    SocialIcon::get_object_data(
                                        [
                                            'container_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Container settings',
                                                'description' => 'Handle container layout settings',
                                            ],
                                            'padding'    => [
                                                'top'    => 10,
                                                'right'  => 50,
                                                'bottom' => 10,
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#ffffff00',
                                            'align'      => 'center',
                                            'content_breaker' => [
                                                'component' => 'LineBreaker',
                                            ],
                                            'content_group_definition' => [
                                                'component' => 'GroupDefinition',
                                                'title' => 'Content settings',
                                                'description' => 'Handle content settings',
                                            ],
                                            'width_icon' => 36,
                                            'spacing'    => 25,
                                            'theme'      => 'Colorful',
                                            'icon_list'  => [
                                                '0' => [
                                                    'icon' => 'facebook',
                                                    'url'  => '#',
                                                ],
                                                '1' => [
                                                    'icon' => 'instagram',
                                                    'url'  => '#',
                                                ],
                                                '2' => [
                                                    'icon' => 'tiktok',
                                                    'url'  => '#',
                                                ],
                                                '3' => [
                                                    'icon' => 'youtube',
                                                    'url'  => '#',
                                                ],
                                            ],
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
                                            'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#ffffff',
                                            'rich_text'   => '<p style="margin: 0px;font-weight: 400;text-align: center"><span style="font-size: 12px">© 2025 YayCommerce.com</span></p>',
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
