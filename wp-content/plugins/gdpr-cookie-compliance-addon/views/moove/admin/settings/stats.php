<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<h2><?php esc_html_e( 'Analytics', 'gdpr-cookie-compliance-addon' ); ?></h2>
<link rel="stylesheet" type="text/css" href="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/css/gdpr_cc_datepicker.css" >
<?php
	
	$is_detailed_view 		= isset( $_GET['detailed_view'] ) ? true : false;

	$gdpr_default_content = new Moove_GDPR_Content();
	$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
	$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
	$gdpr_options         = get_option( $option_name );
	$filter_start_date    = date( 'Y-m-d', strtotime( '2 days ago' ) );
	$filter_end_date      = date( 'Y-m-d', strtotime( 'today' ) );
	$addon_controller     = new Moove_GDPR_Addon_Controller();

	$count_posts = $is_detailed_view ? wp_count_posts( 'gdpr_analytics' ) : 0;

	$published_posts = $is_detailed_view ? $count_posts->publish : 0;

	if ( isset( $_POST ) && isset( $_POST['gdpr_submit_type'] ) ) :
		wp_verify_nonce( 'ga_nonce', 'gdpr_addon_nonce' );
		$updated     = false;
		$submit_type = sanitize_text_field( wp_unslash( $_POST['gdpr_submit_type'] ) );
		$nonce       = isset( $_POST[ 'moove_gdpr_stats_nonce_' . $submit_type ] ) ? sanitize_key( $_POST[ 'moove_gdpr_stats_nonce_' . $submit_type ] ) : false;
		if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_stats_' . $submit_type ) ) :
			die( 'Security check' );
		else :
			if ( is_array( $_POST ) ) :

				if ( 'settings' === $submit_type ) :
					$value = isset( $_POST['moove_gdpr_premium_analytics_enable'] ) ? 1 : 0;
					$updated = isset( $gdpr_options['moove_gdpr_premium_analytics_enable'] ) && intval( $gdpr_options['moove_gdpr_premium_analytics_enable'] ) === $value ? false : true;
					$gdpr_options['moove_gdpr_premium_analytics_enable'] = $value;
					update_option( $option_name, $gdpr_options );
				elseif ( 'filters' === $submit_type ) :
					$start_date        = isset( $_POST['gdpr_analytics_datepicker_start'] ) ? sanitize_text_field( wp_unslash( $_POST['gdpr_analytics_datepicker_start'] ) ) : false;
					$end_date          = isset( $_POST['gdpr_analytics_datepicker_end'] ) ? sanitize_text_field( wp_unslash( $_POST['gdpr_analytics_datepicker_end'] ) ) : false;
					$filter_end_date   = $end_date ? date( 'Y-m-d', strtotime( $end_date ) ) : date( 'Y-m-d', strtotime( 'today' ) );
					$filter_start_date = $start_date ? date( 'Y-m-d', strtotime( $start_date ) ) : date( 'Y-m-d', strtotime( '1 month ago' ) );
				elseif ( 'trash' === $submit_type ) :
					global $wpdb;
					$_post_type = 'gdpr_analytics';
					if ( post_type_exists( $_post_type ) ) :
						$result = $wpdb->query(
							$wpdb->prepare(
								' DELETE posts,pt,pm
			            FROM wp_posts posts
			            LEFT JOIN wp_term_relationships pt ON pt.object_id = posts.ID
			            LEFT JOIN wp_postmeta pm ON pm.post_id = posts.ID
			            WHERE posts.post_type = %s
			            ',
								$_post_type
							)
						);
					endif;
				endif;
				do_action( 'gdpr_cookie_filter_settings' );
			endif;
			
			if ( $updated ) : 
				?>
					<script>
						jQuery('#moove-gdpr-setting-error-settings_scripts_empty').hide();
						jQuery('#moove-gdpr-setting-error-settings_updated').show();
					</script>
				<?php
			endif;
		endif;
	endif;
	$gdpr_options = get_option( $option_name );

	$enabled_analytics = isset( $gdpr_options['moove_gdpr_premium_analytics_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_premium_analytics_enable'] ) === 1 ? true : false ) : false;
	if ( $enabled_analytics && $is_detailed_view ) :
		$is_enabled_advanced 			= false;
		$is_enabled_third_party 	= false;

		if ( isset( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) :
			$is_enabled_third_party = true;
		endif;

		if ( isset( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) :
			$is_enabled_advanced 		= true;
		endif;

		$args = array(
			'post_type'      => 'gdpr_analytics',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		$args['date_query'] = array(
			array(
				'after'     => array(
					'year'  => date( 'Y', strtotime( $filter_start_date ) ),
					'month' => date( 'n', strtotime( $filter_start_date ) ),
					'day'   => date( 'j', strtotime( $filter_start_date ) ),
				),
				'before'    => array(
					'year'  => date( 'Y', strtotime( $filter_end_date ) ),
					'month' => date( 'n', strtotime( $filter_end_date ) ),
					'day'   => date( 'j', strtotime( $filter_end_date ) ),
				),
				'inclusive' => true,
			),
		);

		$args           = apply_filters( 'gdpr_cc_addon_analytics_table_args', $args );
		$gdpr_analytics = new WP_Query( $args );

		$infobar_showed    = 0;
		$modal_opened      = 0;
		$third_party_c     = 0;
		$advanced_c        = 0;
		$strict_c          = 0;
		$tabs_inside_modal = array(
			'privacy_overview'         => 0,
			'strict-necessary-cookies' => 0,
			'third_party_cookies'      => 0,
			'advanced-cookies'         => 0,
			'cookie_policy_modal'      => 0,
		);
		$clicked_to_accept = 0;
	endif;


	/**
	 * Analytics Fix
	 */

	$accurate_analytics = array();

	$total_cookies_accepted = 0;

	if ( $enabled_analytics && $is_detailed_view ) :
		if ( $gdpr_analytics->have_posts() ) :
			while ( $gdpr_analytics->have_posts() ) :
				$gdpr_analytics->the_post();
				$analytics_id = get_the_ID();

				// Infobar Showed.
				$value          = $addon_controller->gdpr_get_post_meta( $analytics_id, 'gdpr_show_infobar' );
				$infobar_showed = $value ? $infobar_showed + 1 : $infobar_showed;

				// Modal Opened.
				$value        = $addon_controller->gdpr_get_post_meta( $analytics_id, 'gdpr_opened_modal_from_link' );
				$modal_opened = $value ? $modal_opened + 1 : $modal_opened;

				$scripts_injected = get_post_meta( $analytics_id, 'script_injected', true );
				$encoded_injected = json_decode( $scripts_injected, true );


				if ( $encoded_injected && is_array( $encoded_injected ) ) :

					$is_accepted_strict 	= isset( $encoded_injected['strict'] ) && intval( $encoded_injected['strict'] ) === 1;
					$is_accepted_3rdparty = $is_enabled_third_party ? isset( $encoded_injected['thirdparty'] ) && intval( $encoded_injected['thirdparty'] ) === 1 : false;
					$is_accepted_advanced = $is_enabled_advanced ? isset( $encoded_injected['advanced'] ) && intval( $encoded_injected['advanced'] ) === 1 : false;

					$analytics_key = '';
					$analytics_key .= $is_accepted_strict 	? '1' : '0';
					$analytics_key .= $is_accepted_3rdparty ? '1' : '0';
					$analytics_key .= $is_accepted_advanced ? '1' : '0';

					if ( isset( $accurate_analytics[ $analytics_key ] ) ) :
						$accurate_analytics[ $analytics_key ]++;
					else :
						$accurate_analytics[ $analytics_key ] = 1;
					endif;
					$total_cookies_accepted++;

					// Tabs Opened inside Modal.
					$value = $addon_controller->gdpr_get_post_meta( $analytics_id, 'gdpr_clicked_to_tab' );
					$value = json_decode( $value, true );
					if ( is_array( $value ) && ! empty( $value ) ) :
						foreach ( $value as $key => $tab_name ) :
							if ( isset( $tabs_inside_modal[ $key ] ) ) :
								$tabs_inside_modal[ $key ]++;
							endif;
						endforeach;
					endif;


					$value             = $addon_controller->gdpr_get_post_meta( $analytics_id, 'gdpr_accept_all' );
					$clicked_to_accept = $value ? $clicked_to_accept + 1 : $clicked_to_accept;

				else :

					// Strictly Necessary Cookies.
					$value      = $addon_controller->gdpr_get_post_meta( $analytics_id, 'cookies_strict' );
					$value      = $value && intval( $value ) === 1;
					$is_accepted_strict 	= $value;

					// Third Party Cookies.
					$value         = $addon_controller->gdpr_get_post_meta( $analytics_id, 'cookies_thirdparty' );
					$value         = $value && intval( $value ) === 1;
					$is_accepted_3rdparty = $is_enabled_third_party && $value;

					// Advanced Cookies.
					$value        = $addon_controller->gdpr_get_post_meta( $analytics_id, 'cookies_advanced' );
					$value        = $value && intval( $value ) === 1;
					$is_accepted_advanced = $is_enabled_advanced && $value;

					if ( $is_accepted_strict ) :
						$analytics_key = '';
						$analytics_key .= $is_accepted_strict 	? '1' : '0';
						$analytics_key .= $is_accepted_3rdparty ? '1' : '0';
						$analytics_key .= $is_accepted_advanced ? '1' : '0';
						if ( isset( $accurate_analytics[ $analytics_key ] ) ) :
							$accurate_analytics[ $analytics_key ]++;
						else :
							$accurate_analytics[ $analytics_key ] = 1;
						endif;
						$total_cookies_accepted++;
					endif;

					// Tabs Opened inside Modal.
					$value = $addon_controller->gdpr_get_post_meta( $analytics_id, 'gdpr_clicked_to_tab' );
					$value = json_decode( $value, true );
					if ( is_array( $value ) && ! empty( $value ) ) :
						foreach ( $value as $key => $tab_name ) :
							if ( isset( $tabs_inside_modal[ $key ] ) ) :
								$tabs_inside_modal[ $key ]++;
							endif;
						endforeach;
					endif;

					// Clicked to "Accept All" or "Enable All Button".
					$value             = $addon_controller->gdpr_get_post_meta( $analytics_id, 'gdpr_accept_all' );
					$clicked_to_accept = $value ? $clicked_to_accept + 1 : $clicked_to_accept;

				endif;

			endwhile;
			wp_reset_postdata();
		endif;
	endif;
	?>
	<div class="gdpr-analytics-filters">
		<div class="gdpr-toggle-panel">
			<div class="gdpr-toggle-panel-heading">
				<h4><span class="dashicons dashicons-admin-tools"></span> <?php esc_html_e( 'Setup & Filters', 'gdpr-cookie-compliance-addon' ); ?></h4>
				<div class="buttons-container">
					<?php if ( $enabled_analytics ) : ?>
						<a href="#gdpr-analytics-settings" title="<?php esc_html_e( 'Analytics Settings', 'gdpr-cookie-compliance-addon' ); ?>"><span class="dashicons dashicons-admin-settings"></span> <span><?php esc_html_e( 'Settings', 'gdpr-cookie-compliance-addon' ); ?></span></a>
						<?php if ( $is_detailed_view ) : ?>
							<a href="#gdpr-analytics-filters" title="<?php esc_html_e( 'Filter results', 'gdpr-cookie-compliance-addon' ); ?>"><span class="dashicons dashicons-filter"></span> <span><?php esc_html_e( 'Filters', 'gdpr-cookie-compliance-addon' ); ?></span></a>
						<?php endif; ?>
						<?php if ( $published_posts || ! $is_detailed_view ) : ?>
							<a href="#gdpr-analytics-trash" title="<?php esc_html_e( 'Delete All Analytics', 'gdpr-cookie-compliance-addon' ); ?>"><span class="dashicons dashicons-trash"></span> <span><?php esc_html_e( 'Delete All Analytics', 'gdpr-cookie-compliance-addon' ); ?></span></a>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<!--  .buttons-container -->			
			</div>
			<!--  .gdpr-toggle-panel-heading -->
			<div class="gdpr-toggle-content" id="gdpr-analytics-filters" style="display: none;">
				<form action="#" method="post" autocomplete="off">
					<?php wp_nonce_field( 'moove_gdpr_stats_filters', 'moove_gdpr_stats_nonce_filters' ); ?>
					<input type="hidden" autocomplete="off" name="gdpr_submit_type" value="filters" />		
					<div class="filter-action-box">
						<h3><strong><?php esc_html_e( 'Filter results by date', 'gdpr-cookie-compliance-addon' ); ?></strong></h3>
						<label for="gdpr_analytics_datepicker_start" class="date-input">
							<span class="label-text"><?php esc_html_e( 'Start Date', 'gdpr-cookie-compliance-addon' ); ?>:</span>			
							<span class="dashicons dashicons-calendar-alt"></span>
							<input type="text" id="gdpr_analytics_datepicker_start" autocomplete="off" name="gdpr_analytics_datepicker_start" required value="<?php echo esc_attr( date( 'Y-m-d', strtotime( $filter_start_date ) ) ); ?>">
						</label>
						<label for="gdpr_analytics_datepicker_end" class="date-input">
							<span class="label-text"><?php esc_html_e( 'End Date', 'gdpr-cookie-compliance-addon' ); ?>:</span>
							<span class="dashicons dashicons-calendar-alt"></span>
							<input type="text" id="gdpr_analytics_datepicker_end" autocomplete="off" name="gdpr_analytics_datepicker_end" required value="<?php echo esc_attr( date( 'Y-m-d', strtotime( $filter_end_date ) ) ); ?>">
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
			<div class="gdpr-toggle-content" id="gdpr-analytics-trash" style="display: none;">
				<form action="#" method="post">		
					<?php wp_nonce_field( 'moove_gdpr_stats_trash', 'moove_gdpr_stats_nonce_trash' ); ?>
					<input type="hidden" name="gdpr_submit_type" value="trash" />			
					<div class="filter-action-box">
						<h3><strong><?php esc_html_e( 'Delete All Analytics', 'gdpr-cookie-compliance-addon' ); ?></strong></h3>
						<p class="description desc-error"><strong><?php esc_html_e( 'Warning', 'gdpr-cookie-compliance-addon' ); ?>:</strong> <?php esc_html_e( 'This will remove all existing GDPR analytics that are stored and reset the GDPR analytics tracking to the default settings.', 'gdpr-cookie-compliance-addon' ); ?></p>
						<!--  .description -->
						<div class="submit-gdpr-button-cnt">
							<button type="submit" id="gdpr_remove_all_statistics" class="button button-error"><?php esc_html_e( 'Delete All Analytics', 'gdpr-cookie-compliance-addon' ); ?></button>
						</div>
						<!--  .submit-gdpr-button-cnt -->
					</div>
					<!--  .filter-action-box -->			
				</form>
			</div>
			<!--  .gdpr-toggle-content -->
			<div class="gdpr-toggle-content" id="gdpr-analytics-settings" <?php echo $enabled_analytics && $is_detailed_view ? 'style="display: none;"' : ''; ?>>
				<form action="#" method="post">	
					<?php wp_nonce_field( 'moove_gdpr_stats_settings', 'moove_gdpr_stats_nonce_settings' ); ?>
					<input type="hidden" name="gdpr_submit_type" value="settings" />
					<div class="filter-action-box">
						<h3><strong><?php esc_html_e( 'Analytics Settings', 'gdpr-cookie-compliance-addon' ); ?></strong></h3>
						<p class="description"><?php esc_html_e( 'Please note that this feature may slow down your site as every interaction with the Cookie Banner is recorded.', 'gdpr-cookie-compliance-addon' ); ?></p>
						<!--  .description -->
						<div class="enable-gdpr-analytics">						
							<table class="form-table">
								<tbody>
									<tr>										
										<td style="padding-left: 0;">
											<!-- GDPR Rounded switch -->
											<label class="gdpr-checkbox-toggle">
												<input type="checkbox" name="moove_gdpr_premium_analytics_enable" id="moove_gdpr_premium_analytics_enable" <?php echo isset( $gdpr_options['moove_gdpr_premium_analytics_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_premium_analytics_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_premium_analytics_enable'] ) ? 'checked' : '' ) ) : ''; ?> >
												<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
											</label>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!--  .enable-gdpr-analytics -->
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
		<?php if ( $enabled_analytics && $is_detailed_view ) : ?>
			<div class="gdpr-current-filter">
				<p><strong><?php esc_html_e( 'Active filters', 'gdpr-cookie-compliance-addon' ); ?>: </strong><?php echo esc_attr( date_i18n( get_option( 'date_format' ), strtotime( $filter_start_date ) ) ); ?> - <?php echo esc_attr( date_i18n( get_option( 'date_format' ), strtotime( $filter_end_date ) ) ); ?></p>
			</div>
			<!--  .gdpr-current-filter -->
		<?php endif; ?>
	</div>
	<!--  .gdpr-analytics-filters -->
	<?php if ( $enabled_analytics && $is_detailed_view ) : ?>
		<div class="gdpr-analytics-data">
		<div class="moove-gdpr-stat-table">
				<div class="gdpr-stat-flex-inner">
					<?php
						do_action(
							'gdpr_cookie_analytics_section',
							'total-sessions',
							array(
								'total_sessions' => $gdpr_analytics->found_posts,
							)
						);
						do_action(
							'gdpr_cookie_analytics_section',
							'total-sessions-cookies',
							array(
								'total_sessions_cookies' => $gdpr_analytics->found_posts,
								'infobar_showed'         => $infobar_showed,
								'total_accepted'         => $total_cookies_accepted,
							)
						);
						do_action(
							'gdpr_cookie_analytics_section',
							'total-sessions-percentage',
							array(
								'total_sessions_cookies' => $gdpr_analytics->found_posts,
								'infobar_showed'         => $infobar_showed,
								'total_accepted'         => $total_cookies_accepted,
							)
						);
					?>
					 
				</div>
				<!-- .gdpr-stat-flex-inner -->
			</div>	
			<div class="moove-gdpr-stat-table">
				<div class="gdpr-stat-flex-inner">
					<?php
						do_action(
							'gdpr_cookie_analytics_section',
							'modal-opened',
							array(
								'modal_opened' => $modal_opened,
							)
						);
					?>

					<?php
						do_action(
							'gdpr_cookie_analytics_section',
							'clicked-accept-all',
							array(
								'accept_all' => $clicked_to_accept,
							)
						);
					?>
				</div>
				<!-- .gdpr-stat-flex-inner -->
			</div>

			<div class="moove-gdpr-stat-table full-width-section cookies-accepted-section">
				<div class="gdpr-stat-flex-inner">
					<?php

						do_action(
							'gdpr_cookie_analytics_section',
							'cookies-accepted',
							array(
								'gdpr_options'   	=> $gdpr_options,
								'wpml_lang'      	=> $wpml_lang,
								'analytics'			 	=> $accurate_analytics,
								'total_sessions'	=> $total_cookies_accepted,
							)
						);
					?>
				</div>
				<!-- .gdpr-stat-flex-inner -->

			</div>

			<?php do_action( 'gdpr_cc_addon_analytics_stats_table', $args ); ?>
		</div>
		<!--  .gdpr-analytics-data -->
	<?php elseif ( $enabled_analytics && ! $is_detailed_view ) : ?>

		<div class="gdpr-analytics-show-more-wrap">
			<img src="<?php echo moove_gdpr_addon_get_plugin_dir(); ?>/assets/images/analytics-placeholder.jpg">
			<div class="load-more-btn-wrap">
				<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=stats&amp;detailed_view" class="button button-primary"><?php _e('Load Analytics', 'gdpr-cookie-compliance-addon'); ?></a>
			</div>
			<!-- .load-more-btn-wrap -->
		</div>
		<!-- .gdpr-analytics-show-more-wrap -->
	<?php endif; ?>
<script type="text/javascript" src="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/js/gdpr_cc_datepicker.js"></script>
