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
 * Clean Check template for Processing Order email.
 */
class CleanCheck extends BaseTemplate {
    use SingletonTrait;

    public function __construct() {
        parent::__construct();
        $this->id                = 'processing_order_v6';
        $this->email_type        = 'customer_processing_order';
        $this->name              = 'Clean Check';
        $this->description       = 'Minimal layout with a success checkmark.';
        $this->categories        = [ 'Purple Collection' ];
        $this->template_settings = [
            'text_link_color' => '#1A1A1A',
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
                                            'background_color' => '#E8EFF800',
                                            'src'     => 'https://images.wpbrandy.com/uploads/woocommerce-logo.png',
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
                                            'background_color' => '#E8EFF800',
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#333439',
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
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => '0',
                                                'bottom' => '15',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#E8EFF800',
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#333439',
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
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => '2',
                                                'bottom' => '15',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#E8EFF800',
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#333439',
                                            'rich_text'   => '<div style="text-align: right"><span style="font-size: 14px;font-weight: 500">Contact</span></div>',
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
                    'background_color' => '#edf2ff',
                ]
            ),
            Divider::get_object_data(
                [
                    'padding'          => [
                        'top'    => '1',
                        'right'  => '0',
                        'bottom' => '1',
                        'left'   => '0',
                    ],
                    'height'           => '1',
                    'background_color' => '#dfe8ff',
                    'divider_color'    => '#C5B8E033',
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
                                    Image::get_object_data(
                                        [
                                            'padding' => [
                                                'top'    => '0',
                                                'right'  => '50',
                                                'bottom' => '15',
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#E8EFF800',
                                            'src'     => 'https://images.wpbrandy.com/uploads/processing-icon-1.png',
                                            'width'   => '114',
                                            'alt'     => 'Order confirmed',
                                        ]
                                    ),
                                    Heading::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => '0',
                                                'right'  => '50',
                                                'bottom' => '12',
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#E8EFF800',
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#1A1A1A',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 48px;font-weight: 800; line-height: normal">New Order Received</span></p>
<p style="text-align: center"><span style="font-size: 48px;font-weight: 800; line-height: normal">Please Review</span></p>',
                                        ]
                                    ),
                                    Text::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => '0',
                                                'right'  => '50',
                                                'bottom' => '20',
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#E8EFF800',
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#333439',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 16px">Order placed on [yaymail_order_date]</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '30',
                        'right'  => '0',
                        'bottom' => '0',
                        'left'   => '0',
                    ],
                    'background_color' => '#edf2ff',
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
                                                'top'    => '15',
                                                'right'  => '50',
                                                'bottom' => '0',
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#EDE7F600',
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#333439',
                                            'rich_text'   => '<p><span style="font-size: 18px">Hi [yaymail_billing_first_name] [yaymail_billing_last_name]<strong>,</strong></span></p><br /><p><span style="font-size: 18px">Thanks for your order!</span></p><p><span style="font-size: 18px">We\'ve successfully received it and it\'s now being processed. Your order will be shipped within 24-48 hours.</span></p>',
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
                                                                        'right' => '50',
                                                                        'bottom' => '0',
                                                                        'left' => '50',
                                                                    ],
                                                                    'background_color' => '#EDE7F600',
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color' => '#1A1A1A',
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
                                                                    'padding' => [
                                                                        'top'  => '0',
                                                                        'right' => '50',
                                                                        'bottom' => '0',
                                                                        'left' => '50',
                                                                    ],
                                                                    'background_color' => '#EDE7F600',
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color' => '#1A1A1A',
                                                                    'rich_text' => '<div style="text-align: right"><strong>Number ID: #[yaymail_order_id is_plain="true"]</strong></div>',
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                            ],
                                            'vertical_align' => 'middle',
                                            'padding'  => [
                                                'top'    => '15',
                                                'right'  => '0',
                                                'bottom' => '1',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#EDE7F600',
                                        ]
                                    ),
                                    OrderDetails::get_object_data(
                                        [
                                            'padding'      => [
                                                'top'    => '0',
                                                'right'  => '50',
                                                'bottom' => '10',
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#EDE7F600',
                                            'layout_type'  => 'legacy',
                                            'title'        => '',
                                            'title_color'  => '#1A1A1A',
                                            'text_color'   => '#333439',
                                            'border_color' => '#C5B8E0',
                                            'font_family'  => '"Outfit", "DM Sans", sans-serif',
                                            'table_content_font_size' => '16',
                                            'product_title' => 'PRODUCT',
                                            'quantity_title' => 'QUANTITY',
                                            'price_title'  => 'PRICE',
                                        ]
                                    ),
                                    Button::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => '10',
                                                'right'  => '50',
                                                'bottom' => '15',
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#EDE7F600',
                                            'width'       => '100%',
                                            'button_background_color' => '#5d19e0',
                                            'border_radius' => [
                                                'top_left' => 0,
                                                'top_right' => 0,
                                                'bottom_right' => 0,
                                                'bottom_left' => 0,
                                            ],
                                            'text'        => 'View Order Detail',
                                            'url'         => '[yaymail_order_url]',
                                            'font_size'   => '16',
                                            'weight'      => '600',
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '15',
                        'right'  => '0',
                        'bottom' => '50',
                        'left'   => '0',
                    ],
                    'background_color' => '#edf2ff',
                    'background_image' => [
                        'url'      => 'https://images.wpbrandy.com/uploads/processing-6-img-1.png',
                        'position' => 'default',
                        'size'     => 'cover',
                        'repeat'   => 'default',
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
                                    BillingAddress::get_object_data(
                                        [
                                            'padding'      => [
                                                'top'    => '15',
                                                'right'  => 0,
                                                'bottom' => '15',
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#edf2ff00',
                                            'layout_type'  => 'legacy',
                                            'text_color'   => '#1a1a1a',
                                            'border_color' => '#1a1a1a33',
                                            'font_family'  => 'Helvetica,Roboto,Arial,sans-serif',
                                            'title'        => '<div style="text-align: center"><span style="color: #1a1a1a;font-size: 20px;font-weight: 800;text-align: center;background-color: #edf2ff">BILLING ADDRESS</span></div>',
                                            'billing_content_font_size' => 14,
                                            'billing_content_alignment' => 'center',
                                            'billing_content_text_format' => [
                                                'bold'   => false,
                                                'italic' => false,
                                                'underline' => false,
                                            ],
                                        ]
                                    ),
                                ],
                                'border'   => [
                                    'side'   => 'none',
                                    'width'  => '1',
                                    'style'  => 'solid',
                                    'color'  => '#1a1a1a33',
                                    'custom' => [
                                        'top'    => 0,
                                        'right'  => '1',
                                        'bottom' => '1',
                                        'left'   => 0,
                                    ],
                                ],
                            ]
                        ),
                        Column::get_object_data(
                            50,
                            [
                                'children' => [
                                    ShippingAddress::get_object_data(
                                        [
                                            'padding'      => [
                                                'top'    => '15',
                                                'right'  => 0,
                                                'bottom' => '15',
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#ffffff00',
                                            'layout_type'  => 'legacy',
                                            'text_color'   => '#1a1a1a',
                                            'border_color' => '#1a1a1a33',
                                            'font_family'  => 'Helvetica,Roboto,Arial,sans-serif',
                                            'title'        => '<div style="text-align: center"><span style="color: #1a1a1a;font-size: 20px;font-weight: 800;text-align: center;background-color: #edf2ff">SHIPPING ADDRESS</span></div>',
                                            'shipping_content_alignment' => 'center',
                                            'shipping_content_text_format' => [
                                                'bold'   => false,
                                                'italic' => false,
                                                'underline' => false,
                                            ],
                                        ]
                                    ),
                                ],
                                'border'   => [
                                    'side'   => 'none',
                                    'width'  => '1',
                                    'style'  => 'solid',
                                    'color'  => '#1a1a1a33',
                                    'custom' => [
                                        'top'    => '1',
                                        'right'  => '1',
                                        'bottom' => '1',
                                        'left'   => 0,
                                    ],
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '15',
                        'right'  => 50,
                        'bottom' => '15',
                        'left'   => 50,
                    ],
                    'background_color' => '#edf2ff',
                ]
            ),
            Text::get_object_data(
                [
                    'padding'          => [
                        'top'    => '20',
                        'right'  => '50',
                        'bottom' => '24',
                        'left'   => '50',
                    ],
                    'background_color' => '#edf2ff',
                    'font_family'      => '"Outfit", "DM Sans", sans-serif',
                    'text_color'       => '#333439',
                    'rich_text'        => '<p><span style="font-size: 16px;font-weight: 400">Thanks again for shopping with us!</span></p><p><span style="font-size: 16px;font-weight: 400">Got questions? Check out our <u>Returns</u> &amp; <u>Refunds Policy</u> or contact <u>Here</u> for support. We\'re happy to help!</span></p>',
                ]
            ),
            Text::get_object_data(
                [
                    'padding'          => [
                        'top'    => '0',
                        'right'  => '50',
                        'bottom' => '50',
                        'left'   => '50',
                    ],
                    'background_color' => '#edf2ff',
                    'font_family'      => '"Outfit", "DM Sans", sans-serif',
                    'text_color'       => '#333439',
                    'rich_text'        => '<p><span style="font-size: 16px;font-weight: 400">Best regards,</span></p><p><span style="font-size: 16px;font-weight: 400">The [yaymail_site_name] Team</span></p>',
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
                                                    50,
                                                    [
                                                        'children' => [
                                                            Text::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => '0',
                                                                        'right' => '50',
                                                                        'bottom' => '0',
                                                                        'left' => '50',
                                                                    ],
                                                                    'background_color' => '#E8EFF800',
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color' => '#1a1a1a',
                                                                    'rich_text' => '<p style="text-align: left"><span style="font-size: 12px">© 2026 WooCommerce</span></p>',
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
                                                                        'right' => '50',
                                                                        'bottom' => '0',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#E8EFF800',
                                                                    'align'   => 'right',
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
                                                'top'    => '10',
                                                'right'  => '0',
                                                'bottom' => '15',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#E8EFF800',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => '35',
                        'right'  => '0',
                        'bottom' => '10',
                        'left'   => '0',
                    ],
                    'background_color' => '#edf2ff',
                    'background_image' => [
                        'url'      => 'https://images.wpbrandy.com/uploads/processing-6-img-2.png',
                        'position' => 'default',
                        'size'     => 'cover',
                        'repeat'   => 'default',
                    ],
                ]
            ),
        ];
    }
}
