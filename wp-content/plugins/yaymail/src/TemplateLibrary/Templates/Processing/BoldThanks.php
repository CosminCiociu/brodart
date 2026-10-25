<?php

namespace YayMail\TemplateLibrary\Templates\Processing;

use YayMail\Abstracts\BaseTemplate;
use YayMail\Elements\BillingAddress;
use YayMail\Elements\Button;
use YayMail\Elements\Column;
use YayMail\Elements\ColumnLayout;
use YayMail\Elements\Heading;
use YayMail\Elements\Image;
use YayMail\Elements\Logo;
use YayMail\Elements\OrderDetails;
use YayMail\Elements\ShippingAddress;
use YayMail\Elements\SocialIcon;
use YayMail\Elements\Text;
use YayMail\Utils\SingletonTrait;

/**
 * Bold Thanks template for Processing Order email.
 */
class BoldThanks extends BaseTemplate {
    use SingletonTrait;

    public function __construct() {
        parent::__construct();
        $this->id                = 'processing_order_v5';
        $this->email_type        = 'customer_processing_order';
        $this->name              = 'Bold Thanks';
        $this->description       = 'Large, attention-grabbing thank-you hero.';
        $this->categories        = [ 'Purple Collection' ];
        $this->template_settings = [
            'text_link_color' => '#5D19E0',
        ];
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
                                            'background_color' => '#5D19E000',
                                            'src'     => 'https://images.wpbrandy.com/uploads/woo-white-logo.png',
                                            'align'   => 'left',
                                            'width'   => '117',
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
                                            'background_color' => '#723af5',
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#EFEDF3',
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
                                            'background_color' => '#723af5',
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#EFEDF3',
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
                                            'background_color' => '#5D19E000',
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#EFEDF3',
                                            'rich_text'   => '<p style="text-align: right"><span style="font-size: 14px;font-weight: 400">Contact</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '15',
                        'right'  => '50',
                        'bottom' => '15',
                        'left'   => '50',
                    ],
                    'background_color' => '#723af5',
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
                                            'children' => [
                                                Column::get_object_data(
                                                    58,
                                                    [
                                                        'children' => [
                                                            Text::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => 15,
                                                                        'right' => '10',
                                                                        'bottom' => 0,
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#C6F6D500',
                                                                    'font_family' => 'Arial, Helvetica, sans-serif',
                                                                    'text_color' => '#5d19e0',
                                                                    'rich_text' => '<p><span style="font-size: 14px;font-weight: bold">ORDER CONFIRMED</span></p>',
                                                                ]
                                                            ),
                                                            Heading::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => 10,
                                                                        'right' => 0,
                                                                        'bottom' => 10,
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#C6F6D500',
                                                                    'font_family' => '"Arial Black", Gadget, sans-serif',
                                                                    'text_color' => '#5d19e0',
                                                                    'rich_text' => '<p style="font-size: 30px;font-weight: 900; line-height: 1.2 !important;"><span style="line-height: 1.2 !important;">We\'ve Received</span><br /><span style="line-height: 1.2 !important;">Your Order</span></p>',
                                                                ]
                                                            ),
                                                            Text::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => '0',
                                                                        'right' => '10',
                                                                        'bottom' => '0',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#C6F6D500',
                                                                    'font_family' => 'Arial, Helvetica, sans-serif',
                                                                    'text_color' => '#5d19e0',
                                                                    'rich_text' => '<p><span style="font-size: 14px">Order placed on [yaymail_order_date]</span></p><p><span style="font-size: 14px">Number ID: #[yaymail_order_id is_plain="true"]</span></p>',
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                                Column::get_object_data(
                                                    42,
                                                    [
                                                        'children' => [
                                                            Image::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => '0',
                                                                        'right' => '0',
                                                                        'bottom' => '0',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#C6F6D500',
                                                                    'src' => 'https://images.wpbrandy.com/uploads/processing-5-img-2.png',
                                                                    'width' => 648,
                                                                    'alt' => 'Shopping cart illustration',
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                            ],
                                            'vertical_align' => 'middle',
                                            'padding'  => [
                                                'top'    => 0,
                                                'right'  => 0,
                                                'bottom' => 20,
                                                'left'   => 30,
                                            ],
                                            'border_radius' => [
                                                'top_left' => 0,
                                                'top_right' => 0,
                                                'bottom_left' => null,
                                                'bottom_right' => 0,
                                            ],
                                            'background_color' => '#bdedea',
                                            'background_image' => [
                                                'url'      => '',
                                                'position' => 'top_right',
                                                'x_position' => 84,
                                                'y_position' => -26,
                                                'repeat'   => 'no-repeat',
                                                'size'     => 'contain',
                                            ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => 0,
                        'right'  => '40',
                        'bottom' => '0',
                        'left'   => '40',
                    ],
                    'background_color' => '#723af5',
                ]
            ),
            Text::get_object_data(
                [
                    'padding'          => [
                        'top'    => '32',
                        'right'  => '50',
                        'bottom' => 0,
                        'left'   => '50',
                    ],
                    'background_color' => '#723af5',
                    'font_family'      => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                    'text_color'       => '#ffffff',
                    'rich_text'        => '<p style="text-align: center"><span style="font-size: 24px">Hi <strong>[yaymail_billing_first_name],</strong></span></p>',
                ]
            ),
            Text::get_object_data(
                [
                    'padding'          => [
                        'top'    => 0,
                        'right'  => '50',
                        'bottom' => 0,
                        'left'   => 50,
                    ],
                    'background_color' => '#723af5',
                    'font_family'      => '"Arial Black", Gadget, sans-serif',
                    'text_color'       => '#ffffff',
                    'rich_text'        => '<p style="text-align: center"><span style="font-size: 80px;font-weight: 800; line-height: normal">THANKS FOR YOUR ORDER!</span></p>',
                ]
            ),
            Text::get_object_data(
                [
                    'padding'          => [
                        'top'    => 0,
                        'right'  => '50',
                        'bottom' => '24',
                        'left'   => 50,
                    ],
                    'background_color' => '#723af5',
                    'font_family'      => 'Arial, Helvetica, sans-serif',
                    'text_color'       => '#ffffff',
                    'rich_text'        => '<p style="text-align: center"><span style="font-size: 56px;font-weight: bold; line-height: normal">WE HAVE SUCCESSFULLY RECEIVED IT AND IT\'S NOW BEING PROCESSED.</span></p>',
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
                                                                    'padding'      => [
                                                                        'top'    => '0',
                                                                        'right'  => '0',
                                                                        'bottom' => '8',
                                                                        'left'   => '0',
                                                                    ],
                                                                    'background_color' => '#bdedea00',
                                                                    'layout_type'  => 'modern',
                                                                    'title'        => '<span style="font-size: 20px;font-weight: 800">Order Summary</span>',
                                                                    'title_color'  => '#5d19e0',
                                                                    'text_color'   => '#5d19e0',
                                                                    'border_color' => '#5d19e033',
                                                                    'font_family'  => 'Arial, Helvetica, sans-serif',
                                                                    'table_heading_font_size' => '12',
                                                                    'product_title' => 'PRODUCT',
                                                                    'quantity_title' => 'QUANTITY',
                                                                    'price_title'  => 'PRICE',
                                                                ]
                                                            ),
                                                            Button::get_object_data(
                                                                [
                                                                    'padding'     => [
                                                                        'top'    => '0',
                                                                        'right'  => '0',
                                                                        'bottom' => '0',
                                                                        'left'   => '0',
                                                                    ],
                                                                    'background_color' => '#ffffff',
                                                                    'width'       => '100%',
                                                                    'height'      => 27,
                                                                    'button_background_color' => '#7B42F6',
                                                                    'border_radius' => [
                                                                        'top_left' => 0,
                                                                        'top_right' => 0,
                                                                        'bottom_right' => 0,
                                                                        'bottom_left' => 0,
                                                                    ],
                                                                    'text'        => 'View Your Order',
                                                                    'url'         => '[yaymail_order_url]',
                                                                    'font_size'   => '16',
                                                                    'weight'      => '600',
                                                                    'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                            ],
                                            'padding'          => [
                                                'top'    => '24',
                                                'right'  => '24',
                                                'bottom' => '24',
                                                'left'   => '24',
                                            ],
                                            'background_color' => '#bdedea',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '0',
                        'right'  => '40',
                        'bottom' => '24',
                        'left'   => '40',
                    ],
                    'background_color' => '#723af5',
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
                                    Text::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => 15,
                                                'right'  => 0,
                                                'bottom' => 0,
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#bdedea',
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#5d19e0',
                                            'rich_text'   => '<p style="text-align: center"><strong style="font-size: 20px;text-align: center">Shipping Address</strong></p>',
                                        ]
                                    ),
                                    ShippingAddress::get_object_data(
                                        [
                                            'padding'      => [
                                                'top'    => 0,
                                                'right'  => 30,
                                                'bottom' => 30,
                                                'left'   => 30,
                                            ],
                                            'background_color' => '#bdedea',
                                            'layout_type'  => 'modern',
                                            'title_color'  => '#1a1a1a',
                                            'text_color'   => '#5d19e0',
                                            'border_color' => '#c7ccc7',
                                            'font_family'  => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'title'        => '',
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
                                    Text::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => 0,
                                                'bottom' => 0,
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#bdedea',
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#5d19e0',
                                            'rich_text'   => '<p style="text-align: center"><strong style="font-size: 20px;text-align: center">Billing Address</strong></p>',
                                        ]
                                    ),
                                    BillingAddress::get_object_data(
                                        [
                                            'padding'      => [
                                                'top'    => 0,
                                                'right'  => 30,
                                                'bottom' => 30,
                                                'left'   => 30,
                                            ],
                                            'background_color' => '#bdedea',
                                            'layout_type'  => 'modern',
                                            'title_color'  => '#1a1a1a',
                                            'text_color'   => '#5d19e0',
                                            'border_color' => '#23661b33',
                                            'font_family'  => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'title'        => '',
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
                        'top'    => 30,
                        'right'  => 50,
                        'bottom' => 30,
                        'left'   => 50,
                    ],
                    'background_color' => '#723af5',
                ]
            ),
            Text::get_object_data(
                [
                    'padding'          => [
                        'top'    => 50,
                        'right'  => '50',
                        'bottom' => '24',
                        'left'   => '50',
                    ],
                    'background_color' => '#723af5',
                    'font_family'      => 'Arial, Helvetica, sans-serif',
                    'text_color'       => '#ffffff',
                    'rich_text'        => '<p style="text-align: left"><span style="font-size: 18px">Thanks again for shopping with us!</span></p>
                        <p style="text-align: left"><span style="font-size: 18px">Your order will be shipped within 24-48 hours.</span></p>
                        <p style="text-align: left"><span style="font-size: 18px">Got questions? Check out our <u>Returns</u> &amp; <u>Refunds Policy</u> or contact <u>Here</u> for support. We\'re happy to help!</span></p>
                        <p style="text-align: left"><span style="font-size: 18px">Best regards,<br />The <strong>[yaymail_site_name]</strong> Team</span></p>',
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
                                            'background_color' => '#5f19e000',
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
                                            'background_color' => '#8049ff',
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#efedf3',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 12px">© 2024 WooCommerce</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => 40,
                        'right'  => '50',
                        'bottom' => 40,
                        'left'   => '50',
                    ],
                    'background_color' => '#8049ff',
                ]
            ),
        ];
    }
}
