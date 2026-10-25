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
 * Cozy Paws template for Processing Order email.
 */
class CozyPaws extends BaseTemplate {
    use SingletonTrait;

    public function __construct() {
        parent::__construct();
        $this->id            = 'processing_order_v11';
        $this->email_type    = 'customer_processing_order';
        $this->name          = 'Cozy Paws';
        $this->description   = 'Warm and welcoming pet store design.';
        $this->categories    = [ 'Pets Collection' ];
        $this->access        = 'free';
        $this->modified_date = '2026-07-21';
        $this->elements      = [
            ColumnLayout::get_object_data(
                4,
                [
                    'children'         => [
                        Column::get_object_data(
                            29,
                            [
                                'children' => [
                                    Logo::get_object_data(
                                        [
                                            'padding' => [
                                                'top'    => '10',
                                                'right'  => '10',
                                                'bottom' => '10',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#FDE6C100',
                                            'src'     => 'https://images.wpbrandy.com/uploads/woo-black-logo.png',
                                            'align'   => 'left',
                                            'width'   => '116',
                                            'alt'     => 'Store logo',
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
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => '0',
                                                'bottom' => '15',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#FDE6C100',
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#2f2d29',
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
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => '0',
                                                'bottom' => '15',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#FDE6C100',
                                            'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#3D2B1F',
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
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => '2',
                                                'bottom' => '15',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#FDE6C100',
                                            'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#3D2B1F',
                                            'rich_text'   => '<p style="text-align: right"><span style="font-size: 14px;font-weight: 400">Contact</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '20',
                        'right'  => '50',
                        'bottom' => '20',
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
                                            'children'         => [
                                                Column::get_object_data(
                                                    100,
                                                    [
                                                        'children' => [
                                                            Text::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => '0',
                                                                        'right' => '16',
                                                                        'bottom' => '0',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#3D2B1F00',
                                                                    'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                                    'text_color' => '#ffffff',
                                                                    'rich_text' => '<p><span style="font-size: 12px;font-weight: 600">ORDER CONFIRMED</span></p>',
                                                                ]
                                                            ),
                                                            Heading::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => '8',
                                                                        'right' => '16',
                                                                        'bottom' => '8',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#3D2B1F00',
                                                                    'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                                    'rich_text' => '<h1 style="font-size: 32px;font-weight: bold;line-height: 1.2 !important;color: #ffffff;margin: 0">We\'ve Received</h1>
<h1 style="font-size: 32px;font-weight: bold;line-height: 1.2 !important;color: #ffffff;margin: 0">Your Order</h1>',
                                                                ]
                                                            ),
                                                            Text::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => '0',
                                                                        'right' => '16',
                                                                        'bottom' => '0',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#3D2B1F00',
                                                                    'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                                    'text_color' => '#ffffff',
                                                                    'rich_text' => '<p><span style="font-size: 14px">Order placed on [yaymail_order_date]</span></p><p><span style="font-size: 14px">Number ID #[yaymail_order_id is_plain="true"]</span></p>',
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                            ],
                                            'padding'          => [
                                                'top'    => 30,
                                                'right'  => 50,
                                                'bottom' => 30,
                                                'left'   => 50,
                                            ],
                                            'background_image' => [
                                                'url'        => 'https://images.wpbrandy.com/uploads/processing-11-bg-1.png',
                                                'position'   => 'custom',
                                                'x_position' => 0,
                                                'y_position' => 0,
                                                'repeat'     => 'no-repeat',
                                                'size'       => 'cover',
                                            ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '0',
                        'right'  => 50,
                        'bottom' => '24',
                        'left'   => 50,
                    ],
                    'background_color' => '#ffe1b0',
                ]
            ),
            Text::get_object_data(
                [
                    'padding'          => [
                        'top'    => '8',
                        'right'  => '50',
                        'bottom' => '16',
                        'left'   => '50',
                    ],
                    'background_color' => '#ffe1b0',
                    'font_family'      => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                    'text_color'       => '#3D2B1F',
                    'rich_text'        => '<p><span style="font-size: 16px;font-weight: 700">Hi <strong>[yaymail_billing_first_name]</strong>,</span></p><p><span style="font-size: 16px">We have successfully received it and it\'s now being processed. So, your order will be shipped within 24–48 hours.</span></p>',
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
                                            'children'         => [
                                                Column::get_object_data(
                                                    100,
                                                    [
                                                        'children' => [
                                                            OrderDetails::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => '0',
                                                                        'right' => '0',
                                                                        'bottom' => '8',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#fffae7',
                                                                    'layout_type' => 'modern',
                                                                    'title'   => '<span style="font-size: 20px;font-weight: 700">Order Summary</span>',
                                                                    'title_color' => '#3d2d21',
                                                                    'text_color' => '#3d2d21',
                                                                    'border_color' => '#8259391f',
                                                                    'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                                    'table_heading_font_size' => '12',
                                                                    'product_title' => 'PRODUCT',
                                                                    'cost_title' => 'PRICE',
                                                                    'quantity_title' => 'QUANTITY',
                                                                    'price_title' => 'TOTAL',
                                                                ]
                                                            ),
                                                            Button::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => '8',
                                                                        'right' => '0',
                                                                        'bottom' => '16',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#fffae7',
                                                                    'width' => '100%',
                                                                    'button_background_color' => '#825939',
                                                                    'border_radius' => [
                                                                        'top_left' => 0,
                                                                        'top_right' => 0,
                                                                        'bottom_right' => 0,
                                                                        'bottom_left' => 0,
                                                                    ],
                                                                    'text' => 'View Your Order',
                                                                    'url'  => '[yaymail_order_url]',
                                                                    'font_size' => '16',
                                                                    'weight' => '500',
                                                                    'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                            ],
                                            'padding'          => [
                                                'top'    => '24',
                                                'right'  => '24',
                                                'bottom' => '8',
                                                'left'   => '24',
                                            ],
                                            'border_radius'    => [
                                                'top_left'     => 0,
                                                'top_right'    => 0,
                                                'bottom_left'  => 0,
                                                'bottom_right' => 0,
                                            ],
                                            'background_color' => '#fffae7',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '0',
                        'right'  => 50,
                        'bottom' => '24',
                        'left'   => 50,
                    ],
                    'background_color' => '#ffe1b0',
                ]
            ),
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
                                            'padding'     => [
                                                'top'    => 30,
                                                'right'  => 0,
                                                'bottom' => 30,
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#fffae7',
                                            'layout_type' => 'modern',
                                            'title_color' => '#3d2d21',
                                            'text_color'  => '#3d2d21',
                                            'font_family' => 'Helvetica,Roboto,Arial,sans-serif',
                                            'title'       => '<p style="text-align: center"><span style="font-size: 20px;font-weight: 600">Shipping Address</span></p>',
                                            'shipping_content_alignment' => 'center',
                                            'shipping_content_text_format' => [
                                                'bold'   => false,
                                                'italic' => false,
                                                'underline' => false,
                                            ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                        Column::get_object_data(
                            50,
                            [
                                'children' => [
                                    BillingAddress::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => 30,
                                                'right'  => 0,
                                                'bottom' => 30,
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#fffae7',
                                            'layout_type' => 'modern',
                                            'title_color' => '#3d2d21',
                                            'text_color'  => '#3d2d21',
                                            'font_family' => 'Helvetica,Roboto,Arial,sans-serif',
                                            'title'       => '<p style="text-align: center"><span style="font-size: 20px;font-weight: 600">Billing Address</span></p>',
                                            'billing_content_alignment' => 'center',
                                            'billing_content_text_format' => [
                                                'bold'   => false,
                                                'italic' => false,
                                                'underline' => false,
                                            ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'column_spacing'   => 20,
                    'padding'          => [
                        'top'    => '15',
                        'right'  => 50,
                        'bottom' => '15',
                        'left'   => 50,
                    ],
                    'background_color' => '#ffe1b0',
                ]
            ),
            Divider::get_object_data(
                [
                    'height'           => 1,
                    'background_color' => '#ffe1b0',
                    'divider_color'    => '#8259391f',
                ]
            ),
            Text::get_object_data(
                [
                    'padding'          => [
                        'top'    => '8',
                        'right'  => '50',
                        'bottom' => '8',
                        'left'   => '50',
                    ],
                    'background_color' => '#ffe1b0',
                    'font_family'      => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                    'text_color'       => '#3D2B1F',
                    'rich_text'        => '<p><span style="font-size: 16px">Thanks again for shopping with us!</span></p><p><span style="font-size: 16px">Got questions? Check out our <u>Returns</u> &amp; <u>Refunds Policy</u> or contact <u>Here</u> for support. We\'re happy to help!</span></p>',
                ]
            ),
            Text::get_object_data(
                [
                    'padding'          => [
                        'top'    => '0',
                        'right'  => '50',
                        'bottom' => '32',
                        'left'   => '50',
                    ],
                    'background_color' => '#ffe1b0',
                    'font_family'      => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                    'text_color'       => '#3D2B1F',
                    'rich_text'        => '<p><span style="font-size: 16px">Best regards,</span></p><p><span style="font-size: 16px">The <strong>[yaymail_site_name]</strong> Team</span></p>',
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
                                    SocialIcon::get_object_data(
                                        [
                                            'padding'    => [
                                                'top'    => '0',
                                                'right'  => '0',
                                                'bottom' => '16',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#3D2B1F',
                                            'width_icon' => '32',
                                            'spacing'    => '12',
                                            'icon_list'  => [
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
                                            'padding'     => [
                                                'top'    => '0',
                                                'right'  => '0',
                                                'bottom' => '0',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#3D2B1F',
                                            'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#ffffff',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 12px">© 2026 WooCommerce</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '32',
                        'right'  => '50',
                        'bottom' => '32',
                        'left'   => '50',
                    ],
                    'background_color' => '#3d2d21',
                ]
            ),
        ];
    }
}
