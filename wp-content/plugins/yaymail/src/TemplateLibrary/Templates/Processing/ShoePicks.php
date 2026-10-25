<?php

namespace YayMail\TemplateLibrary\Templates\Processing;

use YayMail\Abstracts\BaseTemplate;
use YayMail\Elements\BillingAddress;
use YayMail\Elements\Button;
use YayMail\Elements\Column;
use YayMail\Elements\ColumnLayout;
use YayMail\Elements\Divider;
use YayMail\Elements\FeaturedProducts;
use YayMail\Elements\Logo;
use YayMail\Elements\OrderDetails;
use YayMail\Elements\ShippingAddress;
use YayMail\Elements\SocialIcon;
use YayMail\Elements\Text;
use YayMail\Utils\SingletonTrait;

/**
 * Shoe Picks template for Processing Order email.
 */
class ShoePicks extends BaseTemplate {
    use SingletonTrait;

    public function __construct() {
        parent::__construct();
        $this->id                = 'processing_order_v7';
        $this->email_type        = 'customer_processing_order';
        $this->name              = 'Shoe Picks';
        $this->description       = 'Includes recommended shoes.';
        $this->categories        = [ 'Purple Collection', 'Shoes Collection' ];
        $this->template_settings = [
            'text_link_color' => '#1A1A1A',
        ];
        $this->modified_date     = '2026-07-21';
        $this->access            = 'free';

        $body_section = self::with_margin(
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
                                                'right'  => '24',
                                                'bottom' => '8',
                                                'left'   => '24',
                                            ],
                                            'background_color' => '#fffcf7',
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'text_color'  => '#333439',
                                            'rich_text'   => '<p><span style="font-size: 16px">Hi [yaymail_billing_first_name],</span></p>
                                                <p></p>
                                                <p><span style="font-size: 16px">Thanks for your purchase! We have received your order #[yaymail_order_id is_plain="true"] and your order will be shipped within 24–48 hours.</span></p>
                                                <p></p>
                                                <p><span style="font-size: 16px">Best regards,<br />The <strong>[yaymail_site_name]</strong> Team</span></p>',
                                        ]
                                    ),
                                    Divider::get_object_data(
                                        [
                                            'padding' => [
                                                'top'    => '15',
                                                'right'  => 24,
                                                'bottom' => '15',
                                                'left'   => 24,
                                            ],
                                            'height'  => 1,
                                            'background_color' => '#fffcf7',
                                            'divider_color' => '#000000',
                                        ]
                                    ),
                                    OrderDetails::get_object_data(
                                        [
                                            'padding'      => [
                                                'top'    => '8',
                                                'right'  => '24',
                                                'bottom' => '8',
                                                'left'   => '24',
                                            ],
                                            'background_color' => '#fffcf7',
                                            'border'       => [
                                                'side'  => 'none',
                                                'width' => '2',
                                                'style' => 'solid',
                                                'color' => '#000000',
                                            ],
                                            'layout_type'  => 'modern',
                                            'title'        => '<div><span style="font-size: 30px;font-weight: bold">Order Summary</span></div>',
                                            'title_color'  => '#1A1A1A',
                                            'text_color'   => '#333439',
                                            'border_color' => '#1a1a1a1f',
                                            'font_family'  => '"Outfit", "DM Sans", sans-serif',
                                            'table_heading_font_size' => '12',
                                            'product_title' => 'PRODUCT',
                                            'cost_title'   => 'COST',
                                            'quantity_title' => 'QUANTITY',
                                            'price_title'  => 'PRICE',
                                        ]
                                    ),
                                    Button::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => 10,
                                                'right'  => '24',
                                                'bottom' => 10,
                                                'left'   => '24',
                                            ],
                                            'background_color' => '#fffcf7',
                                            'width'       => '100%',
                                            'button_background_color' => '#9500ff',
                                            'border'      => [
                                                'side'  => 'all',
                                                'width' => '2',
                                                'style' => 'solid',
                                                'color' => '#9500ff',
                                            ],
                                            'border_radius' => [
                                                'top_left' => '0',
                                                'top_right' => '0',
                                                'bottom_right' => '0',
                                                'bottom_left' => '0',
                                            ],
                                            'text'        => 'View Your Order',
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
                        'bottom' => 20,
                        'left'   => '0',
                    ],
                    'background_color' => '#fffcf7',
                    'border'           => [
                        'side'  => 'all',
                        'width' => 4,
                        'style' => 'solid',
                        'color' => '#000000',
                    ],
                ]
            ),
            self::section_margin()
        );

        $address_section = self::with_margin(
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
                                            'background_color' => '#fffcf7',
                                            'layout_type' => 'modern',
                                            'title_color' => '#1a1a1a',
                                            'text_color'  => '#1a1a1a',
                                            'font_family' => 'Helvetica,Roboto,Arial,sans-serif',
                                            'title'       => '<div style="text-align: center"><span style="font-size: 20px;font-weight: 600">Shipping Address</span></div>',
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
                                    'side'   => 'right',
                                    'width'  => '1',
                                    'style'  => 'solid',
                                    'color'  => '#0000001f',
                                    'custom' => [
                                        'top'    => '1',
                                        'right'  => '1',
                                        'bottom' => '1',
                                        'left'   => '1',
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
                                            'padding'     => [
                                                'top'    => '15',
                                                'right'  => 0,
                                                'bottom' => '15',
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#fffcf7',
                                            'layout_type' => 'modern',
                                            'title_color' => '#1a1a1a',
                                            'text_color'  => '#1a1a1a',
                                            'font_family' => 'Helvetica,Roboto,Arial,sans-serif',
                                            'title'       => '<div style="text-align: center"><span style="font-size: 20px;font-weight: 600">Billing Address</span></div>',
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
                    'padding'          => [
                        'top'    => '15',
                        'right'  => 24,
                        'bottom' => '15',
                        'left'   => 24,
                    ],
                    'background_color' => '#fffcf7',
                    'border'           => [
                        'side'  => 'all',
                        'width' => 4,
                        'style' => 'solid',
                        'color' => '#000000',
                    ],
                ]
            ),
            self::section_margin()
        );

        $featured_section = self::with_margin(
            ColumnLayout::get_object_data(
                1,
                [
                    'children'         => [
                        Column::get_object_data(
                            100,
                            [
                                'children' => [
                                    FeaturedProducts::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => 10,
                                                'right'  => '24',
                                                'bottom' => 10,
                                                'left'   => '24',
                                            ],
                                            'background_color' => '#fffcf7',
                                            'text_color'  => '#333439',
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'showing_items' => [
                                                'top_content',
                                                'product_image',
                                                'product_name',
                                                'product_price',
                                                'buy_button',
                                            ],
                                            'top_content' => '<p style="text-align: center"><span style="font-size: 20px;font-weight: 800">Recommended For You</span></p>',
                                            'sale_price_color' => '#1A1A1A',
                                            'buy_button_label' => '',
                                            'buy_button_background_color' => '#ffffff',
                                            'buy_button_text_color' => '#1A1A1A',
                                            'products_per_row' => '2',
                                            'number_of_products' => 4,
                                        ]
                                    ),
                                    Button::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => 10,
                                                'right'  => '24',
                                                'bottom' => 10,
                                                'left'   => '24',
                                            ],
                                            'background_color' => '#fffcf7',
                                            'width'       => '100%',
                                            'button_background_color' => '#fffcf7',
                                            'text_color'  => '#1A1A1A',
                                            'border'      => [
                                                'side'  => 'all',
                                                'width' => '2',
                                                'style' => 'solid',
                                                'color' => '#1a1a1a',
                                            ],
                                            'border_radius' => [
                                                'top_left' => '0',
                                                'top_right' => '0',
                                                'bottom_right' => '0',
                                                'bottom_left' => '0',
                                            ],
                                            'text'        => 'View All Products',
                                            'font_size'   => '16',
                                            'weight'      => '600',
                                            'font_family' => '"Outfit", "DM Sans", sans-serif',
                                            'url'         => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ),
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'background_color' => '#fffcf7',
                    'border'           => [
                        'side'  => 'all',
                        'width' => 4,
                        'style' => 'solid',
                        'color' => '#000000',
                    ],
                ]
            ),
            self::section_margin()
        );

        $this->elements = [
            ColumnLayout::get_object_data(
                1,
                [
                    'children'         => [
                        Column::get_object_data(
                            100,
                            [
                                'children' => [
                                    ColumnLayout::get_object_data(
                                        4,
                                        [
                                            'children' => [
                                                Column::get_object_data(
                                                    29,
                                                    [
                                                        'children' => [
                                                            Logo::get_object_data(
                                                                [
                                                                    'padding' => [
                                                                        'top'  => '10',
                                                                        'right' => '10',
                                                                        'bottom' => '10',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#FDE8F400',
                                                                    'src' => 'https://images.wpbrandy.com/uploads/woocommerce-logo.png',
                                                                    'align' => 'left',
                                                                    'width' => '116',
                                                                    'alt' => 'Store logo',
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
                                                                    'padding' => [
                                                                        'top'  => '15',
                                                                        'right' => '0',
                                                                        'bottom' => '15',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#FDE8F400',
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color' => '#333439',
                                                                    'rich_text' => '<div style="text-align: right"><span style="font-size: 14px;font-weight: 400">My Account</span></div>',
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
                                                                    'padding' => [
                                                                        'top'  => '15',
                                                                        'right' => '0',
                                                                        'bottom' => '15',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#FDE8F400',
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color' => '#333439',
                                                                    'rich_text' => '<div style="text-align: center"><span style="font-size: 14px;font-weight: 400">Order Tracking</span></div>',
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
                                                                    'padding' => [
                                                                        'top'  => '15',
                                                                        'right' => '2',
                                                                        'bottom' => '15',
                                                                        'left' => '0',
                                                                    ],
                                                                    'background_color' => '#FDE8F400',
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color' => '#333439',
                                                                    'rich_text' => '<div style="text-align: right"><span style="font-size: 14px;font-weight: 400">Contact</span></div>',
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                            ],
                                            'padding'  => [
                                                'top'    => '20',
                                                'right'  => 0,
                                                'bottom' => '20',
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#fde8f400',
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
                                                            Text::get_object_data(
                                                                [
                                                                    'padding'     => [
                                                                        'top'    => 10,
                                                                        'right'  => '20',
                                                                        'bottom' => 0,
                                                                        'left'   => '20',
                                                                    ],
                                                                    'background_color' => '#FFFDF500',
                                                                    'border'      => [
                                                                        'side'  => 'none',
                                                                        'width' => '2',
                                                                        'style' => 'solid',
                                                                        'color' => '#000000',
                                                                    ],
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color'  => '#1A1A1A',
                                                                    'rich_text'   => '<p><span style="background-color: #f8d293;color: #1a1a1a;font-size: 12px;font-weight: bold;padding: 4px 10px;display: inline-block;border: 2px solid #000000">ORDER CONFIRMED</span></p>',
                                                                ]
                                                            ),
                                                            Text::get_object_data(
                                                                [
                                                                    'padding'     => [
                                                                        'top'    => 0,
                                                                        'right'  => '20',
                                                                        'bottom' => 0,
                                                                        'left'   => '20',
                                                                    ],
                                                                    'background_color' => '#FFFDF500',
                                                                    'border'      => [
                                                                        'side'  => 'none',
                                                                        'width' => '2',
                                                                        'style' => 'solid',
                                                                        'color' => '#000000',
                                                                    ],
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color'  => '#1A1A1A',
                                                                    'rich_text'   => '<h1 style="font-size: 36px;font-weight: 800;line-height: 1.1 !important;color: #1a1a1a;margin: 16px 0 12px"><span style="font-size: 64px;font-weight: 800">New Order Received</span></h1>',
                                                                ]
                                                            ),
                                                            Text::get_object_data(
                                                                [
                                                                    'padding'     => [
                                                                        'top'    => 0,
                                                                        'right'  => '20',
                                                                        'bottom' => 0,
                                                                        'left'   => '20',
                                                                    ],
                                                                    'background_color' => '#FFFDF500',
                                                                    'border'      => [
                                                                        'side'  => 'none',
                                                                        'width' => '2',
                                                                        'style' => 'solid',
                                                                        'color' => '#000000',
                                                                    ],
                                                                    'font_family' => '"Outfit", "DM Sans", sans-serif',
                                                                    'text_color'  => '#1A1A1A',
                                                                    'rich_text'   => '<p><span style="font-size: 14px;font-weight: 400">Order placed on [yaymail_order_date]</span></p>
                                                                    <p><span style="font-size: 14px;font-weight: 400">Number ID: #[yaymail_order_id is_plain="true"]</span></p>',
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
                                                'bottom' => 20,
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#fffaf3',
                                            'background_image' => [
                                                'url'      => 'https://images.wpbrandy.com/uploads/processing-7-img-2.png',
                                                'position' => 'top_right',
                                                'x_position' => 0,
                                                'y_position' => 0,
                                                'repeat'   => 'no-repeat',
                                                'size'     => 'cover',
                                                'custom_size' => 100,
                                            ],
                                            'border'   => [
                                                'side'  => 'all',
                                                'width' => '4',
                                                'style' => 'solid',
                                                'color' => '#000000',
                                            ],
                                        ]
                                    ),
                                    $body_section,
                                    $address_section,
                                    $featured_section,
                                ],
                            ]
                        ),
                    ],
                    'column_spacing'   => 0,
                    'padding'          => [
                        'top'    => 0,
                        'right'  => 50,
                        'bottom' => '15',
                        'left'   => 50,
                    ],
                    'background_image' => [
                        'url'        => 'https://images.wpbrandy.com/uploads/processing-7-img-1.png',
                        'position'   => 'default',
                        'x_position' => 0,
                        'y_position' => 0,
                        'repeat'     => 'default',
                        'size'       => 'default',
                    ],
                ]
            ),
            Text::get_object_data(
                [
                    'padding'          => [
                        'top'    => '32',
                        'right'  => '50',
                        'bottom' => '16',
                        'left'   => '50',
                    ],
                    'background_color' => '#1A1A1A',
                    'font_family'      => '"Outfit", "DM Sans", sans-serif',
                    'text_color'       => '#ffffff',
                    'rich_text'        => '<p style="text-align: center"><span style="font-size: 14px">Got questions? Check out our <u>Returns</u> &amp; <u>Refunds Policy</u> or contact <u>Here</u> for support. We\'re happy to help!</span></p>',
                ]
            ),
            SocialIcon::get_object_data(
                [
                    'padding'          => [
                        'top'    => 10,
                        'right'  => '0',
                        'bottom' => 10,
                        'left'   => '0',
                    ],
                    'background_color' => '#1A1A1A',
                    'width_icon'       => '32',
                    'spacing'          => '12',
                    'icon_list'        => [
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
                    'padding'          => [
                        'top'    => '0',
                        'right'  => '50',
                        'bottom' => '32',
                        'left'   => '50',
                    ],
                    'background_color' => '#1A1A1A',
                    'font_family'      => '"Outfit", "DM Sans", sans-serif',
                    'text_color'       => '#B0B0B0',
                    'rich_text'        => '<p style="text-align: center"><span style="font-size: 12px;font-weight: 400">© 2026 WooCommerce</span></p>',
                ]
            ),
        ];
    }

    private static function section_margin(): array {
        return [
            'top'    => '20',
            'right'  => '0',
            'bottom' => '20',
            'left'   => '0',
        ];
    }

    private static function with_margin( array $element, array $margin ): array {
        $element['data']['margin'] = $margin;

        return $element;
    }
}
