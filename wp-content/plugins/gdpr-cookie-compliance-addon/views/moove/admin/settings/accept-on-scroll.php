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
		$nonce = sanitize_key( $_POST['moove_gdpr_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_nonce_field' ) ) :
		die( 'Security check' );
	else :

		if ( isset( $_POST['moove_gdpr_aos_hide_enable'] ) && intval( $_POST['moove_gdpr_aos_hide_enable'] ) >= 0 ) :
			if ( is_array( $_POST['moove_gdpr_aos_hide_enable'] ) ) :
				$value = implode( ',', $_POST['moove_gdpr_aos_hide_enable'] );
			else :
				$value = intval( $_POST['moove_gdpr_aos_hide_enable'] );
			endif;
		else :
			$value = 0;
		endif;

		$gdpr_options['moove_gdpr_modal_enable_scroll'] = false;
		$gdpr_options['moove_gdpr_aos_hide_enable'] = $value;

		if ( isset( $_POST['moove_gdpr_aos_hide_seconds'] ) && intval( $_POST['moove_gdpr_aos_hide_seconds'] ) >= 0 ) :
			$value = intval( $_POST['moove_gdpr_aos_hide_seconds'] );
		else :
			$value = 0;
		endif;
		$gdpr_options['moove_gdpr_aos_hide_seconds'] = $value;

		update_option( $option_name, $gdpr_options );

		$gdpr_options = get_option( $option_name );
		do_action( 'gdpr_cookie_filter_settings' );

		?>
		<script>
			jQuery('#moove-gdpr-setting-error-settings_updated').show();
		</script>
		<?php
	endif;
endif;

$checked_status = array( '0' );
if ( isset( $gdpr_options['moove_gdpr_modal_enable_scroll'] ) && $gdpr_options['moove_gdpr_modal_enable_scroll'] === 1 ) :
	// Accept cookies on scroll
	$checked_status = array( '1' );
else :
	$checked_status = isset( $gdpr_options['moove_gdpr_aos_hide_enable'] ) ? explode( ',', $gdpr_options['moove_gdpr_aos_hide_enable'] ) : 0; 
endif;

$checked_status = is_array( $checked_status ) ? $checked_status : array( '0' );

?>
<form action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=accept-on-scroll" method="post" id="moove_gdpr_tab_fsm_settings">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<h2><?php esc_html_e( 'Capture user consent & hide the banner', 'gdpr-cookie-compliance-addon' ); ?></h2>
	<hr />

	<table class="form-table">
		<tbody>
			<tr>
				<td style="padding: 0;">
					<table>

						<tr>
							<td style="padding-left: 0;">
								<h4 style="margin: 0">
									<?php 
										printf(
									    __( 'After the user clicks the Accept/Reject button %1$s', 'gdpr-cookie-compliance-addon' ),
									    sprintf(
								        '<strong class="gdpr_cc_highlight">[%s]</strong>',
								        esc_html__( 'default option', 'text-domain' )
							        )
								    );
							   	?>
							  </h4>
								<!-- GDPR Rounded switch -->
								
							</td>
							<td>
								<label class="gdpr-checkbox-toggle">
									<input type="checkbox" name="moove_gdpr_aos_hide_enable[]" id="moove_gdpr_aos_hide_enable" <?php echo in_array( '0', $checked_status ) ? 'checked' : ''; ?> value="0" >
									<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
								</label>
							</td>
						</tr>

						<tr>
							<td style="padding-left: 0;">
								<h4 style="margin: 0"><?php esc_html_e( 'After the user scrolls down the page', 'gdpr-cookie-compliance-addon' ); ?></h4>
								<!-- GDPR Rounded switch -->
								
							</td>
							<td>
								<label class="gdpr-checkbox-toggle">
									<input type="checkbox" name="moove_gdpr_aos_hide_enable[]" id="moove_gdpr_aos_hide_enable_1" <?php echo in_array( '1', $checked_status ) ? 'checked' : ''; ?> value="1" >
									<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
								</label>
							</td>
						</tr>

						<tr>
							<td style="padding-left: 0;">
								<h4 style="margin: 0">
									<?php esc_html_e( 'After this amount of time ', 'gdpr-cookie-compliance-addon' ); ?>
									<input type="number" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_aos_hide_seconds' ] ) && intval( $gdpr_options[ 'moove_gdpr_aos_hide_seconds' ] ) >= 0 ? esc_attr( $gdpr_options[ 'moove_gdpr_aos_hide_seconds' ] ) : '30'; ?>" min="0" step="1" name="moove_gdpr_aos_hide_seconds" style="width: 60px;" />
									<?php esc_attr_e( ' seconds', 'gdpr-cookie-compliance-addon' ); ?>
								</h4>
								<!-- GDPR Rounded switch -->								
							</td>
							<td>
								<label class="gdpr-checkbox-toggle">
									<input type="checkbox" <?php echo in_array( '2', $checked_status ) ? 'checked' : ''; ?> name="moove_gdpr_aos_hide_enable[]" id="moove_gdpr_aos_hide_enable_2" value="2" >
									<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
								</label>
							</td>
						</tr>
					</table>
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
