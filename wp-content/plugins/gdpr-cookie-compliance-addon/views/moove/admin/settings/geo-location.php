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
			if ( is_array( $_POST ) ) :

				$gdpr_options['moove_gdpr_cc_geo_status'] = 0;
				if ( isset( $_POST['moove_gdpr_cc_geo_status'] ) ) :
					$gdpr_options['moove_gdpr_cc_geo_status'] = 1;
				endif;		

				$gdpr_options['moove_gdpr_cc_geo_setup'] 	= json_encode( array() );
				$gdpr_options['gdpr_geo_countries'] 			= json_encode( array() );

				$gdpr_options['moove_gdpr_geolocation_eu'] = '0';
				
				update_option( $option_name, $gdpr_options );
				$gdpr_options = get_option( $option_name );

				foreach ( $_POST as $form_key => $form_value ) :
					if ( $form_key === 'gdpr_geo_countries' ) :
						$sanitized_countries = array();
						if ( is_array( $form_value ) && ! empty( $form_value ) ) :							
							foreach ( $form_value as $country_abbr ) :
								$sanitized_countries[] = sanitize_text_field( $country_abbr );
							endforeach;				
						endif;
						$value = json_encode( $sanitized_countries );
						$gdpr_options[ $form_key ] = $value;
						update_option( $option_name, $gdpr_options );
						$gdpr_options = get_option( $option_name );
					elseif ( $form_key === 'moove_gdpr_cc_geo_setup' ) :
						$sanitized_options = array();
						if ( is_array( $form_value ) && ! empty( $form_value ) ) :							
							foreach ( $form_value as $setup ) :
								$sanitized_options[$setup] = 'true';
							endforeach;				
						endif;
						$value = json_encode( $sanitized_options );
						$gdpr_options[ $form_key ] = $value;
						update_option( $option_name, $gdpr_options );
						$gdpr_options = get_option( $option_name );
					else :
						$value                     = sanitize_text_field( wp_unslash( $form_value ) );
						$gdpr_options[ $form_key ] = $value;
						update_option( $option_name, $gdpr_options );
						$gdpr_options = get_option( $option_name );
					endif;
				endforeach;
				if ( ! isset( $_POST['gdpr_geo_countries'] ) ) :
					unset( $gdpr_options[ 'gdpr_geo_countries' ] );
					update_option( $option_name, $gdpr_options );
					$gdpr_options = get_option( $option_name );
				endif;
			endif;
			do_action( 'gdpr_cookie_filter_settings' );
			?>
				<script>
					jQuery('#moove-gdpr-setting-error-settings_updated').show();
				</script>
			<?php
		endif;
	endif;

	if ( isset( $gdpr_options['moove_gdpr_cc_geo_status'] ) ) :
		// New support - checkbox layout
		$geo_status = intval( $gdpr_options['moove_gdpr_cc_geo_status'] ) === 1;
		$geo_setup 	= isset( $gdpr_options['moove_gdpr_cc_geo_setup'] ) ? json_decode( $gdpr_options['moove_gdpr_cc_geo_setup'], true ) : array();
	else :
		// Legacy support - radio layout
		$geo_setup 	= isset( $gdpr_options['moove_gdpr_geolocation_eu'] ) ? $gdpr_options['moove_gdpr_geolocation_eu'] : '';
		$geo_setup 	= is_array( $geo_setup ) ? $geo_setup : array( intval( $geo_setup ) => 'true' );
		$geo_status = isset( $geo_setup[0] ) ? false : true;
	endif;

