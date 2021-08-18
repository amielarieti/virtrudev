<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php
	$gdpr_options      = isset( $data['gdpr_options'] ) ? $data['gdpr_options'] : false;
	$wpml_lang         = isset( $data['wpml_lang'] ) ? $data['wpml_lang'] : '';
	$tabs_inside_modal = isset( $data['tabs_inside_modal'] ) && is_array( $data['tabs_inside_modal'] ) ? $data['tabs_inside_modal'] : false;
?>
<?php if ( $gdpr_options && $tabs_inside_modal ) : ?>
	<div class="stat-cnt">
		<h4><?php esc_html_e( 'Tabs Clicked inside Modal', 'gdpr-cookie-compliance-addon' ); ?></h4>
		<table class="table-section">
			<?php if ( isset( $tabs_inside_modal['privacy_overview'] ) ) : ?>
				<tr>
					<td>
						<div class="table_name">		
							<p><?php echo isset( $gdpr_options[ 'moove_gdpr_privacy_overview_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_privacy_overview_tab_title' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_privacy_overview_tab_title' . $wpml_lang ] ) : esc_html__( 'Privacy Overview', 'gdpr-cookie-compliance' ); ?>:</p>
						</div>  
					</td>	        	
					<td>	    
						<div class="table_content">	    		
							<p><?php echo esc_attr( $tabs_inside_modal['privacy_overview'] ); ?></p>	   
						</div>       	
					</td>
				   </tr>
			<?php endif; ?>
			<?php if ( isset( $tabs_inside_modal['strict-necessary-cookies'] ) ) : ?>
				   <tr>
					<td>
						<div class="table_name">		
							<p><?php echo isset( $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ) : esc_html__( 'Strictly Necessary Cookies', 'gdpr-cookie-compliance' ); ?>:</p>
						</div>  
					</td>	        	
					<td>	    
						<div class="table_content">	    		
							<p><?php echo esc_attr( $tabs_inside_modal['strict-necessary-cookies'] ); ?></p>  
						</div>       	
					</td>
				   </tr>
			<?php endif; ?>
			<?php if ( isset( $tabs_inside_modal['third_party_cookies'] ) && isset( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) : ?>
				   <tr>
					<td>
						<div class="table_name">
							<p><?php echo isset( $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ) : esc_html__( '3rd Party Cookies', 'gdpr-cookie-compliance' ); ?>:</p>
							
						   </div>
					</td>	        	
					<td>	    
						<div class="table_content">
							<p><?php echo esc_attr( $tabs_inside_modal['third_party_cookies'] ); ?></p>
						</div>    	
					</td>
				   </tr>
			<?php endif; ?>
			<?php if ( isset( $tabs_inside_modal['advanced-cookies'] ) && isset( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) : ?>
				<tr>
					<td>
						<div class="table_name">
							<p><?php echo isset( $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ) : esc_html__( 'Additional Cookies', 'gdpr-cookie-compliance' ); ?>:</p>
						   </div>
					</td>	        	
					<td>	    
						<div class="table_content">
							<p><?php echo esc_attr( $tabs_inside_modal['advanced-cookies'] ); ?></p>
						</div>    	
					</td>
				   </tr>
			<?php endif; ?>
			<?php if ( isset( $tabs_inside_modal['cookie_policy_modal'] ) && isset( $gdpr_options['moove_gdpr_cookie_policy_enable'] ) && intval( $gdpr_options['moove_gdpr_cookie_policy_enable'] ) === 1 ) : ?>
				<tr>
					<td>
						<div class="table_name">
							<p><?php echo isset( $gdpr_options[ 'moove_gdpr_cookie_policy_tab_nav_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cookie_policy_tab_nav_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cookie_policy_tab_nav_label' . $wpml_lang ] ) : esc_html__( 'Cookie Policy', 'gdpr-cookie-compliance' ); ?>:</p>
						   </div>
					</td>	        	
					<td>	    
						<div class="table_content">
							<p><?php echo esc_attr( $tabs_inside_modal['cookie_policy_modal'] ); ?></p>
						</div>    	
					</td>
				   </tr>
			<?php endif; ?>
		</table>
	</div>
	<!--  .stat-cnt -->
<?php endif; ?>
