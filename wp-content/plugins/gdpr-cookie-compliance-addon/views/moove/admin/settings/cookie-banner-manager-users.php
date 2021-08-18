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

	if ( isset( $_POST ) && isset( $_POST['moove_gdpr_nonce'] )  ) :
		$nonce = sanitize_key( $_POST['moove_gdpr_nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_nonce_field' ) ) :
			die( 'Security check' );
		else :
			if ( is_array( $_POST ) ) :        
				if ( isset( $_POST['gdpr_hide_by_role'] ) && is_array( $_POST['gdpr_hide_by_role'] ) ) :
					$gdpr_options['gdpr_hide_by_role'] = json_encode( $_POST['gdpr_hide_by_role'] );
				else :
					$gdpr_options['gdpr_hide_by_role'] = json_encode( array() );
				endif;

				if ( isset( $_POST['gdpr_load_plugin'] ) ) :
					$gdpr_options['gdpr_load_plugin'] = intval( $_POST['gdpr_load_plugin'] );
				endif;

				update_option( $option_name, $gdpr_options );
				$gdpr_options = get_option( $option_name );
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

<form action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=cookie-banner-manager&cbm-type=users" method="post" id="moove_gdpr_tab_fsm_settings_users">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<h2><?php esc_html_e( 'Hide Cookie Banner', 'gdpr-cookie-compliance-addon' ); ?></h2>
	<hr />

	<ul class="gdpr-disable-posts-nav moove-clearfix">
			<li></li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=cookie-banner-manager&cbm-type=post_type" style="padding-left: 0;">Posts / Pages</a>
			</li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=cookie-banner-manager&cbm-type=users" class="active">Users</a>
			</li>
		</ul>


	<?php wp_nonce_field( 'moove_gdpr_perm_nonce_field', 'moove_gdpr_perm_nonce' ); ?>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row" style="vertical-align: top;">
					<label><?php _e('Show cookie banner for:','gdpr-cookie-compliance-addon'); ?></label>
				</th>
				<td>
					<fieldset>                        
						<label for="gdpr_load_plugin_1">
							<input name="gdpr_load_plugin" type="radio" <?php echo isset( $gdpr_options['gdpr_load_plugin'] ) ? ( intval( $gdpr_options['gdpr_load_plugin'] ) === 1  ? 'checked' : '' ) : 'checked'; ?> id="gdpr_load_plugin_1" value="1">
							<?php _e('All users (loads for everyone: website visitors and CMS users)','gdpr-cookie-compliance-addon'); ?>
						</label> <br />
						<label for="gdpr_load_plugin_2">
							<input name="gdpr_load_plugin" type="radio" <?php echo isset( $gdpr_options['gdpr_load_plugin'] ) ? ( intval( $gdpr_options['gdpr_load_plugin'] ) === 2  ? 'checked' : '' ) : ''; ?> id="gdpr_load_plugin_2" value="2">
							<?php _e('Only logged-in users','gdpr-cookie-compliance-addon'); ?>
						</label><br />
						<label for="gdpr_load_plugin_3">
							<input name="gdpr_load_plugin" type="radio" <?php echo isset( $gdpr_options['gdpr_load_plugin'] ) ? ( intval( $gdpr_options['gdpr_load_plugin'] ) === 3  ? 'checked' : '' ) : ''; ?> id="gdpr_load_plugin_3" value="3">
							<?php _e('Only users who are not logged-in','gdpr-cookie-compliance-addon'); ?>
						</label>

					</fieldset>  
				</td>
			</tr>

			<tr class="<?php echo isset( $gdpr_options['gdpr_load_plugin'] ) && intval( $gdpr_options['gdpr_load_plugin'] ) !== 3 ? '' : ( ! isset( $gdpr_options['gdpr_load_plugin'] ) ? '' : 'moove-hidden' ); ?>" id="gdpr_hide_by_role_cnt">
				<th valign="top" style="padding-top: 10px; vertical-align: top; padding-top: 25px;"> 
						<label><?php _e('Banner visibility by user role:','gdpr-cookie-compliance-addon'); ?></label>
				</th>
				<td>
					<?php
						$role_options = '';
						$roles = get_editable_roles();
						$selected_roles = isset( $gdpr_options['gdpr_hide_by_role'] ) ? json_decode( $gdpr_options['gdpr_hide_by_role'], true ) : array();
						ob_start();
						if ( $roles ) :
							?>
								<table class="gdpr-user-exclude-table">
									<?php foreach ( $roles as $role_key => $role ) : ?>
										<tr>
											<td style="padding: 5px">
												<?php echo $role['name']; ?>
											</td>
											<td style="padding: 5px">
												<label class="gdpr-checkbox-toggle gdpr-checkbox-inverted">
													<input type="checkbox" name="gdpr_hide_by_role[]" id="gdpr_hide_by_role<?php echo $role_key; ?>" <?php echo in_array( $role_key, $selected_roles ) ? 'checked="true"' : ''; ?> value="<?php echo $role_key; ?>" >
													<span class="gdpr-checkbox-slider" data-enable="Visible" data-disable="Hidden"></span>
												</label>
											</td>
										</tr>
									<?php endforeach;	?>
								</table>
							<?php 
						endif;
						$role_options .= ob_get_clean();
						echo $role_options; 
					?>
				</td>
			</tr>
			
			<?php do_action('gdpr_hide_by_role_type'); ?>
		</tbody>
	</table>
	<br />
	<button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'gdpr-cookie-compliance-addon' ); ?></button>

	<?php do_action('gdpr_cc_general_buttons_settings'); ?>
</form>
