<?php

namespace YayMail\TemplateLibrary\Templates\Processing;

use YayMail\Abstracts\BaseTemplate;
use YayMail\Elements\BillingAddress;
use YayMail\Elements\Button;
use YayMail\Elements\Column;
use YayMail\Elements\ColumnLayout;
use YayMail\Elements\Heading;
use YayMail\Elements\Logo;
use YayMail\Elements\OrderDetails;
use YayMail\Elements\OrderProgress;
use YayMail\Elements\ShippingAddress;
use YayMail\Elements\SocialIcon;
use YayMail\Elements\Text;
use YayMail\Utils\SingletonTrait;

/**
 * Order Journey template for Processing Order email.
 */
class OrderJourney extends BaseTemplate {
    use SingletonTrait;

    public function __construct() {
        parent::__construct();
        $this->id                = 'processing_order_v4';
        $this->email_type        = 'customer_processing_order';
        $this->name              = 'Order Journey';
        $this->description       = 'Emphasizes the order progress timeline.';
        $this->categories        = [ 'Purple Collection', 'Progress collection' ];
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
                                            'background_color' => '#ffffff',
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
                                            'background_color' => '#ffffff',
                                            'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#333333',
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
                                            'background_color' => '#ffffff',
                                            'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#1a1a1a',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 14px">Order Tracking</span></p>',
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
                                            'background_color' => '#ffffff',
                                            'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#1a1a1a',
                                            'rich_text'   => '<p style="text-align: right"><span style="font-size: 14px;font-weight: 400">Contact</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => 15,
                        'right'  => '50',
                        'bottom' => 15,
                        'left'   => '50',
                    ],
                    'background_color' => '#ffffff',
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
                                                'top'    => 0,
                                                'right'  => '50',
                                                'bottom' => 0,
                                                'left'   => '50',
                                            ],
                                            'background_color' => '#ffffff00',
                                            'font_family' => 'Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#84ff7c',
                                            'rich_text'   => '<p style="text-align: center"><span style="white-space: pre-wrap;font-size: 12px;font-weight: bold">ORDER CONFIRMED!</span></p>',
                                        ]
                                    ),
                                    Heading::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => '0',
                                                'right'  => '0',
                                                'bottom' => '0',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#7f54b300',
                                            'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'rich_text'   => '<p style="font-size: 36px; font-weight: bold; text-align: center; margin: 12px 0;"><span style="font-size: 48px; line-height: 1.2 !important;">New Order Received</span><br /><span style="font-size: 48px; line-height: 1.2 !important;">Please Review</span></p>',
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
                                            'background_color' => '#7f54b300',
                                            'font_family' => 'Helvetica,Roboto,Arial,sans-serif',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 14px;color: #ffffff;font-weight: 400">Order placed on [yaymail_order_date]</span></p>
                                                <p style="text-align: center"><span style="font-size: 14px;font-weight: 400"><span style="color: #ffffff">Number ID #[yaymail_order_id is_plain="true"]</span></span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => 27,
                        'right'  => '50',
                        'bottom' => 27,
                        'left'   => '50',
                    ],
                    'background_color' => '#ffffff',
                    'background_image' => [
                        'url'         => 'https://images.wpbrandy.com/uploads/processing-4-img-1.png',
                        'position'    => 'custom',
                        'size'        => 'cover',
                        'repeat'      => 'no-repeat',
                        'y_position'  => 6,
                        'x_position'  => 10,
                        'custom_size' => 100,
                    ],
                ]
            ),
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
                                            'padding'     => [
                                                'top'    => 30,
                                                'right'  => '40',
                                                'bottom' => 30,
                                                'left'   => '40',
                                            ],
                                            'display_style' => 'filled_bar',
                                            'connector_height' => '3',
                                            'connector_active_color' => '#723af50f',
                                            'connector_inactive_color' => '#723af50f',
                                            'label_active_color' => '#723af5',
                                            'label_inactive_color' => '#723af5',
                                            'icon_size'   => '17',
                                            'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'steps'       => [
                                                [
                                                    'title' => 'Ordered',
                                                    'image_url' => YAYMAIL_PLUGIN_URL . 'assets/images/check.png',
                                                    'image_bg_color' => '#723af5',
                                                    'icon_border_color' => '#723af5',
                                                    'icon_border_style' => 'solid',
                                                    'icon_border_width' => 2,
                                                ],
                                                [
                                                    'title' => 'Processing',
                                                    'image_url' => YAYMAIL_PLUGIN_URL . 'assets/images/process.png',
                                                    'image_bg_color' => '#d1befe',
                                                    'icon_border_color' => '#d1befe',
                                                    'icon_border_style' => 'solid',
                                                    'icon_border_width' => 2,
                                                ],
                                                [
                                                    'title' => 'Completed',
                                                    'image_url' => YAYMAIL_PLUGIN_URL . 'assets/images/check.png',
                                                    'image_bg_color' => '#d1befe00',
                                                    'icon_border_color' => '#d1befe',
                                                    'icon_border_style' => 'dashed',
                                                    'icon_border_width' => 3,
                                                ],
                                            ],
                                        ]
                                    ),
                                ],
                                'border'   => [
                                    'side'   => 'all',
                                    'width'  => '1',
                                    'style'  => 'solid',
                                    'color'  => '#0000001a',
                                    'custom' => [
                                        'top'    => '1',
                                        'right'  => '1',
                                        'bottom' => '1',
                                        'left'   => '1',
                                    ],
                                ],
                            ]
                        ),
                    ],
                    'padding'             => [
                        'top'    => 30,
                        'right'  => 50,
                        'bottom' => 20,
                        'left'   => 50,
                    ],
                    'background_color'    => '#ffffff',
                    'inner_border_radius' => [
                        'top_left'     => 16,
                        'top_right'    => 16,
                        'bottom_left'  => 16,
                        'bottom_right' => 16,
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
                                            'padding'          => [
                                                'top'    => 0,
                                                'right'  => 0,
                                                'bottom' => '8',
                                                'left'   => 0,
                                            ],
                                            'background_color' => '#ffffff',
                                            'font_family'      => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'       => '#1a1a1a',
                                            'rich_text'        => '<p><span style="font-size: 16px">Hi <strong>[yaymail_billing_first_name]</strong>,</span></p>
                                                <p><span style="font-size: 16px">Thanks for your order!</span></p>
                                                <p><span style="font-size: 16px">We\'ve successfully received it and it\'s now being processed. We\'ll notify you as soon as your order is shipped.</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => 10,
                        'right'  => '50',
                        'bottom' => 10,
                        'left'   => '50',
                    ],
                    'background_color' => '#ffffff',
                ]
            ),
            OrderDetails::get_object_data(
                [
                    'padding'                 => [
                        'top'    => 15,
                        'right'  => '50',
                        'bottom' => 15,
                        'left'   => '50',
                    ],
                    'layout_type'             => 'legacy',
                    'title'                   => '<span style="font-size: 20px;font-weight: 600">Order Summary</span>',
                    'title_color'             => '#1a1a1a',
                    'text_color'              => '#1a1a1a',
                    'border_color'            => '#1a1a1a',
                    'font_family'             => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                    'table_heading_font_size' => 16,
                ]
            ),
            Button::get_object_data(
                [
                    'padding'                 => [
                        'top'    => '16',
                        'right'  => '50',
                        'bottom' => '16',
                        'left'   => '50',
                    ],
                    'background_color'        => '#ffffff',
                    'align'                   => 'left',
                    'width'                   => '50%',
                    'button_background_color' => '#723af5',
                    'border_radius'           => [
                        'top_left'     => '30',
                        'top_right'    => '30',
                        'bottom_right' => '30',
                        'bottom_left'  => '30',
                    ],
                    'text'                    => 'View Your Order',
                    'url'                     => '[yaymail_order_url]',
                    'font_size'               => '16',
                    'weight'                  => '500',
                    'font_family'             => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                ]
            ),
            ColumnLayout::get_object_data(
                2,
                [
                    'children'               => [
                        Column::get_object_data(
                            50,
                            [
                                'children' => [
                                    ShippingAddress::get_object_data(
                                        [
                                            'padding'     => [
                                                'top'    => 25,
                                                'right'  => 0,
                                                'bottom' => '15',
                                                'left'   => 0,
                                            ],
                                            'layout_type' => 'modern',
                                            'title_color' => '#1a1a1a',
                                            'text_color'  => '#1a1a1a',
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
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
                                'border'   => [
                                    'side'   => 'custom',
                                    'width'  => '1',
                                    'style'  => 'solid',
                                    'color'  => '#1a1a1a',
                                    'custom' => [
                                        'top'    => '1',
                                        'right'  => 0,
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
                                                'top'    => 25,
                                                'right'  => '50',
                                                'bottom' => '15',
                                                'left'   => '50',
                                            ],
                                            'layout_type' => 'modern',
                                            'title_color' => '#1a1a1a',
                                            'text_color'  => '#1a1a1a',
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
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
                                'border'   => [
                                    'side'   => 'all',
                                    'width'  => '1',
                                    'style'  => 'solid',
                                    'color'  => '#1a1a1a',
                                    'custom' => [
                                        'top'    => '1',
                                        'right'  => '1',
                                        'bottom' => '1',
                                        'left'   => '1',
                                    ],
                                ],
                            ]
                        ),
                    ],
                    'column_spacing'         => 0,
                    'padding'                => [
                        'top'    => 25,
                        'right'  => 50,
                        'bottom' => 25,
                        'left'   => 50,
                    ],
                    'inner_background_color' => '#ffffff',
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
                                                'bottom' => '0',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#fafafa',
                                            'font_family' => '"Rethink Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#1a1a1a',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 16px;font-weight: 400">For questions, contact <u>hi@yourwoostore.com</u>, visit our <u>FAQs</u>, or <u>chat</u> with us during operating hours for account support</span></p>',
                                        ]
                                    ),
                                    SocialIcon::get_object_data(
                                        [
                                            'padding'    => [
                                                'top'    => '20',
                                                'right'  => '0',
                                                'bottom' => '12',
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#fafafa',
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
                                                'bottom' => 10,
                                                'left'   => '0',
                                            ],
                                            'background_color' => '#fafafa',
                                            'font_family' => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
                                            'text_color'  => '#1a1a1a',
                                            'rich_text'   => '<p style="text-align: center"><span style="font-size: 12px">© 2026 WooCommerce</span></p>',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                    'padding'          => [
                        'top'    => 30,
                        'right'  => '50',
                        'bottom' => '20',
                        'left'   => '50',
                    ],
                    'background_color' => '#fafafa',
                ]
            ),
        ];
    }

    private static function with_margin( array $element, array $margin ): array {
        $element['data']['margin'] = $margin;

        return $element;
    }
}
