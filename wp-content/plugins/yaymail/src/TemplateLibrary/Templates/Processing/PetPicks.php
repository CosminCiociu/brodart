<?php

namespace YayMail\TemplateLibrary\Templates\Processing;

use YayMail\Abstracts\BaseTemplate;
use YayMail\Elements\BillingAddress;
use YayMail\Elements\Button;
use YayMail\Elements\Column;
use YayMail\Elements\ColumnLayout;
use YayMail\Elements\CrossUpSellsProducts;
use YayMail\Elements\Divider;
use YayMail\Elements\Heading;
use YayMail\Elements\Logo;
use YayMail\Elements\OrderDetails;
use YayMail\Elements\OrderProgress;
use YayMail\Elements\ShippingAddress;
use YayMail\Elements\SocialIcon;
use YayMail\Elements\Text;
use YayMail\Utils\SingletonTrait;

/**
 * Pet Picks template for Processing Order email.
 */
class PetPicks extends BaseTemplate {
    use SingletonTrait;

    public function __construct() {
        parent::__construct();
        $this->id                = 'processing_order_v12';
        $this->email_type        = 'customer_processing_order';
        $this->name              = 'Pet Picks';
        $this->description       = 'Showcase recommended pet products.';
        $this->categories        = [ 'Pets Collection', 'Progress collection' ];
        $this->template_settings = [
            'text_link_color' => '#FFFAE7',
        ];
        $this->access            = 'free';
        $this->modified_date     = '2026-07-21';
        $this->elements          = [
            Logo::get_object_data(
                [
                    'padding'          => [
                        'top'    => 30,
                        'right'  => '50',
                        'bottom' => 15,
                        'left'   => '50',
                    ],
                    'background_color' => '#ffe1b0',
                    'src'              => 'https://images.wpbrandy.com/uploads/woo-brown-logo.png',
                    'width'            => '116',
                    'alt'              => 'Store logo',
                ]
            ),
            ColumnLayout::get_object_data(
                3,
                [
                    'children'         => [
                        Column::get_object_data(
                            39.33,
                            [
                                'children' => [
                                    Text::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => '8',
                                                'right'  => '0',
                                                'bottom' => '8',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#F9EBD700',
                                            'font_family' => 'Georgia, serif',
                                            'text_color'  => '#080808',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 14px;font-weight: 400">My Account</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                        Column::get_object_data(
                            21.33,
                            [
                                'children' => [
                                    Text::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => '8',
                                                'right'  => '0',
                                                'bottom' => '8',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#F9EBD700',
                                            'font_family' => 'Georgia, serif',
                                            'text_color'  => '#080808',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 14px;font-weight: 400">Order Tracking</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                        Column::get_object_data(
                            39.34,
                            [
                                'children' => [
                                    Text::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => '8',
                                                'right'  => '0',
                                                'bottom' => '8',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#F9EBD700',
                                            'font_family' => 'Georgia, serif',
                                            'text_color'  => '#080808',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 14px;font-weight: 400">Contact Us</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '0',
                        'right'  => '50',
                        'bottom' => 10,
                        'left'   => '50',
                    ],
                    'background_color' => '#ffe1b0',
                ]
            ),
            ColumnLayout::get_object_data(
                1,
                [
                    'children'         => [
                        Column::get_object_data(
                            100,
                            [
                                'children' => [
                                    ColumnLayout::get_object_data(
                                        1,
                                        [
                                'children'            => [
                                    Column::get_object_data(
                                        100,
                                        [
                                            'children' => [
                                                Text::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => '0',
                                                            'right' => '0',
                                                            'bottom' => '8',
                                                            'left' => 50,
                                                        ],
                                                        'background_color' => '#70543E00',
                                                        'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                        'text_color' => '#ffffff',
                                                        'rich_text' => '<p><span style="font-size: 14px;font-weight: 500">ORDER CONFIRMED</span></p>',
                                                    ]
                                                ),
                                                Heading::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => '0',
                                                            'right' => '0',
                                                            'bottom' => '12',
                                                            'left' => 50,
                                                        ],
                                                        'background_color' => '#70543E00',
                                                        'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                        'rich_text' => '<p style="font-size: 36px; margin: 0;"><span style="font-weight: bold; line-height: 1.15 !important;">We\'ve Received</span><br \><span style="font-weight: bold; line-height: 1.15 !important;">Your Order</span></p>',
                                                    ]
                                                ),
                                                Text::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => '0',
                                                            'right' => '0',
                                                            'bottom' => '0',
                                                            'left' => 50,
                                                        ],
                                                        'background_color' => '#70543E00',
                                                        'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                        'text_color' => '#ffffff',
                                                        'rich_text' => '<p><span style="font-size: 14px">Order placed on [yaymail_order_date]</span></p><p><span style="font-size: 14px">Number ID #[yaymail_order_id is_plain="true"]</span></p>',
                                                    ]
                                                ),
                                            ],
                                        ]
                                    ),
                                ],
                                'padding'             => [
                                    'top'    => 40,
                                    'right'  => '0',
                                    'bottom' => 40,
                                    'left'   => '0',
                                ],
                                'border_radius'       => [
                                    'top_left'     => 10,
                                    'top_right'    => 10,
                                    'bottom_left'  => 10,
                                    'bottom_right' => 10,
                                ],
                                'background_color'    => '#ffe1b0',
                                'background_image'    => [
                                    'url'        => 'https://images.wpbrandy.com/uploads/processing-12-bg-1.png',
                                    'position'   => 'center_right',
                                    'x_position' => 0,
                                    'y_position' => 0,
                                    'repeat'     => 'no-repeat',
                                    'size'       => 'cover',
                                ],
                                'inner_border_radius' => [
                                    'top_left'     => 0,
                                    'top_right'    => 0,
                                    'bottom_left'  => 0,
                                    'bottom_right' => 0,
                                ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '15',
                        'right'  => 20,
                        'bottom' => '15',
                        'left'   => 20,
                    ],
                    'background_color' => '#ffe1b0',
                ]
            ),
            ColumnLayout::get_object_data(
                1,
                [
                    'children'         => [
                        Column::get_object_data(
                            100,
                            [
                                'children' => [
                                    ColumnLayout::get_object_data(
                                        1,
                                        [
                                'children'            => [
                                    Column::get_object_data(
                                        100,
                                        [
                                            'children' => [
                                                OrderProgress::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => 0,
                                                            'right' => '0',
                                                            'bottom' => 0,
                                                            'left' => '0',
                                                        ],
                                                        'background_color' => '#70543E00',
                                                        'display_style' => 'filled_bar',
                                                        'connector_height' => '3',
                                                        'connector_active_color' => '#b4896833',
                                                        'connector_inactive_color' => '#b4896833',
                                                        'label_active_color' => '#ffffff',
                                                        'label_inactive_color' => '#ffffff',
                                                        'icon_size' => 24,
                                                        'label_font_size' => '12',
                                                        'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                        'steps'   => [
                                                            [
                                                                'title' => 'Ordered',
                                                                'image_url' => 'https://images.wpbrandy.com/uploads/brown-check.png',
                                                                'image_bg_color' => '#fffae7',
                                                                'icon_border_color' => '#fffae7',
                                                                'icon_border_style' => 'solid',
                                                                'icon_border_width' => 2,
                                                            ],
                                                            [
                                                                'title' => 'Processing',
                                                                'image_url' => 'https://images.wpbrandy.com/uploads/brown-dots.png',
                                                                'image_bg_color' => '#b48968',
                                                                'icon_border_color' => '#b48968',
                                                                'icon_border_style' => 'solid',
                                                                'icon_border_width' => 2,
                                                            ],
                                                            [
                                                                'title' => 'Completed',
                                                                'image_url' => 'https://images.wpbrandy.com/uploads/brown-circle.png',
                                                                'image_bg_color' => '#70543E00',
                                                                'icon_border_color' => '#b48968',
                                                                'icon_border_style' => 'dashed',
                                                                'icon_border_width' => 3,
                                                            ],
                                                        ],
                                                    ]
                                                ),
                                            ],
                                        ]
                                    ),
                                ],
                                'padding'             => [
                                    'top'    => 30,
                                    'right'  => 20,
                                    'bottom' => 30,
                                    'left'   => 20,
                                ],
                                'border_radius'       => [
                                    'top_left'     => 10,
                                    'top_right'    => 10,
                                    'bottom_left'  => 10,
                                    'bottom_right' => 10,
                                ],
                                'background_color'    => '#825939',
                                'background_image'    => [
                                    'url'        => '',
                                    'position'   => 'center_right',
                                    'x_position' => 0,
                                    'y_position' => 0,
                                    'repeat'     => 'no-repeat',
                                    'size'       => 'cover',
                                ],
                                'inner_border_radius' => [
                                    'top_left'     => 0,
                                    'top_right'    => 0,
                                    'bottom_left'  => 0,
                                    'bottom_right' => 0,
                                ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '15',
                        'right'  => 20,
                        'bottom' => '15',
                        'left'   => 20,
                    ],
                    'background_color' => '#ffe1b0',
                ]
            ),
            ColumnLayout::get_object_data(
                1,
                [
                    'children'         => [
                        Column::get_object_data(
                            100,
                            [
                                'children' => [
                                    ColumnLayout::get_object_data(
                                        1,
                                        [
                                'children'            => [
                                    Column::get_object_data(
                                        100,
                                        [
                                            'children' => [
                                                Text::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => '0',
                                                            'right' => '0',
                                                            'bottom' => '16',
                                                            'left' => '0',
                                                        ],
                                                        'background_color' => '#825939',
                                                        'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                        'text_color' => '#fffae7',
                                                        'rich_text' => '<p><span style="font-size: 16px;font-weight: 600">Hi <strong>[yaymail_billing_first_name]</strong>,</span></p>
                                                            <br>
                                                            <p><span style="font-size: 16px">Thanks for your purchase! We have received your order #[yaymail_order_id is_plain="true"] and your order will be shipped within 24–48 hours.</span></p>
                                                            <br>
                                                            <p><span style="font-size: 16px">Best regards,<br />The <strong>[yaymail_site_name]</strong> Team</span></p>',
                                                    ]
                                                ),
                                                Divider::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => '15',
                                                            'right' => 0,
                                                            'bottom' => '15',
                                                            'left' => 0,
                                                        ],
                                                        'height'  => 1,
                                                        'background_color' => '#825939',
                                                        'divider_color' => '#b48968',
                                                    ]
                                                ),
                                                OrderDetails::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => 15,
                                                            'right' => '0',
                                                            'bottom' => '16',
                                                            'left' => '0',
                                                        ],
                                                        'background_color' => '#70543E00',
                                                        'layout_type' => 'modern',
                                                        'title'   => '<span style="font-size: 24px;font-weight: 700">Order Summary</span>',
                                                        'title_color' => '#fffae7',
                                                        'text_color' => '#fffae7',
                                                        'border_color' => '#fffae71f',
                                                        'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                        'table_heading_font_size' => '12',
                                                    ]
                                                ),
                                                Button::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => 20,
                                                            'right' => '0',
                                                            'bottom' => 0,
                                                            'left' => '0',
                                                        ],
                                                        'background_color' => '#70543E00',
                                                        'width' => '100%',
                                                        'button_background_color' => '#ffe1b0',
                                                        'text_color' => '#825939',
                                                        'border_radius' => [
                                                            'top_left' => '8',
                                                            'top_right' => '8',
                                                            'bottom_left' => '8',
                                                            'bottom_right' => '8',
                                                        ],
                                                        'text' => 'View Your Order',
                                                        'url'  => '[yaymail_order_url]',
                                                        'font_size' => '16',
                                                        'weight' => '600',
                                                        'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                    ]
                                                ),
                                            ],
                                        ]
                                    ),
                                ],
                                'padding'             => [
                                    'top'    => 30,
                                    'right'  => 20,
                                    'bottom' => 30,
                                    'left'   => 20,
                                ],
                                'border_radius'       => [
                                    'top_left'     => 10,
                                    'top_right'    => 10,
                                    'bottom_left'  => 10,
                                    'bottom_right' => 10,
                                ],
                                'background_color'    => '#825939',
                                'background_image'    => [
                                    'url'        => '',
                                    'position'   => 'center_right',
                                    'x_position' => 0,
                                    'y_position' => 0,
                                    'repeat'     => 'no-repeat',
                                    'size'       => 'cover',
                                ],
                                'inner_border_radius' => [
                                    'top_left'     => 0,
                                    'top_right'    => 0,
                                    'bottom_left'  => 0,
                                    'bottom_right' => 0,
                                ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '15',
                        'right'  => 20,
                        'bottom' => '15',
                        'left'   => 20,
                    ],
                    'background_color' => '#ffe1b0',
                ]
            ),
            ColumnLayout::get_object_data(
                1,
                [
                    'children'         => [
                        Column::get_object_data(
                            100,
                            [
                                'children' => [
                                    ColumnLayout::get_object_data(
                                        2,
                                        [
                                'children'         => [
                                    Column::get_object_data(
                                        50,
                                        [
                                            'children' => [
                                                ShippingAddress::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => 17,
                                                            'right' => 0,
                                                            'bottom' => '15',
                                                            'left' => 0,
                                                        ],
                                                        'background_color' => '#825939',
                                                        'layout_type' => 'modern',
                                                        'title_color' => '#fffae7',
                                                        'text_color' => '#fffae7',
                                                        'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                        'title'   => '<p style="text-align: center"><span style="font-size: 20px;font-weight: 600">Shipping Address</span></p>',
                                                        'shipping_content_alignment' => 'center',
                                                        'shipping_content_text_format' => [
                                                            'bold' => false,
                                                            'italic' => false,
                                                            'underline' => false,
                                                        ],
                                                    ]
                                                ),
                                            ],
                                            'border'   => [
                                                'side'   => 'right',
                                                'width'  => '1',
                                                'style'  => 'solid',
                                                'color'  => '#fffae71f',
                                                'custom' => [
                                                    'top'  => '1',
                                                    'right' => '1',
                                                    'bottom' => '1',
                                                    'left' => '1',
                                                ],
                                            ],
                                        ]
                                    ),
                                    Column::get_object_data(
                                        50,
                                        [
                                            'children' => [
                                                BillingAddress::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => '15',
                                                            'right' => 0,
                                                            'bottom' => '15',
                                                            'left' => 0,
                                                        ],
                                                        'background_color' => '#825939',
                                                        'layout_type' => 'modern',
                                                        'title_color' => '#fffae7',
                                                        'text_color' => '#fffae7',
                                                        'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                        'title'   => '<p style="text-align: center"><span style="font-size: 20px;font-weight: 600">Billing Address</span></p>',
                                                        'billing_content_alignment' => 'center',
                                                        'billing_content_text_format' => [
                                                            'bold' => false,
                                                            'italic' => false,
                                                            'underline' => false,
                                                        ],
                                                    ]
                                                ),
                                            ],
                                        ]
                                    ),
                                ],
                                'border_radius'    => [
                                    'top_left'     => 10,
                                    'top_right'    => 10,
                                    'bottom_left'  => 10,
                                    'bottom_right' => 10,
                                ],
                                'background_color' => '#825939',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '15',
                        'right'  => 20,
                        'bottom' => '15',
                        'left'   => 20,
                    ],
                    'background_color' => '#ffe1b0',
                ]
            ),
            CrossUpSellsProducts::get_object_data(
                [
                    'padding'                => [
                        'top'    => 30,
                        'right'  => 0,
                        'bottom' => 0,
                        'left'   => 0,
                    ],
                    'background_color'       => '#ffe1b0',
                    'text_color'             => '#825939',
                    'font_family'            => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                    'showing_items'          => [
                        'top_content',
                        'product_image',
                        'product_name',
                        'product_price',
                        'product_original_price',
                    ],
                    'top_content'            => '<p style="text-align: center;"><span style="font-size: 20px; font-weight: 600; color: #70543e;">Maybe you also like it</span></p>
<p> </p>',
                    'sale_price_color'       => '#70543E',
                    'regular_price_color'    => '#9A8A7A',
                    'products_per_row'       => 2,
                    'max_products_displayed' => 4,
                ]
            ),
            ColumnLayout::get_object_data(
                1,
                [
                    'children'         => [
                        Column::get_object_data(
                            100,
                            [
                                'children' => [
                                    ColumnLayout::get_object_data(
                                        1,
                                        [
                                'children'            => [
                                    Column::get_object_data(
                                        100,
                                        [
                                            'children' => [
                                                Text::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => '0',
                                                            'right' => '0',
                                                            'bottom' => '24',
                                                            'left' => '0',
                                                        ],
                                                        'background_color' => '#825939',
                                                        'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                        'text_color' => '#ffffff',
                                                        'rich_text' => '<p style="text-align: center"><span style="font-size: 14px">For questions, contact <u>hi@yourwoostore.com</u>, visit our <u>FAQs</u>, or chat with us during operating hours for account support</span></p>',
                                                    ]
                                                ),
                                                SocialIcon::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => '0',
                                                            'right' => '0',
                                                            'bottom' => '16',
                                                            'left' => '0',
                                                        ],
                                                        'background_color' => '#825939',
                                                        'width_icon' => '32',
                                                        'spacing' => '12',
                                                        'icon_list' => [
                                                            [
                                                                'icon' => 'facebook',
                                                                'url'  => '#',
                                                            ],
                                                            [
                                                                'icon' => 'instagram',
                                                                'url'  => '#',
                                                            ],
                                                            [
                                                                'icon' => 'tiktok',
                                                                'url'  => '#',
                                                            ],
                                                            [
                                                                'icon' => 'youtube',
                                                                'url'  => '#',
                                                            ],
                                                        ],
                                                    ]
                                                ),
                                                Text::get_object_data(
                                                    [
                                                        'padding' => [
                                                            'top'  => '0',
                                                            'right' => '0',
                                                            'bottom' => '0',
                                                            'left' => '0',
                                                        ],
                                                        'background_color' => '#825939',
                                                        'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                        'text_color' => '#ffffff',
                                                        'rich_text' => '<p style="text-align: center"><span style="font-size: 12px">© 2026 WooCommerce</span></p>',
                                                    ]
                                                ),
                                            ],
                                        ]
                                    ),
                                ],
                                'padding'             => [
                                    'top'    => 30,
                                    'right'  => 20,
                                    'bottom' => 30,
                                    'left'   => 20,
                                ],
                                'border_radius'       => [
                                    'top_left'     => 10,
                                    'top_right'    => 10,
                                    'bottom_left'  => 10,
                                    'bottom_right' => 10,
                                ],
                                'background_color'    => '#825939',
                                'background_image'    => [
                                    'url'        => '',
                                    'position'   => 'center_right',
                                    'x_position' => 0,
                                    'y_position' => 0,
                                    'repeat'     => 'no-repeat',
                                    'size'       => 'cover',
                                ],
                                'inner_border_radius' => [
                                    'top_left'     => 0,
                                    'top_right'    => 0,
                                    'bottom_left'  => 0,
                                    'bottom_right' => 0,
                                ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '15',
                        'right'  => 20,
                        'bottom' => '15',
                        'left'   => 20,
                    ],
                    'background_color' => '#ffe1b0',
                ]
            ),
        ];
    }
}
