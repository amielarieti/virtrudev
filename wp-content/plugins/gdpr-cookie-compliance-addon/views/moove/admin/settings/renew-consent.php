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
			if ( isset( $_POST['gdpr_consent_version'] ) && floatval( $_POST['gdpr_consent_version'] ) >= 1.0 ) :
				$value = floatval( $_POST['gdpr_consent_version'] );
			else :
				$value = 1.0;
			endif;
			$gdpr_options['gdpr_consent_version'] = $value;


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
?>

<form action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=renew-consent" method="post" id="moove_gdpr_tab_renew_consent_settings">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<h2><?php esc_html_e( 'Renew Consent', 'gdpr-cookie-compliance-addon' ); ?></h2>

	<hr />

	<table class="gdpr-cc-geo-wrap form-table">
		<tbody>
			<tr>
				<td style="padding-left: 0;">
					<p><strong><?php esc_html_e( 'This feature allows you to ask users to renew their consent if there is a change in privacy or cookie policy on your website.', 'gdpr-cookie-compliance-addon' ); ?></strong></p>
					<!--  .description -->
					<br /><br />

					<div class="gdpr_consent_version-wrap">
						<label for="gdpr_consent_version">
							<?php esc_html_e( 'Consent version', 'gdpr-cookie-compliance-addon' ); ?>
						</label>
						<span class="gdpr-cv-btn gdpr-cv-dec">
							<span class="dashicons dashicons-minus"></span>
						</span>
						<input type="number" class="gdpr-float-number-field" value="<?php echo isset( $gdpr_options[ 'gdpr_consent_version' ] ) && floatval( $gdpr_options[ 'gdpr_consent_version' ] ) >= 1.0 ? esc_attr( $gdpr_options[ 'gdpr_consent_version' ] ) : 1.0; ?>" min="1.0" step="0.1" id="gdpr_consent_version" name="gdpr_consent_version" style="width: 80px;" />
						<span class="gdpr-cv-btn gdpr-cv-inc">
							<span class="dashicons dashicons-plus"></span>
						</span>
					</div>
					<!--  .gdpr_consent_version-wrap -->
					<br />
					<p class="description"><?php esc_html_e( 'Increase the number above and your users will be asked to accept cookies again.', 'gdpr-cookie-compliance-addon' ); ?></p>
					<!--  .description -->
						
					

				</td>
			</tr>
		</tbody>
	</table>
	<br />
	<hr />
	<br />
	<div class="submit-gdpr-button-cnt">
		<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Changes', 'gdpr-cookie-compliance-addon' ); ?></button>
	</div>
	<!--  .submit-gdpr-button-cnt -->
</form>