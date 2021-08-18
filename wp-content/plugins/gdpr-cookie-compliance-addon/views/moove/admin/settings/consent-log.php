<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<h2><?php esc_html_e( 'Consent Log', 'gdpr-cookie-compliance-addon' ); ?></h2>
<link rel="stylesheet" type="text/css" href="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/css/gdpr_cc_datepicker.css" >
<link rel="stylesheet" type="text/css" href="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/css/gdpr_cc_datatables.css" >
<?php
	$gdpr_default_content = new Moove_GDPR_Content();
	$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
	$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
	$gdpr_options         = get_option( $option_name );
	$filter_start_int 		= apply_filters('gdpr_consent_log_start_interval', '1 week ago');
	$filter_start_date    = date( 'Y-m-d', strtotime( $filter_start_int ) );
	$filter_end_date      = date( 'Y-m-d', strtotime( 'today' ) );
	$addon_controller     = new Moove_GDPR_Addon_Controller();
	if ( isset( $_POST ) && isset( $_POST['gdpr_submit_type'] ) ) :
		wp_verify_nonce( 'ga_nonce', 'gdpr_addon_nonce' );
		$updated     = false;
		$submit_type = sanitize_text_field( wp_unslash( $_POST['gdpr_submit_type'] ) );
		$nonce       = isset( $_POST[ 'moove_gdpr_cl_nonce_' . $submit_type ] ) ? sanitize_key( $_POST[ 'moove_gdpr_cl_nonce_' . $submit_type ] ) : false;
		if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_cl_' . $submit_type ) ) :
			die( 'Security check' );
		else :
				if ( is_array( $_POST ) ) :
					if ( 'settings' === $submit_type ) :
						if ( isset( $_POST['moove_gdpr_consent_log_enable'] ) ) :
							$value = 1;
						else :
							$value = 0;
						endif;
						$updated = isset( $gdpr_options['moove_gdpr_consent_log_enable'] ) && intval( $gdpr_options['moove_gdpr_consent_log_enable'] ) === $value ? false : true;
						$gdpr_options['moove_gdpr_consent_log_enable'] = $value;
						update_option( $option_name, $gdpr_options );
					elseif ( 'filters' === $submit_type ) :
						$start_date        = isset( $_POST['gdpr_consentlog_datepicker_start'] ) ? sanitize_text_field( wp_unslash( $_POST['gdpr_consentlog_datepicker_start'] ) ) : false;
						$end_date          = isset( $_POST['gdpr_consentlog_datepicker_end'] ) ? sanitize_text_field( wp_unslash( $_POST['gdpr_consentlog_datepicker_end'] ) ) : false;
						$filter_end_date   = $end_date ? date( 'Y-m-d', strtotime( $end_date ) ) : date( 'Y-m-d', strtotime( 'today' ) );
						$filter_start_date = $start_date ? date( 'Y-m-d', strtotime( $start_date ) ) : date( 'Y-m-d', strtotime( '1 month ago' ) );
					elseif ( 'trash' === $submit_type ) :
						$log_controller = new Moove_GDPR_Consent_Log();
						$log_controller->reset_log();
					elseif ( 'export' === $submit_type ) :

						$start_date        	= isset( $_POST['gdpr_consentlog_export_start'] ) ? sanitize_text_field( wp_unslash( $_POST['gdpr_consentlog_export_start'] ) ) : false;
						$end_date          	= isset( $_POST['gdpr_consentlog_export_end'] ) ? sanitize_text_field( wp_unslash( $_POST['gdpr_consentlog_export_end'] ) ) : false;

						$export_end_date   	= $end_date ? date( 'Y-m-d', strtotime( $end_date ) ) : date( 'Y-m-d', strtotime( 'today' ) );
						$export_start_date 	= $start_date ? date( 'Y-m-d', strtotime( $start_date ) ) : date( 'Y-m-d', strtotime( '1 month ago' ) );
						$start_filter 		 	= strtotime( $export_start_date );
						$end_filter 		 		= strtotime( $export_end_date );

						$export_url		 			= admin_url( 'admin.php?page=moove-gdpr&tab=consent-log&consent_export='.$start_filter.'_'.$end_filter );
						?>
						<iframe src="<?php echo $export_url; ?>" style="display: none;" frameborder="0"></iframe>
						<?php

					endif;
					do_action( 'gdpr_cookie_filter_settings' );
				endif;
				?>
				<?php if ( $updated ) : ?>
					<script>
						jQuery('#moove-gdpr-setting-error-settings_scripts_empty').hide();
						jQuery('#moove-gdpr-setting-error-settings_updated').show();
					</script>
					<?php
				endif;
		endif;
	endif;
	$gdpr_options = get_option( $option_name );

	$enabled_analytics = isset( $gdpr_options['moove_gdpr_consent_log_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_consent_log_enable'] ) === 1 ? true : false ) : false;

	$consent_controller 	= new Moove_GDPR_Consent_Log();
	$log_results 					= $consent_controller->get_log_entry( $filter_start_date, $filter_end_date );

