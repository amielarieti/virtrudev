<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php
	$gdpr_default_content = new Moove_GDPR_Content();
	$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
	$gdpr_options         = get_option( $option_name );
	$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
	$gdpr_options         = is_array( $gdpr_options ) ? $gdpr_options : array();
	if ( isset( $_POST ) && isset( $_POST['moove_gdpr_nonce'] ) ) :

		if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['moove_gdpr_nonce'] ) ), 'moove_gdpr_nonce_field' ) ) :
			die( 'Security check' );
		else :
			$gdpr_options['moove_gdpr_cd_enable'] = 0;
			if ( isset( $_POST['moove_gdpr_cd_enable'] ) ) :
				$gdpr_options['moove_gdpr_cd_enable'] = 1;
			endif;
			update_option( $option_name, $gdpr_options );
			$gdpr_options = get_option( $option_name );

			if ( is_array( $_POST ) ) :
				foreach ( $_POST as $form_key => $form_value ) :
					if ( 'moove_gdpr_cd_enable' !== $form_key ) :
						$value                     = sanitize_text_field( wp_unslash( urldecode( $form_value ) ) );
						$gdpr_options[ $form_key ] = $value;
						if ( $wpml_lang ) :
							$gdpr_options[ $form_key . $wpml_lang ] = $value;
						endif;
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
<form action="<?php echo esc_url( admin_url( 'admin.php?page=moove-gdpr&amp;tab=cookie-declaration') ); ?>" method="post" id="moove_gdpr_tab_cd_settings">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>

	<h2><?php _e('Cookie Declaration','gdpr-cookie-compliance'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr>
				<td style="padding-left: 0;">
					<!-- GDPR Rounded switch -->
					<label class="gdpr-checkbox-toggle">
						<input type="checkbox" name="moove_gdpr_cd_enable" id="moove_gdpr_cd_enable" <?php echo isset( $gdpr_options['moove_gdpr_cd_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_cd_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_cd_enable'] ) ? 'checked' : '' ) ) : ''; ?> >
						<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
					</label>
					<br /><br />

					<p class="description">
						<ul>
							<li><?php _e('Below you can list cookies that your website uses.','gdpr-cookie-compliance-addon'); ?></li>
							<li><?php _e('The list will be displayed on the Cookie Settings screen.','gdpr-cookie-compliance-addon'); ?></li>
						</ul>
						<hr />
					</p>
					<!--  .description -->
				</td>
			</tr>
			<tr>
				<td style="padding-left: 0;">
					<?php 
						$view_controller = new Moove_GDPR_Addon_View();
						$types = array( 
							'strictly' 		=> array(
								'id'			=> 'strictly',
								'title'		=> isset( $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] : __( 'Strictly Necessary Cookies', 'gdpr-cookie-compliance' ),
								'options'	=> $gdpr_options,
							), 
							'thirdparty'	=> array(
								'id'			=> 'thirdparty',
								'title'		=> isset( $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] : __( '3rd Party Cookies', 'gdpr-cookie-compliance' ),
								'options'	=> $gdpr_options,
							), 
							'advanced'		=> array(
								'id'			=> 'advanced',
								'title'		=> isset( $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] : __( 'Additional Cookies', 'gdpr-cookie-compliance' ),
								'options'	=> $gdpr_options,
							) 
						);
						$types = apply_filters( 'gdpr_cc_cookie_declaration_types', $types );
						foreach ( $types as $type_id => $type_content ) :
							$content         = $view_controller->load( 'moove.admin.settings.cookie-declaration.declaration-flex', $type_content );
							apply_filters( 'gdpr_addon_keephtml', $content, true );
						endforeach;
					?>
				</td>
			</tr>

			
			<tr>
				<table class="gdpr-conditional-field" data-dependency="#moove_gdpr_cd_enable">
					<thead>
						<tr>
							<th scope="row" style="padding-right: 20px; text-align: left;">
								<label for="moove_gdpr_cd_name_label">
									<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) : esc_attr__( 'Name', 'gdpr-cookie-compliance' ); ?>
								</label>
							</th>
							<th scope="row" style="padding-right: 20px; text-align: left;">
								<label for="moove_gdpr_cd_provider_label">
									<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) : esc_attr__( 'Provider', 'gdpr-cookie-compliance' ); ?>
								</label>
							</th>
							<th scope="row" style="padding-right: 20px; text-align: left;">
								<label for="moove_gdpr_cd_purpose_label">
									<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) : esc_attr__( 'Purpose', 'gdpr-cookie-compliance' ); ?>
								</label>
							</th>
							<th scope="row" style="padding-right: 20px; text-align: left;">
								<label for="moove_gdpr_cd_expiration_label">
									<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) : esc_attr__( 'Expiration', 'gdpr-cookie-compliance' ); ?>
								</label>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							
							<td>
								<input name="moove_gdpr_cd_name_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_cd_name_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) : esc_attr__( 'Name', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
							</td>
							<td>
								<input name="moove_gdpr_cd_provider_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_cd_provider_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) : esc_attr__( 'Provider', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
							</td>
							<td>
								<input name="moove_gdpr_cd_purpose_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_cd_purpose_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) : esc_attr__( 'Purpose', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
							</td>
							<td>
								<input name="moove_gdpr_cd_expiration_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_cd_expiration_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) : esc_attr__( 'Expiration', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
							</td>
						</tr>
					</tbody>
				</table>
			</tr>
			<tr>
				<td>
					<br />
				</td>
			</tr>

			<tr>
				<table class="gdpr-conditional-field" data-dependency="#moove_gdpr_cd_enable">
					<thead>
						<tr>
							<th scope="row" style="padding-right: 20px; text-align: left;">
								<label for="moove_gdpr_cd_show_label"><?php esc_html_e( 'Show Details - Label', 'gdpr-cookie-compliance' ); ?></label>
							</th>
							<th scope="row" style="padding-right: 20px; text-align: left;">
								<label for="moove_gdpr_cd_hide_label"><?php esc_html_e( 'Hide Details - Label', 'gdpr-cookie-compliance' ); ?></label>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							
							<td>
								<input name="moove_gdpr_cd_show_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_cd_show_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_show_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_show_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_show_label' . $wpml_lang ] ) : esc_attr__( 'Show details', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
							</td>
							<td>
								<input name="moove_gdpr_cd_hide_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_cd_hide_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_hide_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_hide_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_hide_label' . $wpml_lang ] ) : esc_attr__( 'Hide details', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
							</td>
						</tr>
					</tbody>
				</table>
			</tr>
		</tbody>
	</table>
	
	
	<br />
	<hr />
	<br />
	<button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'gdpr-cookie-compliance-addon' ); ?></button>
	<?php do_action( 'gdpr_cc_banner_buttons_settings' ); ?>
</form>