<div class="admin cnkt" id="ewpq-add-ons">	
	<div class="wrap">
   	
		<div class="header-wrap">
         <h1>
            <?php echo EQ_TITLE; ?>: <strong><?php _e('License', 'easy-query'); ?></strong>
            <em><?php _e('Enter your license key to receive plugin update notifications.', 'easy-query'); ?></em>
         </h1>  
		</div>
		
		<div class="cnkt-main">
   		   		
   		<?php 
      		// start: License
			   $license = get_option( 'easy_query_license_key' );
				$status = get_option( 'easy_query_license_status' );
	      ?>
         <div class="cnkt-license postbox" id="license-easy-query">
	         <div class="cnkt-license-title"> 
		         <div class="status <?php if($status == 'valid'){echo 'valid';}else{echo 'invalid';} ?> "></div>        
      			<h2><?php _e('Easy Query Pro', 'easy-query'); ?></h2> 
	         </div>
	         	
            <div class="cnkt-license-wrap">
               <p><?php _e('Enter your Easy Query Pro license key below to receive plugin update notifications directly in your <a href="plugins.php">WP Plugins dashboard</a>.', 'easy-query'); ?></p>
               
               <?php                   
                  $eq_url = 'https://connekthq.com/plugins/easy-query/pricing/';
                  if( $status !== false && $status == 'valid' ) { ?>
   			   <!-- nothing -->
   			   <?php } else { ?>
   			   <div class="no-license">
      	         <h4><?php _e('Don\'t have a license?', 'ajax-load-more'); ?></h4>
      	         <p><?php _e('A valid license is required to activate and receive plugin updates directly in your WordPress dashboard', 'ajax-load-more'); ?> &rarr; <a href="<?php echo $eq_url; ?>?utm_source=WP%20Admin&utm_medium=License&utm_campaign=EasyQuery" target="blank"><strong><?php _e('Purchase Now', 'ajax-load-more'); ?>!</strong></a></p>
               </div> 
   			   <?php } ?>
               
			      <form method="post" action="options.php">     			                 			
         			<?php settings_fields('easy_query_license'); ?>   
         			<label class="description" for="easy_query_license_key"><?php _e('Enter License Key', 'easy-query'); ?></label>
         			<div class="cnkt-license-key-field">
         			   <input id="easy_query_license_key" name="easy_query_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" placeholder="<?php _e('Enter License Key', 'easy-query'); ?>" />
         			   <?php if( $status !== false && $status == 'valid' ) { ?>
            		   <span class="status active"><?php _e('Active', 'easy-query'); ?></span>
            		   <?php } else { ?>
            		   <span class="status inactive"><?php _e('Inactive', 'easy-query'); ?></span>
            		   <?php } ?>
         			</div>
         			
         			<?php 
            			//delete_transient( 'easy_query_expiry');
            			$eq_expiry = get_transient( 'easy_query_expiry' ); 
            			
            			if(!$eq_expiry){
               			// Get license expiry
                        $api_params = array( 
                        	'edd_action'=> 'check_license', 
                        	'license' 	=> esc_attr($license), 
                        	'item_id'   => EASY_QUERY_ITEM_NAME, // the ID of our product in EDD
                        	'url'       => EASY_QUERY_STORE_URL
                        );
                        
                        $license_data = wp_remote_post(
                           EASY_QUERY_STORE_URL, 
                           array( 
                              'timeout' => 15, 
                              'sslverify' => false, 
                              'body' => $api_params
                           )
                        );
                        $license_data = json_decode($license_data['body']);
                        $expires = $license_data->expires;
                        
                        // Store expiry date in transitent
                        set_transient( 'easy_query_expiry', $expires, 7 * DAY_IN_SECONDS );
                        
                     } else {
         			?>
         			<p style="margin: 0; font-size: 0.9em; opacity: 0.8;" class="expiry"><?php _e('Your license expires', 'easy-query'); ?>: <strong><?php echo $eq_expiry; ?></strong></p>
         			<?php } ?>     			      			
            	</form>
            	
		      </div> 
		      
   			<div id="major-publishing-actions">
      			<div class="cnkt-license-btn-wrap"	         			
				   		data-name="<?php echo EASY_QUERY_ITEM_NAME; ?>" 
		         		data-url="<?php echo EASY_QUERY_STORE_URL; ?>" 
			         	data-option-status="easy_query_license_status" 
				         data-option-key="easy_query_license_key"
				         data-upgrade-url="https://connekthq.com/plugins/easy-query/">
         			<button type="button" class="activate cnkt-license-btn <?php if($status === 'valid'){ echo 'hide'; } ?> button-primary" data-type="activate"><?php _e('Activate License', 'easy-query'); ?></button>
					   
					   <button type="button" class="deactivate cnkt-license-btn <?php if($status !== 'valid'){ echo 'hide'; } ?> button-secondary" data-type="deactivate"><?php _e('Deactivate License', 'easy-query'); ?></button>  
      			</div>
               <div class="clear"></div>
            </div>
               
		      <div class="loading"></div>
		      
         </div> 	
         <?php 
            // end: License          
         ?>
         		   
	   </div>
	   
	   <div class="cnkt-sidebar">
   	   <div class="cta postbox">
            <h3><?php _e('About Your License', 'easy-query'); ?></h3>
            <div class="cta-wrap">
               <p><?php _e('An Easy Query license will enable updates directly in your WP dashboard', 'easy-query'); ?>.</p>         
               <p><?php _e('License keys are found in the purchase receipt email that was sent immediately after your successful purchase and in the <a href="https://connekthq.com/account/" target="_blank">Account</a> section on our website', 'easy-query') ?>.</p>
               
               <hr/><p><?php _e('If you cannot locate your key please use the <a href="https://connekthq.com/contact/">contact form</a> on our website and reference the email used when you completed the purchase.', 'easy-query'); ?></p>
            </div>
         </div>   	   
	   </div>	      
	   	
	</div>
</div>