?>
<div class="gdpr-analytics-filters">
	<div class="gdpr-toggle-panel">
		<div class="gdpr-toggle-panel-heading">
			<h4><span class="dashicons dashicons-admin-tools"></span> <?php esc_html_e( 'Setup & Filters', 'gdpr-cookie-compliance-addon' ); ?></h4>
			<div class="buttons-container">
				<?php if ( $enabled_analytics ) : ?>
					<a href="#gdpr-analytics-settings" title="<?php esc_html_e( 'Consent Log Settings', 'gdpr-cookie-compliance-addon' ); ?>"><span class="dashicons dashicons-admin-settings"></span> <span><?php esc_html_e( 'Settings', 'gdpr-cookie-compliance-addon' ); ?></span></a>
					<a href="#gdpr-analytics-filters" title="<?php esc_html_e( 'Filter results', 'gdpr-cookie-compliance-addon' ); ?>"><span class="dashicons dashicons-filter"></span> <span><?php esc_html_e( 'Filters', 'gdpr-cookie-compliance-addon' ); ?></span></a>
					<?php if ( $log_results ) : ?>
						<a href="#gdpr-consent-log-trash" title="<?php esc_html_e( 'Delete All Logs', 'gdpr-cookie-compliance-addon' ); ?>"><span class="dashicons dashicons-trash"></span> <span><?php esc_html_e( 'Delete All Logs', 'gdpr-cookie-compliance-addon' ); ?></span></a>

						<a href="#gdpr-consent-log-export" title="<?php esc_html_e( 'Export Logs', 'gdpr-cookie-compliance-addon' ); ?>"><span class="dashicons dashicons-download"></span> <span><?php esc_html_e( 'Export Logs', 'gdpr-cookie-compliance-addon' ); ?></span></a>

					<?php endif; ?>
				<?php endif; ?>
			</div>
			<!--  .buttons-container -->			
		</div>
		<!--  .gdpr-toggle-panel-heading -->
		<div class="gdpr-toggle-content" id="gdpr-analytics-filters" style="display: none;">
			<form action="#" method="post" autocomplete="off">
				<?php wp_nonce_field( 'moove_gdpr_cl_filters', 'moove_gdpr_cl_nonce_filters' ); ?>
				<input type="hidden" autocomplete="off" name="gdpr_submit_type" value="filters" />		
				<div class="filter-action-box">
					<h3><strong><?php esc_html_e( 'Filter results by date', 'gdpr-cookie-compliance-addon' ); ?></strong></h3>
					<label for="gdpr_consentlog_datepicker_start" class="date-input">
						<span class="label-text"><?php esc_html_e( 'Start Date', 'gdpr-cookie-compliance-addon' ); ?>:</span>			
						<span class="dashicons dashicons-calendar-alt"></span>
						<input type="text" id="gdpr_consentlog_datepicker_start" autocomplete="off" name="gdpr_consentlog_datepicker_start" required value="<?php echo esc_attr( date( 'Y-m-d', strtotime( $filter_start_date ) ) ); ?>">
					</label>
					<label for="gdpr_consentlog_datepicker_end" class="date-input">
						<span class="label-text"><?php esc_html_e( 'End Date', 'gdpr-cookie-compliance-addon' ); ?>:</span>
						<span class="dashicons dashicons-calendar-alt"></span>
						<input type="text" id="gdpr_consentlog_datepicker_end" autocomplete="off" name="gdpr_consentlog_datepicker_end" required value="<?php echo esc_attr( date( 'Y-m-d', strtotime( $filter_end_date ) ) ); ?>">
					</label>
					<div class="submit-gdpr-button-cnt">
						<button type="submit" class="button button-primary"><?php esc_html_e( 'Filter Results', 'gdpr-cookie-compliance-addon' ); ?></button>
					</div>
					<!--  .submit-gdpr-button-cnt -->	
					<div class="moove-clearfix"></div><!--  .moove-clearfix -->
				</div>
				<!--  .filter-action-box -->	
			</form>
		</div>
		<!--  .gdpr-toggle-content -->
		<div class="gdpr-toggle-content" id="gdpr-consent-log-trash" style="display: none;">
			<form action="#" method="post">		
				<?php wp_nonce_field( 'moove_gdpr_cl_trash', 'moove_gdpr_cl_nonce_trash' ); ?>
				<input type="hidden" name="gdpr_submit_type" value="trash" />			
				<div class="filter-action-box">
					<h3><strong><?php esc_html_e( 'Delete All Logs', 'gdpr-cookie-compliance-addon' ); ?></strong></h3>
					<p class="description desc-error"><strong><?php esc_html_e( 'Warning', 'gdpr-cookie-compliance-addon' ); ?>:</strong> <?php esc_html_e( 'This will remove all existing logs that are stored and reset the logs tracking to the default settings.', 'gdpr-cookie-compliance-addon' ); ?></p>
					<!--  .description -->
					<div class="submit-gdpr-button-cnt">
						<button type="submit" id="gdpr_remove_all_consent_logs" class="button button-error"><?php esc_html_e( 'Delete All Logs', 'gdpr-cookie-compliance-addon' ); ?></button>
					</div>
					<!--  .submit-gdpr-button-cnt -->
				</div>
				<!--  .filter-action-box -->			
			</form>
		</div>
		<!--  .gdpr-toggle-content -->
	
		<div class="gdpr-toggle-content" id="gdpr-consent-log-export" style="display: none;">
			<form action="#" method="post">		
				<?php wp_nonce_field( 'moove_gdpr_cl_export', 'moove_gdpr_cl_nonce_export' ); ?>
				<input type="hidden" name="gdpr_submit_type" value="export" />			
				<div class="filter-action-box">
					<h3><strong><?php esc_html_e( 'Export Logs', 'gdpr-cookie-compliance-addon' ); ?></strong></h3>
					
					<label for="gdpr_consentlog_export_start" class="date-input">
						<span class="label-text"><?php esc_html_e( 'Start Date', 'gdpr-cookie-compliance-addon' ); ?>:</span>			
						<span class="dashicons dashicons-calendar-alt"></span>
						<input type="text" id="gdpr_consentlog_export_start" autocomplete="off" name="gdpr_consentlog_export_start" required value="<?php echo esc_attr( date( 'Y-m-d', strtotime( $filter_start_date ) ) ); ?>">
					</label>
					<label for="gdpr_consentlog_export_end" class="date-input">
						<span class="label-text"><?php esc_html_e( 'End Date', 'gdpr-cookie-compliance-addon' ); ?>:</span>
						<span class="dashicons dashicons-calendar-alt"></span>
						<input type="text" id="gdpr_consentlog_export_end" autocomplete="off" name="gdpr_consentlog_export_end" required value="<?php echo esc_attr( date( 'Y-m-d', strtotime( $filter_end_date ) ) ); ?>">
					</label>
					<div class="submit-gdpr-button-cnt">
						<button type="submit" class="button button-primary"><?php esc_html_e( 'Download Logs', 'gdpr-cookie-compliance-addon' ); ?></button>
					</div>
					<!--  .submit-gdpr-button-cnt -->	
					<div class="moove-clearfix"></div><!--  .moove-clearfix -->
				</div>
				<!--  .filter-action-box -->			
			</form>
		</div>
		<!--  .gdpr-toggle-content -->

		<div class="gdpr-toggle-content" id="gdpr-analytics-settings" <?php echo $enabled_analytics ? 'style="display: none;"' : ''; ?>>
			<form action="#" method="post">	
				<?php wp_nonce_field( 'moove_gdpr_cl_settings', 'moove_gdpr_cl_nonce_settings' ); ?>
				<input type="hidden" name="gdpr_submit_type" value="settings" />
				<div class="filter-action-box">
					<div class="enable-gdpr-analytics">						
						<table class="form-table">
							<tbody>
								<tr>									
									<td style="padding-left: 0;">
										<!-- GDPR Rounded switch -->
										<label class="gdpr-checkbox-toggle">
											<input type="checkbox" name="moove_gdpr_consent_log_enable" id="moove_gdpr_consent_log_enable" <?php echo isset( $gdpr_options['moove_gdpr_consent_log_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_consent_log_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_consent_log_enable'] ) ? 'checked' : '' ) ) : ''; ?> >
											<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
										</label>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!--  .enable-gdpr-analytics -->
					<p class="description"><?php esc_html_e( 'Please note this feature may slow down your website as every user interaction with the Cookie Notice Banner or Cookie Settings Screen will be recorded.', 'gdpr-cookie-compliance-addon' ); ?></p>
					<!--  .description -->
					
					<div class="submit-gdpr-button-cnt">
						<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Changes', 'gdpr-cookie-compliance-addon' ); ?></button>
					</div>
					<!--  .submit-gdpr-button-cnt -->
				</div>
				<!--  .filter-action-box -->			
			</form>
		</div>
		<!--  .gdpr-toggle-content -->
	</div>
	<!--  .gdpr-toggle-panel -->
	<?php if ( $enabled_analytics ) : ?>
		<div class="gdpr-current-filter">
			<p><strong><?php esc_html_e( 'Active filters', 'gdpr-cookie-compliance-addon' ); ?>: </strong><?php echo esc_attr( date_i18n( get_option( 'date_format' ), strtotime( $filter_start_date ) ) ); ?> - <?php echo esc_attr( date_i18n( get_option( 'date_format' ), strtotime( $filter_end_date ) ) ); ?></p>
		</div>
		<!--  .gdpr-current-filter -->
	<?php endif; ?>
</div>
<!--  .gdpr-analytics-filters -->

<?php 
	if ( $enabled_analytics ) : 
		$view_cnt = new Moove_GDPR_Addon_View();
		$content 	= $view_cnt->load( 'moove.admin.settings.consent-log.log-table', $log_results );
		apply_filters( 'gdpr_addon_keephtml', $content, true );
	endif; 
?>

<script type="text/javascript" src="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/js/gdpr_cc_datatables.js"></script>
<script type="text/javascript" src="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/js/gdpr_cc_datepicker.js"></script>
<script>
	var consent_log_table = jQuery('.gdpr-consent-log-table');
	jQuery(document).ready(function(){
		try {
			consent_log_table.DataTable({
				"order": [[ 0, "desc" ]],
				columnDefs: [
				  { targets: 'no-sort', orderable: false }
				]
			});
		} catch(err) {
		}
	});
</script>
