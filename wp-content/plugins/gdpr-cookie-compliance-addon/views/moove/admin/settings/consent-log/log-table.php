<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php wp_nonce_field( 'cc_single_delete_nonce', 'cc_single_delete_nonce_trash' ); ?>
<table class="gdpr-consent-log-table dataTable row-border stripe" style="width:100%" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">
	<thead>
	 	<tr>
     	<th><?php _e('Consent Date','gdpr-cookie-compliance-addon'); ?></th>
     	<th><?php _e('IP Address','gdpr-cookie-compliance-addon'); ?></th>
     	<th><?php _e('Cookies Accepted','gdpr-cookie-compliance-addon'); ?></th>
     	<th><?php _e('User Details','gdpr-cookie-compliance-addon'); ?></th>
     	<th class="no-sort"></th>
	 	</tr>
	</thead>
 	<tbody>
		<?php if ( $data && is_array( $data ) ) : 
			/**
			 * Date formats from option
			 */
			$date_format = get_option('date_format');
			$time_format = get_option('time_format');


			/**
			 * Cookie category names 
			 */
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
			$gdpr_options         = get_option( $option_name );
			$count 								= 0;

			$strict_label = isset( $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ) : esc_html__( 'Strictly Necessary Cookies', 'gdpr-cookie-compliance' );

			$thirdparty_label = isset( $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ) : esc_html__( '3rd Party Cookies', 'gdpr-cookie-compliance' );

			$advanced_label = isset( $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ) : esc_html__( 'Additional Cookies', 'gdpr-cookie-compliance' );

			?>
			<?php foreach ( $data as $log_entry ) : $count++; ?>
				<tr data-id="<?php echo esc_attr( isset( $log_entry->id ) && intval( $log_entry->id ) ? $log_entry->id : 'false' ); ?>">
					
					<td data-order="<?php echo $log_entry->log_date ; ?>"><?php echo esc_attr( isset( $log_entry->log_date ) ? date( $date_format . ' @ ' . $time_format, $log_entry->log_date ) : 'false' ); ?></td>
					<td><?php echo esc_attr( isset( $log_entry->ip_address ) ? $log_entry->ip_address : 'false' ); ?></td>
					<td>
						<?php 
							$strict 		= 'no';
							$thirdpary 	= 'no';
							$advanded 	= 'no';

							$cookies 		= isset( $log_entry->option_value ) ? json_decode( $log_entry->option_value, true ) : false;
							if ( $cookies ) :
								$strict 			= isset( $cookies['strict'] ) && intval( $cookies['strict'] ) ? 'yes' : 'no';
								$thirdparty 	= isset( $cookies['thirdparty'] ) && intval( $cookies['thirdparty'] ) ? 'yes' : 'no';
								$advanced 		= isset( $cookies['advanced'] ) && intval( $cookies['advanced'] ) ? 'yes' : 'no';
							endif;

							$cookies_stored = array();

							$cookies_stored[] = array(
								'label'	=> $strict_label,
								'value'	=> $strict === 'yes' ? '<span title="YES" class="gdpr-cc-bt gdpr-bt-yes">&#10003;</span>' : '<span class="gdpr-cc-bt gdpr-bt-no" title="NO">&#10007;</span>'
							);
							if ( isset( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) :
								$cookies_stored[] = array(
									'label'	=> $thirdparty_label,
									'value'	=> $thirdparty === 'yes' ? '<span title="YES" class="gdpr-cc-bt gdpr-bt-yes">&#10003;</span>' : '<span class="gdpr-cc-bt gdpr-bt-no" title="NO">&#10007;</span>'
								);
							endif;
							if ( isset( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) :
								$cookies_stored[] = array(
									'label'	=> $advanced_label,
									'value'	=> $advanced === 'yes' ? '<span title="YES" class="gdpr-cc-bt gdpr-bt-yes">&#10003;</span>' : '<span class="gdpr-cc-bt gdpr-bt-no" title="NO">&#10007;</span>'
								);
							endif;

						?>
						<?php if ( is_array( $cookies_stored ) ) : ?>
							<table class="gdpr_consent_log_cookie_list">
								<?php foreach ( $cookies_stored as $cookie_value ) : ?>
									<?php if ( isset( $cookie_value['label'] ) && $cookie_value['value'] ) : ?>
										<tr>
											<td><?php echo $cookie_value['label']; ?>: </td>
											<td><strong><?php echo $cookie_value['value']; ?></strong></td>
										</tr>
									<?php endif; ?>
								<?php endforeach; ?>
							</table>
						<?php endif; ?>
					</td>
					<td><?php echo esc_attr( isset( $log_entry->user_id ) ? apply_filters('gdpr_consent_log_user_info', $log_entry->user_id ) : 'Not logged in' ); ?></td>
					<td>
						<a href="#" class="gdpr_delete_cc_single"><span class="dashicons dashicons-trash"></span></a>
					</td>
				</tr>
			<?php	endforeach; ?> 
		<?php else:  ?>
			<tr>
				<td colspan="5"><?php _e( "You don't have any entries in this log yet.", "gdpr-cookie-compliance-addon" ); ?></td>
			</tr>
		<?php endif; ?>
 	</tbody>
</table>