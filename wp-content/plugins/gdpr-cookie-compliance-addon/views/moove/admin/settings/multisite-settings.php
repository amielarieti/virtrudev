<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly

	$gdpr_default_content = new Moove_GDPR_Content();
	$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
	$gdpr_options         = get_option( $option_name );
	$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
	$gdpr_options         = is_array( $gdpr_options ) ? $gdpr_options : array();
	$empty_scripts        = false;
	if ( isset( $_POST ) && ( isset( $_POST['gdpr-multisite-copy-settings'] ) || isset( $_POST['gdpr-multisite-manage-settings'] ) ) ) :
		wp_verify_nonce( 'ga_nonce', 'gdpr_addon_nonce' );
		$_type = isset( $_POST['moove_gdpr_nonce_copy'] ) ? 'copy' : ( isset( $_POST['moove_gdpr_nonce_manage'] ) ? 'manage' : 'multisite' );
		$nonce = isset( $_POST[ 'moove_gdpr_nonce_' . $_type ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'moove_gdpr_nonce_' . $_type ] ) ) : false;
		if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_nonce_multisite_' . $_type ) ) :
			die( 'Security check' );
		endif;
		if ( isset( $_POST['gdpr-multisite-manage-settings'] ) ) :
			if ( isset( $_POST['moove_gdpr_manage_settings_globally'] ) ) :
				$args  = array(
					'fields' => 'ids',
				);
				$sites = get_sites( $args );
				if ( $sites && is_array( $sites ) ) :
					$current_blog_settings = get_option( $option_name );
					$setting_actions       = new Moove_GDPR_Addon_Actions();
					$setting_fields        = $setting_actions->get_gdpr_setting_field_keys();
					if ( $setting_fields && is_array( $setting_fields ) ) :
						foreach ( $sites as $_blog_id ) :
							switch_to_blog( $_blog_id );
							$gdpr_options = get_option( $option_name );
							foreach ( $setting_fields as $field_key ) :
								if ( isset( $current_blog_settings[ $field_key ] ) ) :
									$gdpr_options[ $field_key ] = $current_blog_settings[ $field_key ];
								endif;
							endforeach;
							$gdpr_options['moove_gdpr_manage_settings_globally'] = '1';

							update_option( $option_name, $gdpr_options );
							
							delete_transient( 'gdpr_cookie_cache' );
							
							restore_current_blog();
						endforeach;
					endif;
				endif;
			else :
				$args  = array(
					'fields' => 'ids',
				);
				$sites = get_sites( $args );

				if ( $sites && is_array( $sites ) ) :
					foreach ( $sites as $_blog_id ) :
						switch_to_blog( $_blog_id );
						$gdpr_options                                        = get_option( $option_name );
						$gdpr_options['moove_gdpr_manage_settings_globally'] = '0';
						update_option( $option_name, $gdpr_options );
						restore_current_blog();
					endforeach;
				endif;
			endif;
			$success = true;
			?>
		  <div id="moove-gdpr-manage-settings_updated" class="<?php echo $success ? 'updated' : 'error'; ?> settings-error notice is-dismissible gdpr-cc-notice" style="display:none;">
				<p><strong><?php esc_html_e( 'Settings saved', 'gdpr-cookie-compliance-addon' ); ?></strong></p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'gdpr-cookie-compliance-addon' ); ?></span>
				</button>
			</div>

			<script>
				jQuery('#moove-gdpr-manage-settings_updated').show();
			</script>
			<?php
		endif;
		if ( isset( $_POST['gdpr-multisite-copy-settings'] ) ) :
			$success = true;
			if ( isset( $_POST['moove_gdpr_copy_settings'] ) && is_array( $_POST['moove_gdpr_copy_settings'] ) ) :
				$current_blog_settings = get_option( $option_name );
				foreach ( array_map( 'sanitize_text_field', wp_unslash( $_POST['moove_gdpr_copy_settings'] ) ) as $unsanitized_blog_id ) :
					$_blog_id = intval( $unsanitized_blog_id );
					if ( $_blog_id ) :
						switch_to_blog( $_blog_id );
						update_option( $option_name, $current_blog_settings );
						restore_current_blog();
						endif;
					endforeach;
				endif;
			?>
			<div id="moove-gdpr-manage-settings_updated" class="<?php echo $success ? 'updated' : 'error'; ?> settings-error notice is-dismissible gdpr-cc-notice" style="display:none;">
				<p><strong><?php esc_html_e( 'Settings copied!', 'gdpr-cookie-compliance-addon' ); ?></strong></p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'gdpr-cookie-compliance-addon' ); ?></span>
				</button>
			</div>

			<script>
				jQuery('#moove-gdpr-manage-settings_updated').show();
			</script>

			<?php
		endif;

	endif;
	$gdpr_options = get_option( $option_name );


