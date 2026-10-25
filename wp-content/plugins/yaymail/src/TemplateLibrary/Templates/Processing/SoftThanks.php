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
use YayMail\Elements\OrderProgress;
use YayMail\Elements\ShippingAddress;
use YayMail\Elements\SocialIcon;
use YayMail\Elements\Text;
use YayMail\Utils\SingletonTrait;

/**
 * Soft Thanks template for Processing Order email.
 */
class SoftThanks extends BaseTemplate {
    use SingletonTrait;

    public function __construct() {
        parent::__construct();
        $this->id                = 'processing_order_v13';
        $this->email_type        = 'customer_processing_order';
        $this->name              = 'Soft Thanks';
        $this->description       = 'Clean and friendly thank-you layout.';
        $this->categories        = [ 'Pets Collection', 'Progress collection' ];
        $this->template_settings = [
            'text_link_color' => '#3D2D21',
        ];
        $this->modified_date     = '2026-07-21';
        $this->access            = 'free';
        $this->elements          = [
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
                                            'background_color' => '#FCF5E500',
                                            'src'     => 'https://images.wpbrandy.com/uploads/woo-brown-logo.png',
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
                                            'background_color' => '#FCF5E500',
                                            'font_family' => '"Comic Sans MS", cursive, sans-serif',
                                            'text_color'  => '#0d0d25',
                                            'rich_text'   => '<p style="text-align: right"><span style="font-size: 14px;font-weight: 600">My Account</span></p>',
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
                                            'background_color' => '#FCF5E500',
                                            'font_family' => '"Comic Sans MS", cursive, sans-serif',
                                            'text_color'  => '#0d0d25',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 14px;font-weight: 600">Order Tracking</span></p>',
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
                                            'background_color' => '#FCF5E500',
                                            'font_family' => '"Comic Sans MS", cursive, sans-serif',
                                            'text_color'  => '#0d0d25',
                                            'rich_text'   => '<p style="text-align: right"><span style="font-size: 14px;font-weight: 600">Contact</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '20',
                        'right'  => '50',
                        'bottom' => 10,
                        'left'   => '50',
                    ],
                    'background_color' => '#fff4d9',
                ]
            ),
            Divider::get_object_data(
                [
                    'padding'          => [
                        'top'    => 5,
                        'right'  => 0,
                        'bottom' => 5,
                        'left'   => 0,
                    ],
                    'height'           => 1,
                    'background_color' => '#fff4d9',
                    'divider_color'    => '#f1e5c9',
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
                                    Text::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => '0',
                                                'right'  => '0',
                                                'bottom' => '16',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#FCF5E500',
                                            'font_family' => '"Comic Sans MS", cursive, sans-serif',
                                            'text_color'  => '#0d0d25',
                                            'rich_text'   => '<p style="text-align: center"><span style="background-color: #ffffff;font-size: 13px;padding: 6px 16px;border-radius: 20px;display: inline-block;font-weight: 600">Order Number: #[yaymail_order_id is_plain="true"]</span></p>',
                                        ]
                                    ),
                                    Heading::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => '0',
                                                'right'  => '0',
                                                'bottom' => '24',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#FCF5E500',
                                            'font_family' => '"Comic Sans MS", cursive, sans-serif',
                                            'text_color'  => '#0D0D25',
                                            'rich_text'   => '<p style="font-size: 40px;font-weight: bold;text-align: center;margin: 0;"><span style="font-size: 48px; line-height: normal">Yay! We\'ve Received</span></p>
                                            <p style="font-size: 40px;font-weight: bold;text-align: center;margin: 0"><span style="font-size: 48px; line-height: normal">Your Order</span></p>',
                                        ]
                                    ),
                                    ColumnLayout::get_object_data(
                                        1,
                                        [
                                            'children' => [
                                                Column::get_object_data(
                                                    100,
                                                    [
                                                        'children' => [
                                                            OrderProgress::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => '20',
                                                                        'right' => '24',
                                                                        'bottom' => '20',
                                                                        'left' => '24',
                                                                    ],
                                                                    'background_color' => '#825939',
                                                                    'display_style' => 'filled_bar',
                                                                    'connector_height' => '3',
                                                                    'connector_active_color' => '#b4896833',
                                                                    'connector_inactive_color' => '#b4896833',
                                                                    'label_active_color' => '#ffffff',
                                                                    'label_inactive_color' => '#ffffff',
                                                                    'icon_size' => 17,
                                                                    'label_font_size' => '12',
                                                                    'font_family' => '"Comic Sans MS", cursive, sans-serif',
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
                                                                            'image_bg_color' => '#7B523300',
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
                                            'padding'  => [
                                                'top'    => 0,
                                                'right'  => '0',
                                                'bottom' => 0,
                                                'left'   => '0',
                                            ],
                                            'border_radius' => [
                                                'top_left' => 10,
                                                'top_right' => 10,
                                                'bottom_left' => 10,
                                                'bottom_right' => 10,
                                            ],
                                            'background_color' => '#825939',
                                            'inner_border_radius' => [
                                                'top_left' => 10,
                                                'top_right' => 10,
                                                'bottom_left' => 10,
                                                'bottom_right' => 10,
                                            ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => 20,
                        'right'  => 30,
                        'bottom' => 20,
                        'left'   => 30,
                    ],
                    'background_color' => '#fff4d9',
                    'background_image' => [
                        'url'         => 'https://images.wpbrandy.com/uploads/processing-12-img-1-1.png',
                        'position'    => 'top_center',
                        'x_position'  => 0,
                        'y_position'  => 0,
                        'repeat'      => 'no-repeat',
                        'size'        => 'custom',
                        'custom_size' => 90,
                    ],
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
                                    Text::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => 10,
                                                'right'  => '50',
                                                'bottom' => 10,
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#fff4d900',
                                            'font_family' => '"Comic Sans MS", cursive, sans-serif',
                                            'text_color'  => '#1A1B35',
                                            'rich_text'   => '<p><span style="font-size: 16px;font-weight: bold">Hi <strong>[yaymail_billing_first_name]</strong>,</span></p>
                                                <br>
                                                <p><span style="font-size: 16px">Thanks for your purchase! We have received your order #[yaymail_order_id is_plain="true"] and your order will be shipped within 24–48 hours.</span></p>
                                                <br>
                                                <p><span style="font-size: 16px">Best regards,<br />The <strong>[yaymail_site_name]</strong> Team</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'background_color' => '#fff4d9',
                    'background_image' => [
                        'url'         => 'https://images.wpbrandy.com/uploads/processing-12-img-2.png',
                        'position'    => 'custom',
                        'x_position'  => -36,
                        'y_position'  => -100,
                        'repeat'      => 'no-repeat',
                        'size'        => 'custom',
                        'custom_size' => 94,
                    ],
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
                                    OrderDetails::get_object_data(
                                        [
                                            'padding'      => [
                                                'top'    => 20,
                                                'right'  => 20,
                                                'bottom' => 10,
                                                'left'   => 20,
                                            ],
                                            'background_color' => '#fffae7',
                                            'layout_type'  => 'modern',
                                            'title'        => '<span style="font-size: 20px;font-weight: 700">Order Summary</span>',
                                            'title_color'  => '#3d2d21',
                                            'text_color'   => '#3d2d21',
                                            'border_color' => '#E8D5C4',
                                            'font_family'  => '"Comic Sans MS", cursive, sans-serif',
                                            'table_heading_font_size' => '12',
                                        ]
                                    ),
                                    Button::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => 10,
                                                'right'  => 20,
                                                'bottom' => 30,
                                                'left'   => 20,
                                            ],
                                            'background_color' => '#fffae7',
                                            'width'       => '100%',
                                            'button_background_color' => '#825939',
                                            'border_radius' => [
                                                'top_left' => '12',
                                                'top_right' => '12',
                                                'bottom_right' => '12',
                                                'bottom_left' => '12',
                                            ],
                                            'text'        => 'View Your Order',
                                            'url'         => '[yaymail_order_url]',
                                            'font_size'   => '16',
                                            'weight'      => '700',
                                            'font_family' => '"Comic Sans MS", cursive, sans-serif',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => 0,
                        'right'  => 50,
                        'bottom' => '8',
                        'left'   => 50,
                    ],
                    'border_radius'    => [
                        'top_left'     => 0,
                        'top_right'    => 0,
                        'bottom_left'  => 0,
                        'bottom_right' => 0,
                    ],
                    'background_color' => '#fff4d9',
                    'background_image' => [
                        'url'        => '',
                        'position'   => 'custom',
                        'x_position' => 0,
                        'y_position' => 100,
                        'repeat'     => 'no-repeat',
                        'size'       => 'contain',
                    ],
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
                                                'top'    => '15',
                                                'right'  => 0,
                                                'bottom' => '15',
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#fffae7',
                                            'layout_type' => 'modern',
                                            'title_color' => '#3d2d21',
                                            'text_color'  => '#3d2d21',
                                            'font_family' => '"Comic Sans MS", cursive, sans-serif',
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
                                                'top'    => '15',
                                                'right'  => 0,
                                                'bottom' => '15',
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#fffae7',
                                            'layout_type' => 'modern',
                                            'title_color' => '#3d2d21',
                                            'text_color'  => '#3d2d21',
                                            'font_family' => '"Comic Sans MS", cursive, sans-serif',
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
                        'top'    => 15,
                        'right'  => 50,
                        'bottom' => 30,
                        'left'   => 50,
                    ],
                    'background_color' => '#fff4d9',
                    'background_image' => [
                        'url'         => 'https://images.wpbrandy.com/uploads/processing-12-img-3.png',
                        'position'    => 'custom',
                        'x_position'  => 100,
                        'y_position'  => 69,
                        'repeat'      => 'no-repeat',
                        'size'        => 'cover',
                        'custom_size' => 100,
                    ],
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
                                    Text::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => '0',
                                                'right'  => '0',
                                                'bottom' => '20',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#825939',
                                            'font_family' => '"Comic Sans MS", cursive, sans-serif',
                                            'text_color'  => '#fef8ea',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 14px">For questions, contact <u>hi@yourwoostore.com</u>, visit our <u>FAQs</u>, or <u>chat</u> with us during operating hours for account support</span></p>',
                                        ]
                                    ),
                                    Divider::get_object_data(
                                        [
                                            'padding' => [
                                                'top'    => 5,
                                                'right'  => 0,
                                                'bottom' => 5,
                                                'left'   => 0,
                                            ],
                                            'height'  => 1,
                                            'background_color' => '#825939',
                                            'divider_color' => '#ffffff1a',
                                        ]
                                    ),
                                    ColumnLayout::get_object_data(
                                        2,
                                        [
                                            'children' => [
                                                Column::get_object_data(
                                                    50,
                                                    [
                                                        'children' => [
                                                            Text::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => '0',
                                                                        'right' => '0',
                                                                        'bottom' => 10,
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#7B523300',
                                                                    'font_family' => '"Comic Sans MS", cursive, sans-serif',
                                                                    'text_color' => '#fef8ea',
                                                                    'rich_text' => '<p style="text-align: left"><span style="font-size: 12px">© 2025 WooCommerce</span></p>',
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
                                                                    'padding' => [
                                                                        'top'  => '0',
                                                                        'right' => '0',
                                                                        'bottom' => '0',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#7B523300',
                                                                    'align'   => 'right',
                                                                    'width_icon' => '28',
                                                                    'spacing' => '10',
                                                                    'icon_list' => [
                                                                        [
                                                                            'icon' => 'facebook',
                                                                            'url' => '#',
                                                                        ],
                                                                        [
                                                                            'icon' => 'instagram',
                                                                            'url' => '#',
                                                                        ],
                                                                        [
                                                                            'icon' => 'tiktok',
                                                                            'url' => '#',
                                                                        ],
                                                                        [
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
                                            'vertical_align' => 'middle',
                                            'padding'  => [
                                                'top'    => 10,
                                                'right'  => '0',
                                                'bottom' => '0',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#7B523300',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '32',
                        'right'  => '50',
                        'bottom' => '24',
                        'left'   => '50',
                    ],
                    'background_color' => '#825939',
                ]
            ),
        ];
    }
}
