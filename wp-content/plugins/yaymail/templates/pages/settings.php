<?php
defined( 'ABSPATH' ) || exit;

/*
 * WooCommerce dependency gate.
 *
 * On the YayMail (WooCommerce) product the customizer app is only enqueued when
 * WooCommerce is active (Initialize::init_core runs solely in the WC branch of
 * the plugin bootstrap). Without WooCommerce the React app never mounts, so the
 * bare pre-loader below would spin forever. Render a dedicated requirement screen
 * instead of a blank, endlessly loading page.
 *
 * The Yay WP Email Customizer product (yaymagic-email-builder platform) is designed to run
 * without WooCommerce, so it must never see this screen — scope the check with the
 * platform provider, falling back to the YAYWP_VERSION signal when the platform
 * cannot be resolved from the current screen.
 */
$yaymail_current_platform = class_exists( '\YayMail\Platform\PlatformRegistry' )
	? \YayMail\Platform\PlatformRegistry::from_screen( get_current_screen() )
	: null;

$yaymail_needs_woocommerce = $yaymail_current_platform
	? ( $yaymail_current_platform->is_woo_product() && ! function_exists( 'WC' ) )
	: ( ! defined( 'YAYWP_VERSION' ) && ! function_exists( 'WC' ) );

if ( $yaymail_needs_woocommerce ) {
	$yaymail_install_wc_url = esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) );
	?>
	<style>
		#wpcontent #wpbody .notice,
		.error, .updated { display: none; }
		#wpfooter { display: none; }
		.yaymail-requirement-screen {
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: calc(100vh - 160px);
			padding: 24px;
			box-sizing: border-box;
		}
		.yaymail-requirement-card {
			max-width: 460px;
			width: 100%;
			text-align: center;
			background: #ffffff;
			border: 1px solid #e4e4e7;
			border-radius: 12px;
			padding: 40px 32px;
			box-shadow: 0 1px 3px rgba( 0, 0, 0, 0.06 );
		}
		.yaymail-requirement-card__icon { font-size: 44px; line-height: 1; margin-bottom: 16px; }
		.yaymail-requirement-card__title { font-size: 22px; font-weight: 600; color: #1e1e1e; margin: 0 0 12px; }
		.yaymail-requirement-card__text { font-size: 14px; line-height: 1.6; color: #50575e; margin: 0 0 24px; }
		.yaymail-requirement-card__button {
			display: inline-block;
			background: #873eff;
			color: #ffffff;
			font-size: 14px;
			font-weight: 600;
			text-decoration: none;
			padding: 10px 24px;
			border-radius: 6px;
			transition: background 0.15s ease;
		}
		.yaymail-requirement-card__button:hover,
		.yaymail-requirement-card__button:focus { background: #6f2fe0; color: #ffffff; }
		.yaymail-requirement-card__hint { font-size: 12px; color: #787c82; margin: 20px 0 0; }
	</style>
	<div class="yaymail-requirement-screen">
		<div class="yaymail-requirement-card">
			<div class="yaymail-requirement-card__icon" aria-hidden="true">🛒</div>
			<h1 class="yaymail-requirement-card__title">
				<?php esc_html_e( 'WooCommerce is required', 'yaymail' ); ?>
			</h1>
			<p class="yaymail-requirement-card__text">
				<?php esc_html_e( 'YayMail customizes your WooCommerce transactional emails, so it needs WooCommerce installed and activated. Install WooCommerce to start building your emails.', 'yaymail' ); ?>
			</p>
			<a class="yaymail-requirement-card__button" href="<?php echo esc_url( $yaymail_install_wc_url ); ?>">
				<?php esc_html_e( 'Install WooCommerce', 'yaymail' ); ?>
			</a>
			<p class="yaymail-requirement-card__hint">
				<?php esc_html_e( 'Already installed it? Activate WooCommerce, then reload this page.', 'yaymail' ); ?>
			</p>
		</div>
	</div>
	<?php
	return;
}
?>
<style>
    #wpcontent #wpbody .notice,
    .error, .updated {
        display: none;
    }
    #wpfooter {
        display: none;
    }
</style>
<div style="display: none;">
    <?php
    wp_editor(
        '',
        'yaymagic-email-builder-editor-placeholder',
        [
            'quicktags'     => false,
            'media_buttons' => true,
            'tinymce'       => true,
        ]
    );
    ?>
    </div>
<div id="yaymail-main-pages">
    <div class="yaymail-pre-loading" style="width: 20px; height: 20px; background: url(images/spinner-2x.gif); background-size: contain; background-repeat: no-repeat; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    </div>
</div>

