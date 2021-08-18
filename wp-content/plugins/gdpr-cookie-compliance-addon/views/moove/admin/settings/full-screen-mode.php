<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
	
	$gdpr_default_content = new Moove_GDPR_Content();
	$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
	$gdpr_options         = get_option( $option_name );
	$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
	$gdpr_options         = is_array( $gdpr_options ) ? $gdpr_options : array();
if ( isset( $_POST ) && isset( $_POST['moove_gdpr_nonce'] ) ) :
	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['moove_gdpr_nonce'] ) ), 'moove_gdpr_nonce_field' ) ) :
		die( 'Security check' );
		else :
			$gdpr_options['moove_gdpr_full_screen_enable'] = 0;
			if ( isset( $_POST['moove_gdpr_full_screen_enable'] ) ) :
				$gdpr_options['moove_gdpr_full_screen_enable'] = 1;
			endif;

			$gdpr_options['moove_gdpr_full_screen_banner_layout'] = 0;
			if ( isset( $_POST['moove_gdpr_full_screen_banner_layout'] ) ) :
				$gdpr_options['moove_gdpr_full_screen_banner_layout'] = 1;
			endif;		


			update_option( $option_name, $gdpr_options );
			$gdpr_options = get_option( $option_name );

			if ( is_array( $_POST ) ) :
				foreach ( $_POST as $form_key => $form_value ) :
					if ( 'moove_gdpr_full_screen_enable' !== $form_key && 'moove_gdpr_full_screen_banner_layout' !== $form_key  ) :
						$value                     = sanitize_text_field( wp_unslash( $form_value ) );
						$gdpr_options[ $form_key ] = $value;
						update_option( $option_name, $gdpr_options );
						$gdpr_options = get_option( $option_name );
					endif;
				endforeach;
			endif;
			do_action( 'gdpr_cookie_filter_settings' );
			?>
				<script>
					jQuery('#moove-gdpr-setting-error-settings_updated').show();
				</script>
			<?php
		endif;
	endif;
?>
<form action="<?php echo esc_url( admin_url( 'admin.php?page=moove-gdpr&amp;tab=full-screen-mode') ); ?>" method="post" id="moove_gdpr_tab_fsm_settings">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<h2><?php esc_html_e( 'Full-Screen Mode Setup', 'gdpr-cookie-compliance-addon' ); ?></h2>
	<hr />

	<table class="form-table">
		<tbody>
			<tr>
				<td style="padding-left: 0;" colspan="2">
					<!-- GDPR Rounded switch -->
					<label class="gdpr-checkbox-toggle">
						<input type="checkbox" name="moove_gdpr_full_screen_enable" id="moove_gdpr_full_screen_enable" <?php echo isset( $gdpr_options['moove_gdpr_full_screen_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_full_screen_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_full_screen_enable'] ) ? 'checked' : '' ) ) : ''; ?> >
						<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
					</label>
					<br /><br />

					<p class="description" id="moove_gdpr_full_screen_enable-description"><?php esc_html_e( 'If enabled, the Cookie Banner will be displayed in full screen mode, and force the user to either accept all cookies, or to review the Cookie Settings.', 'gdpr-cookie-compliance-addon' ); ?></p>
					<p class="description"><strong><?php esc_html_e( 'Please note that this feature requires the Strictly Necessary Cookies to be always enabled (otherwise the Cookie Banner would be displayed at every visit).', 'gdpr-cookie-compliance-addon' ); ?></strong></p>
					<!--  .description -->
				</td>
			</tr>

			<tr class="gdpr-conditional-field" data-dependency="#moove_gdpr_full_screen_enable">
				<th scope="row">
					<label for="moove_gdpr_full_screen_banner_layout"><?php esc_html_e( 'Cookie Settings screen visible straight-away', 'gdpr-cookie-compliance' ); ?></label>
				</th>

				<td style="padding-left: 0;">
					<!-- GDPR Rounded switch -->
					<label class="gdpr-checkbox-toggle">
						<input type="checkbox" name="moove_gdpr_full_screen_banner_layout" id="moove_gdpr_full_screen_banner_layout" <?php echo isset( $gdpr_options['moove_gdpr_full_screen_banner_layout'] ) ? ( intval( $gdpr_options['moove_gdpr_full_screen_banner_layout'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_full_screen_banner_layout'] ) ? 'checked' : '' ) ) : ''; ?> >
						<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
					</label>
					<!--  .description -->
				</td>
			</tr>
			
		</tbody>
	</table>

	<br />
	<hr />
	<br />
	<button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'gdpr-cookie-compliance-addon' ); ?></button>
	<?php do_action( 'gdpr_cc_banner_buttons_settings' ); ?>
</form>
