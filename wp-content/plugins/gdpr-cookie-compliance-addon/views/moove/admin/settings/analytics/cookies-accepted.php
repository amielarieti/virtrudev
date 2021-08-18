<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php
	$gdpr_options = isset( $data['gdpr_options'] ) ? $data['gdpr_options'] : false;
	$wpml_lang    = isset( $data['wpml_lang'] ) ? $data['wpml_lang'] : '';
	$strict       = array(
		'show'  => true,
		'label' => '',
		'value' => isset( $data['analytics'] ) && isset( $data['analytics']['100'] ) ? intval( $data['analytics']['100'] ) : 0,
	);

	$advanced = array(
		'show'  => false,
		'label' => '',
		'value' => isset( $data['analytics'] ) && isset( $data['analytics']['101'] ) ? intval( $data['analytics']['101'] ) : 0,
	);

	$third_party = array(
		'show'  => false,
		'label' => '',
		'value' => isset( $data['analytics'] ) && isset( $data['analytics']['110'] ) ? intval( $data['analytics']['110'] ) : 0,
	);

	$all_accepted_key = '1';

	$strict['show']  = true;
	$strict['label'] = isset( $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] : __( 'Strictly Necessary Cookies', 'gdpr-cookie-compliance' );

	$total = $data['total_sessions'];

	if ( isset( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) :
		$third_party['show']  = true;
		$third_party['label'] = isset( $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] : __( '3rd Party Cookies', 'gdpr-cookie-compliance' );
		$all_accepted_key 		= $all_accepted_key.'1';
	else :
		$all_accepted_key 		= $all_accepted_key.'0';
	endif;


	if ( isset( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) :
		$advanced['show']  = true;
		$advanced['label'] = isset( $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] : __( 'Additional Cookies', 'gdpr-cookie-compliance' );
		$all_accepted_key 		= $all_accepted_key.'1';
	else :
		$all_accepted_key 		= $all_accepted_key.'0';
	endif;

	$pie_chart = array(
		'labels'       => array(
			'cookies_all'     => __( 'Percentage of users who accepted all cookies', 'gdpr-cookie-compliance' ),
			'strict_only'     => __( 'Percentage of users who accepted Strictly Necessary Cookies only', 'gdpr-cookie-compliance' ),
			'strict_3rdparty' => __( 'Percentage of users who accepted Strictly Necessary Cookies and 3rd Party Cookies only', 'gdpr-cookie-compliance' ),
			'strict_advanced' => __( 'Percentage of users who accepted Strictly Necessary Cookies and Additional Cookies only', 'gdpr-cookie-compliance' ),
		),
		'labels_short' => array(
			'cookies_all'     => __( 'Users who accepted all cookies', 'gdpr-cookie-compliance' ),
			'strict_only'     => __( 'Strictly Necessary Cookies only', 'gdpr-cookie-compliance' ),
			'strict_3rdparty' => __( 'Strictly Necessary Cookies and 3rd Party Cookies only', 'gdpr-cookie-compliance' ),
			'strict_advanced' => __( 'Strictly Necessary Cookies and Additional Cookies only', 'gdpr-cookie-compliance' ),
		),
		'values'       => array(
			'cookies_all'     => isset( $data['analytics'][$all_accepted_key] ) ? $data['analytics'][$all_accepted_key] : 0,
			'strict_only'     => isset( $data['analytics'] ) && isset( $data['analytics']['100'] ) ? $data['analytics']['100'] : 0,
			'strict_3rdparty' => isset( $data['analytics'] ) && isset( $data['analytics']['110'] ) ? $data['analytics']['110'] : 0,
			'strict_advanced' => isset( $data['analytics'] ) && isset( $data['analytics']['101'] ) ? $data['analytics']['101'] : 0,
		),
	);

	?>
<?php if ( $gdpr_options ) : ?>
	<div class="stat-cnt">
		<h4><?php esc_html_e( 'Cookies Accepted', 'gdpr-cookie-compliance-addon' ); ?></h4>

		<table class="table-section">
			<tr  style="color: rgba(247, 147, 34, 1);">
				<td class="gdpr_cookie_type">
					<div class="table_name">		
						<p><?php echo esc_attr( $pie_chart['labels']['cookies_all'] ); ?>:</p>
					</div>  
				</td>	        	
				<td class="gdpr_cookie_count">	    
					<div class="table_content">	    		
						<p><?php echo esc_attr( $pie_chart['values']['cookies_all'] ); ?></p>
						<p>
							<?php if ( $strict['show'] ) : 

								$subtotal 	= intval( $data['analytics'][$all_accepted_key] );
								$percentage = $total > 0 ? ( ( $subtotal * 100 ) / $total ) : 0; 
								?>
								  <span class="text"> / <?php echo $percentage === 0 ? esc_attr( $percentage ) : sprintf( '%0.2f', esc_attr( $percentage ) ); ?>%</span>
							<?php endif; ?>
						</p>
					</div>       	
				</td>
			   </tr>

			<?php if ( $all_accepted_key !== '100' ) : ?>
				<tr  style="color: rgba(114, 104, 95, 1);">
					<td class="gdpr_cookie_type">
						<div class="table_name">		
							<p><?php echo esc_attr( $pie_chart['labels']['strict_only'] ); ?>:</p>
						</div>  
					</td>	        	
					<td class="gdpr_cookie_count">	    
						<div class="table_content">	    		
							<p><?php echo esc_attr( $pie_chart['values']['strict_only'] ); ?></p>
							<p>
								<?php $percentage = $total > 0 && isset( $data['analytics'] ) && isset( $data['analytics']['100'] ) ? intval( $data['analytics']['100'] ) * 100 / $total : 0; ?>
								  <span class="text"> / <?php echo $percentage === 0 ? esc_attr( $percentage ) : sprintf( '%0.2f', esc_attr( $percentage ) ); ?>%</span>
							</p>
						</div>       	
					</td>
				</tr>
			<?php endif; ?>
			
			<?php if ( isset( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) === 1 && $all_accepted_key === '111' ) : ?>
				   <tr  style="color: rgba(255, 206, 86, 1);">
					<td class="gdpr_cookie_type">
						<div class="table_name">		
							<p><?php echo esc_attr( $pie_chart['labels']['strict_3rdparty'] ); ?>:</p>
						</div>  
					</td>	        	
					<td class="gdpr_cookie_count">	    
						<div class="table_content">
							<p><?php echo esc_attr( $pie_chart['values']['strict_3rdparty'] ); ?></p>
							<p>
								<?php if ( isset( $third_party['show'] ) && $third_party['show'] ) : ?>
									<?php $percentage = $total > 0 && isset( $data['analytics']['110'] ) ? intval( $data['analytics']['110'] ) * 100 / $total : 0; ?>
								  <span class="text"> / <?php echo $percentage === 0 ? esc_attr( $percentage ) : sprintf( '%0.2f', esc_attr( $percentage ) ); ?>%</span>
									<?php endif; ?>
							</p>
						</div>       	
					</td>
				   </tr>
			<?php endif; ?>

			<?php if ( isset( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) === 1 && $all_accepted_key === '111' )  : ?>
				   <tr  style="color: rgba(34, 34, 34, 1);">
					<td class="gdpr_cookie_type">
						<div class="table_name">		
							<p><?php echo esc_attr( $pie_chart['labels']['strict_advanced'] ); ?>:</p>
						</div>  
					</td>	        	
					<td class="gdpr_cookie_count">	    
						<div class="table_content">
							<p><?php echo esc_attr( $pie_chart['values']['strict_advanced'] ); ?></p>
							<p>
								<?php if ( isset( $advanced['show'] ) && $advanced['show'] ) : ?>
									<?php $percentage = $total > 0 && isset( $data['analytics']['101'] ) ? intval( $data['analytics']['101'] ) * 100 / $total : 0; ?>
								  <span class="text"> / <?php echo $percentage === 0 ? esc_attr( $percentage ) : sprintf( '%0.2f', esc_attr( $percentage ) ); ?>%</span>
								<?php endif; ?>
							</p>
						</div>       	
					</td>

				   </tr>
			<?php endif; ?>
		</table>
		<p class="description"><?php esc_html_e( 'This is the breakdown of cookies that were accepted (based on users who accepted at least the Strictly Necessary cookies). This number is the absolute number (it’s not based on sessions but on the actual number of users who accepted at least the Strictly necessary cookies). Repeated visits from the same users will not increase these numbers.', 'gdpr-cookie-compliance-addon' ); ?></p>
		<!--  .description -->

		<div class="chart-cnt">
			<br />
			<hr />
			<br />
			<script type="text/javascript" src="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/js/gdpr_cc_chart.js"></script>
			<canvas id="myChart" width="400" height="400"></canvas>
			<?php
			if ( $all_accepted_key !== '100' ) :
				$values = '[' . '"' . $pie_chart['values']['cookies_all'] . '",' . '"' . $pie_chart['values']['strict_only'] . '",';
				$labels = '[' . '"' . $pie_chart['labels_short']['cookies_all'] . '",' . '"' . $pie_chart['labels_short']['strict_only'] . '",';
			else :
				$values = '[' . '"' . $pie_chart['values']['cookies_all'] . '",';
				$labels = '[' . '"' . $pie_chart['labels_short']['cookies_all'] . '",';
			endif;

			if ( isset( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) === 1 && $all_accepted_key === '111' ) :
				$labels .= '"' . $pie_chart['labels_short']['strict_3rdparty'] . '",';
				$values .= '"' . $pie_chart['values']['strict_3rdparty'] . '",';
				endif;

			if ( isset( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) === 1 && $all_accepted_key === '111' ) :
				$labels .= '"' . $pie_chart['labels_short']['strict_advanced'] . '",';
				$values .= '"' . $pie_chart['values']['strict_advanced'] . '",';
				endif;
				$labels .= ']';
				$values .= ']';

			?>

			<script>
				var ctx = document.getElementById("myChart");
				var myChart = new Chart(ctx, {
					type: 'doughnut',
					data: {
						labels: <?php apply_filters( 'gdpr_addon_keephtml', $labels, true ); ?>,
						datasets: [{
							data: <?php apply_filters( 'gdpr_addon_keephtml', $values, true ); ?>,
							backgroundColor: [
								'rgba(247, 147, 34, 1)',
								'rgba(114, 104, 95, 1)',
								'rgba(255, 206, 86, 1)',
								'rgba(34, 34, 34, 1)',
							],
							borderColor: [
								'rgba(247, 147, 34, 1)',
								'rgba(114, 104, 95, 1)',
								'rgba(255, 206, 86, 1)',
								'rgba(34, 34, 34, 1)',
							],
							borderWidth: 1
						}]
					},
					options: {
						legend: {
							labels : {
								useLineStyle: true
							}
						}
					}
					
				});
			</script>
		</div>
		<!--  .chart-cnt -->
	</div>
	<!--  .stat-cnt -->
<?php endif; ?>