?>
<h2><?php esc_html_e( 'Multisite Settings', 'gdpr-cookie-compliance-addon' ); ?></h2>
<hr />
<?php if ( is_multisite() ) : ?>
	<form action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=multisite-settings" method="post">
		
		<?php wp_nonce_field( 'moove_gdpr_nonce_multisite_copy', 'moove_gdpr_nonce_copy' ); ?>
		
		<table class="form-table">
			<tbody>
				<?php
					$args  = array(
						'fields'       => 'ids',
						'site__not_in' => get_current_blog_id(),
					);
					$sites = get_sites( $args );
				?>
					
				<?php if ( $sites && ! empty( $sites ) && count( $sites ) >= 1 ) : ?>
					<tr>
						<th scope="row">
							<label for="moove_gdpr_copy_settings"><?php esc_html_e( 'Copy All Settings', 'gdpr-cookie-compliance-addon' ); ?></label>
						</th>
						<td>				
							<input type="hidden" name="gdpr-multisite-copy-settings" value="true" />
							<?php foreach ( $sites as $blogid ) : ?>
								<?php $blog_details = get_blog_details( $blogid ); ?>                	
								<input name="moove_gdpr_copy_settings[]" type="checkbox" value="<?php echo esc_attr( $blogid ); ?>" id="moove_gdpr_copy_settings_<?php echo esc_attr( $blogid ); ?>" <?php echo isset( $gdpr_options['moove_gdpr_copy_settings'] ) ? ( intval( $gdpr_options['moove_gdpr_copy_settings'] ) === $blogid ? '' : '' ) : ''; ?> class="on-off"> <label for="moove_gdpr_copy_settings_<?php echo esc_attr( $blogid ); ?>"><?php echo esc_attr( $blog_details->blogname ); ?></label>
								<span class="separator"></span><br />
							<?php endforeach; ?>
							<br />
							<p class="description"><?php esc_html_e( 'This process will override all existing content on the selected sites (including code snippets and all texts). ', 'gdpr-cookie-compliance-addon' ); ?></p>        
							<br />
							
						</td>
					</tr>
				<?php endif; ?>
				
			</tbody>
		</table>
		<button type="submit" class="button button-primary"><?php esc_html_e( 'Copy All Settings', 'gdpr-cookie-compliance-addon' ); ?></button>   
		<br />
		<br />     
		<br />
	</form>
	<hr />
	<form action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=multisite-settings" method="post">
		<?php wp_nonce_field( 'moove_gdpr_nonce_multisite_manage', 'moove_gdpr_nonce_manage' ); ?>
		
		<table class="form-table">
			<tbody>
				
				<tr>
					<th scope="row">
						<label for="moove_gdpr_manage_settings_globally"><?php esc_html_e( 'Sync General Settings', 'gdpr-cookie-compliance-addon' ); ?></label>
					</th>
					<td>
						 <!-- GDPR Rounded switch -->
						<label class="gdpr-checkbox-toggle">
							<input type="checkbox" name="moove_gdpr_manage_settings_globally" id="moove_gdpr_manage_settings_globally" <?php echo isset( $gdpr_options['moove_gdpr_manage_settings_globally'] ) ? ( intval( $gdpr_options['moove_gdpr_manage_settings_globally'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_manage_settings_globally'] ) ? 'checked' : '' ) ) : ''; ?> >
							<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
						</label>
						<input type="hidden" name="gdpr-multisite-manage-settings" value="true" />
						<br /><br />	  
						<p class="description"><?php esc_html_e( 'If you enable this feature, all general settings will be constantly synced between all sites in your multisite network.', 'gdpr-cookie-compliance-addon' ); ?></p>          
						<p class="description"><?php esc_html_e( 'Code snippets and other editable text and labels will not be synchronised so that you can have different scripts and different description text on each subsite (ie. different language labels).', 'gdpr-cookie-compliance-addon' ); ?></p>       
					</td>
				</tr>
				
			</tbody>
		</table>
		<button type="submit" class="button button-primary"><?php esc_html_e( 'Save', 'gdpr-cookie-compliance-addon' ); ?></button>        
	</form>
<?php else : ?>
	<h4><?php esc_html_e( 'This feature is available only for WordPress Multisite installs!', 'gdpr-cookie-compliance-addon' ); ?></h4>
<?php endif; ?>
