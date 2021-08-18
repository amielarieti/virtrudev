<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>
<div class="gdpr_cookie_settings_shortcode_content">
	<?php if ( $data['title'] ) : ?>
		<h3><?php echo esc_attr( $data['title'] ); ?></h3>
	<?php endif; ?>
	<?php if ( $data['content'] ) : ?>
		<p><strong><?php echo esc_attr( $data['content'] ); ?></strong></p>
	<?php endif; ?>
	<hr />
	<?php
		$view_controller = new Moove_GDPR_Addon_View();
	apply_filters( 'gdpr_addon_keephtml', $view_controller->load( 'moove.shortcode.modules.strictly', $data ), true );
		apply_filters( 'gdpr_addon_keephtml', $view_controller->load( 'moove.shortcode.modules.third_party', $data ), true );
	apply_filters( 'gdpr_addon_keephtml', $view_controller->load( 'moove.shortcode.modules.advanced', $data ), true );

		?>
	<hr />
	<?php if ( $data['save_button_label'] ) : ?>
		<a href="#" class="gdpr-shr-button button-green gdpr-shr-save-settings"><?php echo esc_attr( $data['save_button_label'] ); ?></a>
	<?php endif; ?>
	
	<?php if ( $data['settings_button_label'] && $data['show_settings_button'] ) : ?>
		<a data-href="#moove_gdpr_cookie_modal" href="#" class="gdpr-shr-button gdpr-shr-open-settings"><?php echo esc_attr( $data['settings_button_label'] ); ?></a>
	<?php endif; ?>
	
</div>
<!--  .gdpr_cookie_settings_shortcode_content -->
