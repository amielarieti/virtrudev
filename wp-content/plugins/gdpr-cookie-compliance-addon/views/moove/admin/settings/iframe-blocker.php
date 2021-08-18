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
			if ( isset( $_POST['moove_gdpr_modal_enable_ifb'] ) ) :
				$value = 1;
			else :
				$value = 0;
			endif;
			$gdpr_options['moove_gdpr_modal_enable_ifb'] = $value;

			if ( isset( $_POST['moove_gdpr_ifb_content'] ) ) :
				$allowed_tags = wp_kses_allowed_html( 'post' );
				$value        = wp_kses( wp_unslash( $_POST['moove_gdpr_ifb_content'] ), $allowed_tags );
				$gdpr_options[ 'moove_gdpr_ifb_content' . $wpml_lang ] = $value;
			endif;

			if ( isset( $_POST['moove_gdpr_ifbc'] ) ) :
				$value        = sanitize_text_field( wp_unslash( $_POST['moove_gdpr_ifbc'] ) );
				$value 				= in_array( $value, array( 'strict', 'thirdparty', 'advanced' ) ) ? $value : 'strict';
				$gdpr_options['moove_gdpr_ifbc'] = $value;
			endif;
			$excluded_val 		= array();
			if ( isset( $_POST['gdpr-ifb-exclusion'] ) && $_POST['gdpr-ifb-exclusion'] ) :
				$excluded_strings = nl2br( $_POST['gdpr-ifb-exclusion'] );
				$excluded_strings = str_replace( '<br>', '<br />', $excluded_strings );
				$excluded_strings = explode( '<br />', $excluded_strings );
				
				if ( is_array( $excluded_strings ) ) :
					foreach ( $excluded_strings as $ifb_excl_value ) :
						$sanitized_value = sanitize_text_field( wp_unslash( trim( $ifb_excl_value ) ) );
						if ( $sanitized_value ) :
							$excluded_val[] = $sanitized_value;
						endif;
					endforeach;
				endif;
			endif;
			$gdpr_options['gdpr-ifb-exclusion'] = json_encode( $excluded_val );
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
<form action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=iframe-blocker" method="post" id="moove_gdpr_tab_ifb_settings">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<h2><?php esc_html_e( 'Iframe Blocker', 'gdpr-cookie-compliance-addon' ); ?></h2>
	<hr />
	<table class="form-table">
		<tbody>
			<tr>
				<td style="padding-left: 0;">
					<!-- GDPR Rounded switch -->
					<label class="gdpr-checkbox-toggle">
						<input type="checkbox" name="moove_gdpr_modal_enable_ifb" id="moove_gdpr_modal_enable_ifb" <?php echo isset( $gdpr_options['moove_gdpr_modal_enable_ifb'] ) ? ( intval( $gdpr_options['moove_gdpr_modal_enable_ifb'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_modal_enable_ifb'] ) ? 'checked' : '' ) ) : ''; ?> >
						<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
					</label>
					<br /><br />

					<p class="description">This feature only works with the default WYSIWYG editor (the_content filter) <hr /></p>
					<!--  .description -->
				</td>
			</tr>

			<tr>
				<th scope="row" colspan="2" style="padding-bottom: 0;">
					<label for="moove_gdpr_ifb_content" style="margin-bottom: 0"><?php esc_html_e( 'Iframe Blocker Content', 'gdpr-cookie-compliance' ); ?></label>
				</th>
			</tr>
			<tr class="moove_gdpr_table_form_holder">
				<td colspan="2" scope="row" style="padding-left: 0;">
					<?php
						$content = isset( $gdpr_options[ 'moove_gdpr_ifb_content' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_ifb_content' . $wpml_lang ] ? wp_unslash( $gdpr_options[ 'moove_gdpr_ifb_content' . $wpml_lang ] ) : false;
					if ( ! $content ) :
						$content = $gdpr_default_content->moove_gdpr_ifb_content();
						endif;
					?>
					<?php
						$settings = array(
							'media_buttons' => false,
							'editor_height' => 150,
						);
						wp_editor( $content, 'moove_gdpr_ifb_content', $settings );
						?>
					<p class="description"><?php esc_html_e( 'You can use the following shortcut to link the Cookie Settings Screen: <span><strong>[setting]</strong>Adjust your settings<strong>[/setting]</strong></span>', 'gdpr-cookie-compliance' ); ?></p>
					<p class="description"><?php esc_html_e( 'You can use the following shortcut to display the "Accept" button: <span><strong>[accept]</strong>Accept<strong>[/accept]</strong></span>', 'gdpr-cookie-compliance' ); ?></p>
				</td>
			</tr>

			<tr>
				<td colspan="2" style="padding: 0;">
					<hr />
					<h4><?php esc_html_e( 'Hide Iframe Blocker automatically if the user enabled the following cookies', 'gdpr-cookie-compliance-addon' ); ?>: </h4>
					<fieldset>
						<label for="moove_gdpr_ifbc_strict">
							<?php 
								$ifbc_value 					= isset( $gdpr_options['moove_gdpr_ifbc'] ) ? ( $gdpr_options['moove_gdpr_ifbc'] ) : 'strict';
								$third_party_allowed 	= isset( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) === 1;
								$advanced_allowed 		= isset( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) === 1;
								$ifbc_value  					= $ifbc_value === 'thirdparty' && ! $third_party_allowed ? 'strict' : $ifbc_value;
								$ifbc_value  					= $ifbc_value === 'advanced' && ! $advanced_allowed ? 'strict' : $ifbc_value;

							?>
							<input name="moove_gdpr_ifbc" type="radio" <?php echo $ifbc_value === 'strict' ? 'checked' : ''; ?> id="moove_gdpr_ifbc_strict" value="strict">
							<?php 
								$nav_label = isset( $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] : __( 'Strictly Necessary Cookies', 'gdpr-cookie-compliance' );
								echo $nav_label;
							?>
						</label> 
						
						<?php if ( $third_party_allowed ) : ?>
							<span class="separator"></span>
							<br />
							
							<label for="moove_gdpr_ifbc_thirdparty">
								<input name="moove_gdpr_ifbc" type="radio" <?php echo $ifbc_value === 'thirdparty' ? 'checked' : ''; ?> id="moove_gdpr_ifbc_thirdparty" value="thirdparty">
								<?php 
									$nav_label = isset( $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] : __( '3rd Party Cookies', 'gdpr-cookie-compliance' );
									echo $nav_label;
								?>
							</label> 
						<?php endif; ?>
						
						<?php if ( $advanced_allowed ) : ?>

							<span class="separator"></span>
							<br />
							
							<label for="moove_gdpr_ifbc_advanced">
								<input name="moove_gdpr_ifbc" type="radio" <?php echo $ifbc_value === 'advanced' ? 'checked' :  ''; ?> id="moove_gdpr_ifbc_advanced" value="advanced">
								<?php 
									$nav_label = isset( $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] : __( 'Additional Cookies', 'gdpr-cookie-compliance' );
									echo $nav_label;
								?>
							</label> 
						<?php endif; ?>

					</fieldset>
				</td>
			</tr>

			<tr>
				<td colspan="2" style="padding: 0;">
					<hr />
					<h4><?php esc_html_e( 'Exclude iframes from blocking', 'gdpr-cookie-compliance-addon' ); ?>: </h4>
					<p class="description"><?php esc_html_e( 'Specify URLs (or portion of URLs) of iFrames to be excluded from blocking (one per line).', 'gdpr-cookie-compliance' ); ?></p>
					<?php 
					$excluded_urls = isset( $gdpr_options['gdpr-ifb-exclusion'] ) && $gdpr_options['gdpr-ifb-exclusion'] ? json_decode( $gdpr_options['gdpr-ifb-exclusion'], true ) : array();
					?>
					<textarea cols="10" class="gdpr-ifb-exclusion" name="gdpr-ifb-exclusion" rows="10"><?php echo implode( '&#013;', $excluded_urls ); ?></textarea>
				</td>
			</tr>

			<tr>
				<th colspan="2">
					<hr />
					<label>Blocked iframe example (with default settings):</label>
					<br /><br />
					<img src="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/images/iframe-blocker-shortcode.png?cache=<?php echo esc_attr( strtotime( 'now' ) ); ?>" class="gdpr-img-responsive" alt="Blocked iframe preview" />
				</th>
			</tr>
		</tbody>
	</table>

	<br />
	<hr />
	<br />
	<button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'gdpr-cookie-compliance-addon' ); ?></button>
	<?php do_action( 'gdpr_cc_banner_buttons_settings' ); ?>
</form>