?>
<link rel="stylesheet" type="text/css" href="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/css/gdpr_cc_select2.css" >
<form action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=geo-location" method="post" id="moove_gdpr_tab_geo_settings">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<h2><?php esc_html_e( 'Geo Location Setup', 'gdpr-cookie-compliance-addon' ); ?></h2>
	<hr />

	<table class="gdpr-cc-geo-wrap form-table">
		<tbody>

			<tr >
				<th scope="row" style="padding: 10px 0">
					<h4 style="margin-top: 0">
						<?php 
							printf(
						    __( 'Do you want to show the Cookie Banner to %1$s users or to users from %2$s only?', 'gdpr-cookie-compliance-addon' ),
						    sprintf(
					        '<strong class="gdpr_cc_highlight">%s</strong>',
					        esc_html__( 'all', 'gdpr-cookie-compliance-addon' )
				        ),
				        sprintf(
					        '<strong class="gdpr_cc_highlight">%s</strong>',
					        esc_html__( 'specific countries', 'gdpr-cookie-compliance-addon' )
				        )
					    );
				   	?>
					</h4>
					<hr />
					<br />
					<label for="moove_gdpr_cc_geo_status"><?php esc_html_e( 'Show Cookie Banner for', 'gdpr-cookie-compliance' ); ?>&nbsp;&nbsp;</label>
					<!-- GDPR Rounded switch -->
					<label class="gdpr-checkbox-toggle">
						<input type="checkbox" name="moove_gdpr_cc_geo_status" value="1" id="moove_gdpr_cc_geo_status" <?php echo $geo_status ? 'checked' : ''; ?> >
						<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'specific ', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'all', 'gdpr-cookie-compliance' ); ?>"></span>
					</label>
					<!--  .description -->
					<label for="moove_gdpr_cc_geo_status">&nbsp;&nbsp;<?php esc_html_e( 'countries', 'gdpr-cookie-compliance' ); ?>.</label>
				</td>
			</tr>

			<tr class="gdpr-conditional-field" data-dependency="#moove_gdpr_cc_geo_status">
				<td style="padding: 0;">
					<fieldset class="gdpr-geolocation-options">
						
						<label for="moove_gdpr_cc_geo_setup_1">
							<input name="moove_gdpr_cc_geo_setup[]" type="checkbox" <?php echo isset( $geo_setup['1'] ) && $geo_setup['1'] ?  'checked' : ''; ?> id="moove_gdpr_cc_geo_setup_1" value="1">
							<?php 
								printf(
							    __( 'Show Cookie Banner to users from the %1$s only.', 'gdpr-cookie-compliance-addon' ),
							    sprintf(
						        '<strong class="gdpr_cc_highlight">%s</strong>',
						        esc_html__( 'European Union', 'gdpr-cookie-compliance-addon' )
					        )
						    );
					   	?>

							<span class="gdpr_geo_cc_desc"><?php esc_html_e( '(users from outside of the EU will not see the Cookie Banner and will have all cookies enabled by default)', 'gdpr-cookie-compliance-addon' ); ?></span>
						</label>
					
						<br /><br />

						<label for="moove_gdpr_cc_geo_setup_3">
							<input name="moove_gdpr_cc_geo_setup[]" type="checkbox" <?php echo isset( $geo_setup['3'] ) && $geo_setup['3'] ?  'checked' : ''; ?> id="moove_gdpr_cc_geo_setup_3" value="3">
							<?php 
								printf(
							    __( 'Show Cookie Banner to users from %1$s only.', 'gdpr-cookie-compliance-addon' ),
							    sprintf(
						        '<strong class="gdpr_cc_highlight">%s</strong>',
						        esc_html__( 'California (US)', 'gdpr-cookie-compliance-addon' )
					        )
						    );
					   	?>
							<span class="gdpr_geo_cc_desc"><?php esc_html_e( '(users from outside of California region will not see the Cookie Banner and will have all cookies enabled by default)', 'gdpr-cookie-compliance-addon' ); ?></span>
						</label>

						<br /><br />

						<label for="moove_gdpr_cc_geo_setup_4">
							<input name="moove_gdpr_cc_geo_setup[]" type="checkbox" <?php echo isset( $geo_setup['4'] ) && $geo_setup['4'] ?  'checked' : ''; ?> id="moove_gdpr_cc_geo_setup_4" value="4">
							<?php 
								printf(
							    __( 'Show Cookie Banner to users from %1$s only.', 'gdpr-cookie-compliance-addon' ),
							    sprintf(
						        '<strong class="gdpr_cc_highlight">%s</strong>',
						        esc_html__( 'Canada', 'gdpr-cookie-compliance-addon' )
					        )
						    );
					   	?>
							<span class="gdpr_geo_cc_desc"><?php esc_html_e( '(users from outside of Canada will not see the Cookie Banner and will have all cookies enabled by default)', 'gdpr-cookie-compliance-addon' ); ?></span>
						</label>

						<br />

							
						<?php 
							$addon_dir = moove_gdpr_addon_get_plugin_dir();
							$addon_dir = str_replace( plugins_url(), WP_PLUGIN_DIR, $addon_dir );
							$countries = file_get_contents( $addon_dir . "/assets/countries/countries.json" );
							$countries = json_decode( $countries, true );

							$selected_countries = isset( $gdpr_options['gdpr_geo_countries'] ) ? json_decode( $gdpr_options['gdpr_geo_countries'], true ) : array();
							$selected_countries	= is_array( $selected_countries ) ? $selected_countries : array();
						?>
						<?php if ( $countries && is_array( $countries ) ) : ?>
							<span class="separator"></span>
							<br />
							
							<label for="moove_gdpr_cc_geo_setup_2">
								<input name="moove_gdpr_cc_geo_setup[]" type="checkbox" <?php echo isset( $geo_setup['2'] ) && $geo_setup['2'] ?  'checked' : ''; ?> id="moove_gdpr_cc_geo_setup_2" value="2">
								<?php 
									printf(
								    __( 'Show Cookie Banner to users from the %1$s only.', 'gdpr-cookie-compliance-addon' ),
								    sprintf(
							        '<strong class="gdpr_cc_highlight">%s</strong>',
							        esc_html__( 'selected countries', 'gdpr-cookie-compliance-addon' )
						        )
							    );
						   	?>								
								<span class="gdpr_geo_cc_desc"><?php esc_html_e( '(users from other countries will not see the Cookie Banner and will have all cookies enabled by default)', 'gdpr-cookie-compliance-addon' ); ?></span>
							</label>
							
							<br />

							<div class="gdpr-geo-countries-cnt">
								<select name="gdpr_geo_countries[]" id="gdpr_geo_countries" multiple>
									<?php foreach ( $countries as $country ) : ?>
										<option value="<?php echo $country['abbreviation']; ?>" <?php echo in_array( $country['abbreviation'], $selected_countries ) ? 'selected="selected"' : ''; ?>><?php echo $country['country']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<!--  .gdpr-geo-countries-cnt -->
									
						<?php endif; ?>

					</fieldset>
					<br />
					<hr />
					<br />
					<p class="description" id="moove_gdpr_cc_geo_setup_eu-description">
						<span class="gdpr_geo_cc_desc">
							<?php
							// translators: %s link to third party.

							printf( 
								esc_html__( 'This functionality is using 3rd party service IP geolocation by %s to determine the location of the users, based on anonymised format of the user’s IP address.', 'gdpr-cookie-compliance-addon' ), 
								'<a href="http://www.geoplugin.com/" class="gdpr_admin_link" target="_new">geoPlugin</a>' 
							);
							?>
							<?php							
							esc_html_e( 'If the 3rd party service is temporarily unavailable, the banner will be shown to all users.', 'gdpr-cookie-compliance-addon' );					
							?>
				  	</span>
				  </p>
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
<script type="text/javascript" src="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/js/gdpr_cc_select2.js"></script>
