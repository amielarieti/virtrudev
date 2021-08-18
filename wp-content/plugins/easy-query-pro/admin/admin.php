<?php

/* Admin actions */
add_action( 'admin_head', 'ewpq_admin_vars' ); // Set admin JS variables
add_action( 'admin_menu', 'eq_admin_menu' ); // Create admin menu
add_action( 'eq_display_templates', 'eq_display_templates' ); // Display of templates
add_action( 'ewpq_get_template_list', 'ewpq_get_template_list' ); // Get list of templates
add_action( 'wp_ajax_ewpq_save_repeater', 'ewpq_save_repeater' ); // Ajax Save template
add_action( 'wp_ajax_ewpq_license_activation', 'ewpq_license_activation' ); // Ajax Update License
add_action( 'wp_ajax_eq_get_layout', 'eq_get_layout' ); // Get Layout
add_action( 'wp_ajax_ewpq_update_repeater', 'ewpq_update_repeater' ); // Ajax Update template
add_action( 'wp_ajax_ewpq_create_template', 'ewpq_create_template' ); // Ajax create template
add_action( 'wp_ajax_ewpq_delete_template', 'ewpq_delete_template' ); // Ajax delete template
add_action( 'wp_ajax_ewpq_get_tax_terms', 'ewpq_get_tax_terms' ); // Ajax Get Taxonomy Terms
add_action( 'wp_ajax_ewpq_query_generator', 'ewpq_query_generator' ); // Ajax Generate Query
add_action( 'wp_ajax_ewpq_save_query', 'ewpq_save_query' ); // Save Query
add_action( 'wp_ajax_ewpq_create_query', 'ewpq_create_query' ); // Create Query
add_action( 'wp_ajax_ewpq_view_saved_query', 'ewpq_view_saved_query' ); // View Saved Query
add_action( 'wp_ajax_ewpq_delete_saved_query', 'ewpq_delete_saved_query' ); // Delete Saved Query
add_action( 'wp_ajax_ewpq_update_saved_query', 'ewpq_update_saved_query' ); // Update Saved Query
add_filter( 'admin_footer_text', 'eq_filter_admin_footer_text'); // Admin menu text
add_action( 'admin_notices', 'eq_admin_notice' ); // Admin notice



/*
*  eq_get_layout
*  Get layout and return value to repeater template
*
*  @since 2.1
*/

function eq_get_layout(){	
   if (current_user_can( 'edit_theme_options' )){         
   
      $nonce = sanitize_text_field($_GET["nonce"]);
      $type = sanitize_text_field($_GET["type"]);           
      $custom = sanitize_text_field($_GET["custom"]);
      
      if(empty($type) OR !isset($type)){
	      $type = 'default';
      } 
      
      // Check nonce, if they don't match then bounce!
      if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
         die('Error - unable to verify nonce, please try again.');    
      
      if($custom !== 'true'){	      
	      
         $content =  file_get_contents(EQ_LAYOUT_PATH.''.$type.'.php'); // Default Layout
         
      }else{
	      
	      $dir = 'eq_layouts';		      
			if(is_child_theme()){
				$path = get_stylesheet_directory().'/'. $dir .'/' .$type;
				// if child theme does not have the layout, check the parent theme
				if(!file_exists($path)){
					$path = get_template_directory().'/'. $dir .'/' .$type;
				}
			}
			else{
				$path = get_template_directory().'/'. $dir .'/' .$type;
			}
      	$content =  file_get_contents($path); 	  
      }     
      
      $return["value"] = $content;
      wp_send_json($return); 
             
   }else {
	   
      echo __('You don\'t belong here.', 'easy-query');
      
   }   
   
   wp_die();      
}




/*
*  ewpq_license_activation
*  Activate license
*
*  @since 1.1
*/

function ewpq_license_activation(){
	
	if (current_user_can( 'edit_theme_options' )){
		
		$nonce = $_GET["nonce"];
	   $type = $_GET["type"]; // activate / deactivate
	   $item = $_GET["item"];    
	   $license = $_GET["license"];     
	   $url = $_GET["url"];      
	   $upgrade = $_GET["upgrade"];     
	   $option_status = $_GET["status"];   
	   $option_key = $_GET["key"];   
	      
	   // Check our nonce, if they don't match then bounce!
	   if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
	      die('Error - unable to verify nonce, please try again.');          
	
		// data to send in our API request
		if($type === 'activate'){
			$action = 'activate_license';
		}else{
			$action = 'deactivate_license';
			delete_transient( 'easy_query_expiry');
		}
		
		$api_params = array( 
			'edd_action'=> $action, 
			'license' 	=> $license, 
			'item_id'   => $item, // the ID of our product in EDD
			'url'       => home_url()
		);
		
		$response = wp_remote_post(
		   EASY_QUERY_STORE_URL, 
		   array(
		      'timeout' => 15, 
		      'sslverify' => false, 
		      'body' => $api_params
         )
      );
	
		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;		
		
		$license_data = $response['body'];
		$license_data = json_decode($license_data); // decode the license data		
	
		$return["success"] = $license_data->success;			
		$msg = '';
		
		if($type === 'activate'){		
			$return["license_limit"] = $license_data->license_limit;
			$return["expires"] = $license_data->expires;
			$return["site_count"] = $license_data->site_count;
			$return["activations_left"] = $license_data->activations_left;
			$return["license"] = $license_data->license;
			$return["item_name"] = $license_data->item_name;
			
			$expires = strtotime( $license_data->expires );
			$today = strtotime( date("Y-m-d H:i:s",time()) );			
			
			// Out of activations
			if($license_data->activations_left === 0 && $license_data->success === false){
				$msg = '<strong>Sorry, but you are out of available licenses <em>['. $license_data->license_limit .'/'. $license_data->site_count .']</em>.</strong> Please visit the <a href="'.$upgrade.'" target="_blank">'.$license_data->item_name.'</a> page to add additional licenses.';
			}
			
			// Expired
			if($expires !== false){
   			// false would mean no expiry
   			if($today > $expires && $license_data->success === false){
      			$msg = 'Sorry, but your license has expired. Please visit the <a href="'.$upgrade.'" target="_blank">'.$license_data->item_name.'</a> page to extend your license.';
   			}
			}	
		}
		$return["msg"] = $msg;
		
		update_option( $option_status, $license_data->license);
		update_option( $option_key, $license );	
		
	   echo json_encode($return);
		
		die();
	
	} else {
   	
      echo __('You don\'t belong here.', 'easy-query');
   
   } 
}




/*
*  ewpq_admin_vars
*  Create admin variables and ajax nonce
*
*  @since 1.0.0
*/
function ewpq_admin_vars() { ?>
    <script type='text/javascript'>
	 /* <![CDATA[ */
    var ewpq_admin_localize = <?php echo json_encode( array( 
        'ajax_admin_url' => admin_url( 'admin-ajax.php' ),
        'ewpq_admin_nonce' => wp_create_nonce( 'ewpq_repeater_nonce' ),
        'active' => __('Active', 'easy-query'),
        'inactive' => __('Inactive', 'easy-query'),
    )); ?>
    /* ]]> */
    </script>
<?php }



/**
* ewpq_core_update
* If WP option plugin version do not match or the plugin has been updated and we need to update our templates.
*
* @since 1.0.0
*/

function ewpq_core_update() {  
	global $wpdb;
	$installed_ver = get_option( "easy_query_version" ); // Get value from WP Option tbl
	if ( $installed_ver != EQ_VERSION ) {
      ewpq_run_update();	
   }
}
add_action('plugins_loaded', 'ewpq_core_update');


/**
* ewpq_run_update
* Run the update on our blogs
*
* @since 1.0.0
*/

function ewpq_run_update(){
   global $wpdb;	
   
   if ( is_multisite()) {           
   	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );   	
      
   	// Loop all blogs and run update routine   	
      foreach ( $blog_ids as $blog_id ) {
         switch_to_blog( $blog_id );
         ewpq_update_template_files();
         restore_current_blog();
      }
      
   } else {
      ewpq_update_template_files();
   }
      
   update_option( "easy_query_version", EQ_VERSION ); // Update the WP Option tbl
}


/**
* ewpq_update_template_files
* Update routine for template files
*
* @since 1.0.0
*/

function ewpq_update_template_files(){
   global $wpdb;	
	$table_name = $wpdb->prefix . "easy_query";
	$blog_id = $wpdb->blogid;	
	 
	if($blog_id > 1){	// Create template_ directories if they don't exist 
	   $dir = EQ_PATH. 'core/templates_'. $blog_id;
   	if( !is_dir($dir) ){
         mkdir($dir);
      }
   }
   
	// Get all templates ($rows)
   $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE type = 'default' OR type = 'unlimited'"); 
      
   if($rows){
      foreach( $rows as $row ) { // Loop $rows
         
         $template = $row->name; // Current template
         
         $data = $wpdb->get_var("SELECT template FROM $table_name WHERE name = '$template'");
         
         if($blog_id > 1)
            $f = EQ_PATH. 'core/templates_'. $blog_id.'/'.$template.'.php';
         else
            $f = EQ_TEMPLATE_PATH. ''.$template.'.php';
         
         $o = fopen($f, 'w+'); // Open file or create it
         $w = fwrite($o, $data);
         fclose($o);
      }
   }
}


/*
 * eq_admin_menu
 * Create Admin Menu
 *
 * @since 2.0.0
 */

function eq_admin_menu() {  
   
   $eq_settings_page = add_submenu_page(
      'options-general.php', 
      'Easy Query Pro', 
      'Easy Query Pro', 
      'edit_theme_options', 
      'easy-query', 
      'eq_settings_page'
   ); 	
   
   //Add admin scripts
   add_action( 'load-' . $eq_settings_page, 'ewpq_load_admin_js' );
      
}



/*
*  eq_settings_page
*  Settings page
*
*  @since 2.0.0
*/

function eq_settings_page(){ 
   if( isset($_GET['tab']) && $_GET['tab'] == 'settings'){
      
      $name = __('Settings', 'easy-query' );
      $tab = 'settings';  
   
   }else if( isset($_GET['tab']) && $_GET['tab'] == 'templates'){
   
      $name = __('Templates', 'easy-query' );
      $tab = 'templates';  
   
   }else if( isset($_GET['tab']) && $_GET['tab'] == 'query-builder'){
   
      $name = __('Query Builder', 'easy-query' );
      $tab = 'query-builder';  
   
   }else if( isset($_GET['tab']) && $_GET['tab'] == 'saved-queries'){
   
      $name = __('Saved Queries', 'easy-query' );
      $tab = 'saved-queries';  
   
   }elseif( isset($_GET['tab']) && $_GET['tab'] == 'examples'){
   
      $name = __('Examples', 'easy-query' );
      $tab = 'examples';  
   
   }elseif( isset($_GET['tab']) && $_GET['tab'] == 'license'){
   
      $name = __('License', 'easy-query' );
      $tab = 'license';  
   
   }else{
   
      $name = __('Settings', 'easy-query' );
      $tab = 'settings';  
   
   }
?>
   <ul class="eq-nav">
      <li class="eq-dashboard">
         <a class="tab<?php if( !isset($_GET['tab'])) echo ' nav-tab-active'; ?>" href="options-general.php?page=<?php echo EQ_SLUG; ?>">
            <span><?php _e('Dashboard', 'post-explorer' ); ?></span>
   		</a>
      </li>
      <li>
		   <a class="tab<?php if( isset($_GET['tab']) && $tab == 'templates') echo ' nav-tab-active'; ?>" href="options-general.php?page=<?php echo EQ_SLUG; ?>&tab=templates">
   		   <?php _e('Templates', 'post-explorer' ); ?>
   		</a>
      </li>
      <li>
		   <a class="tab<?php if( isset($_GET['tab']) && $tab == 'query-builder') echo ' nav-tab-active'; ?>" href="options-general.php?page=<?php echo EQ_SLUG; ?>&tab=query-builder">
   		   <?php _e('Query Builder', 'post-explorer' ); ?>
   		</a>
      </li>
      <li>
		   <a class="tab<?php if( isset($_GET['tab']) && $tab == 'saved-queries') echo ' nav-tab-active'; ?>" href="options-general.php?page=<?php echo EQ_SLUG; ?>&tab=saved-queries">
   		   <?php _e('Saved Queries', 'post-explorer' ); ?>
   		</a>
      </li>
      <li>
		   <a class="tab<?php if( isset($_GET['tab']) && $tab == 'examples') echo ' nav-tab-active'; ?>" href="options-general.php?page=<?php echo EQ_SLUG; ?>&tab=examples">
   		   <?php _e('Examples', 'post-explorer' ); ?>
   		</a>
      </li>
		<li>
         <a class="tab<?php if( isset($_GET['tab']) && $tab == 'license') echo ' nav-tab-active'; ?>" id="nav-license" href="options-general.php?page=<?php echo EQ_SLUG; ?>&tab=license">
            <?php _e('License', 'post-explorer' ); ?>
         </a>
		</li>
   </ul>
	<div class="content" id="poststuff">
	<?php 
		if( !isset($_GET['tab'])){
		   include_once( EQ_PATH . 'admin/views/settings.php');
      }
      if( isset($_GET['tab']) && $tab == 'shortcode'){
         include_once( EQ_PATH . 'admin/views/shortcode.php');
      }
      if( isset($_GET['tab']) && $tab == 'templates'){
         include_once( EQ_PATH . 'admin/views/templates.php');
      }
      if( isset($_GET['tab']) && $tab == 'query-builder'){
         include_once( EQ_PATH . 'admin/views/query-builder.php');
      } 
      if( isset($_GET['tab']) && $tab == 'saved-queries'){
         include_once( EQ_PATH . 'admin/views/saved-queries.php');
      } 
      if( isset($_GET['tab']) && $tab == 'examples'){
         include_once( EQ_PATH . 'admin/views/examples.php');
      } 
      if( isset($_GET['tab']) && $tab == 'license'){
         include_once( EQ_PATH . 'admin/views/license.php');
      } 
   ?>
   <div class="clear"></div>
<?php }



/**
* ewpq_load_admin_js
* Load Admin JS
*
* @since 1.0.0
*/

function ewpq_load_admin_js(){
	add_action( 'admin_enqueue_scripts', 'ewpq_enqueue_admin_scripts' );
}



/**
* ewpq_enqueue_admin_scripts
* Enqueue Admin JS
*
* @since 1.0.0
*/

function ewpq_enqueue_admin_scripts(){

   //Load Admin CSS
   wp_enqueue_style( 'ewpq-admin', EQ_ADMIN_URL. 'css/admin.css');
   wp_enqueue_style( 'ewpq-license', EQ_ADMIN_URL. 'css/license.css');
  
   //CodeMirror
   
      // CSS
      wp_enqueue_style( 'ewpq-codemirror-css', EQ_ADMIN_URL. 'codemirror/lib/codemirror.css' );
            
      // JS
      wp_enqueue_script( 'ewpq-codemirror', EQ_ADMIN_URL. 'codemirror/lib/codemirror.js' );    
      wp_enqueue_script( 'ewpq-codemirror-matchbrackets', EQ_ADMIN_URL. 'codemirror/addon/edit/matchbrackets.js' );
      wp_enqueue_script( 'ewpq-codemirror-htmlmixed', EQ_ADMIN_URL. 'codemirror/mode/htmlmixed/htmlmixed.js' );
      wp_enqueue_script( 'ewpq-codemirror-xml', EQ_ADMIN_URL. 'codemirror/mode/xml/xml.js' );
      wp_enqueue_script( 'ewpq-codemirror-javascript', EQ_ADMIN_URL. 'codemirror/mode/javascript/javascript.js' );
      wp_enqueue_script( 'ewpq-codemirror-mode-css', EQ_ADMIN_URL. 'codemirror/mode/css/css.js' );
      wp_enqueue_script( 'ewpq-codemirror-clike', EQ_ADMIN_URL. 'codemirror/mode/clike/clike.js' );
      wp_enqueue_script( 'ewpq-codemirror-php', EQ_ADMIN_URL. 'codemirror/mode/php/php.js' );        
   
   //Load JS   
   wp_enqueue_script( 'jquery-form' );
   wp_enqueue_script( 'ewpq-select2', EQ_ADMIN_URL. 'js/libs/select2.min.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-drops', EQ_ADMIN_URL. 'js/libs/jquery.drops.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-admin', EQ_ADMIN_URL. 'js/admin.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-license', EQ_ADMIN_URL. 'js/license.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-shortcode-builder', EQ_ADMIN_URL. 'shortcode-builder/js/shortcode-builder.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-query-genertor', EQ_ADMIN_URL. 'query-generator/js/query-generator.js', array( 'jquery' ), false);
}



/*
*  eq_filter_admin_footer_text
*  Filter the WP Admin footer text only on Easy Query pages
*
*  @since 2.0
*/

function eq_filter_admin_footer_text( $text ) {	
	$screen = eq_is_admin_screen();	
	if(!$screen){
		return;
	}
	
	echo '<strong>Easy Query Pro</strong> is made with <span style="color: #e25555;">♥</span> by <a href="https://connekthq.com" target="_blank" style="font-weight: 500;">Connekt</a>';
}



/*
*  eq_admin_notice
*  Display warning if plugin is not activated
*
*  @since 2.1
*/

function eq_admin_notice() {	
   $status = get_option( 'easy_query_license_status' );
   if( $status !== false && $status == 'valid' ) {
   } else {  
   ?>
   <div class="notice notice-error"> 
   	<p><?php _e('<strong>Easy Query</strong>: Your license has expired or is inactive - visit the <a href="options-general.php?page=easy-query&tab=license">license</a> page to correct the issue.', 'easy-query'); ?></p>
   </div>
   <?php
   }
}



/*
*  ewpq_license_page
*  Easy Query License
*
*  @since 1.0.0
*/

function ewpq_license_page(){ 
   include_once( EQ_PATH . 'admin/views/license.php');
}



/*
*  ewpq_get_template_list
*  List our repeaters for selection on query builder page
*
*  @since 1.0
*/

function ewpq_get_template_list(){	
   global $wpdb;
	$table_name = $wpdb->prefix . "easy_query";
	$rows = $wpdb->get_results("SELECT * FROM $table_name where type != 'default' AND type != 'saved'"); // Get all data
   $i = 0;
	foreach( $rows as $template )  {  
	   // Get repeater alias, if avaialble	
	   $i++;
	   $name = $template->name;
   	$template_alias = $template->alias;
   	if(empty($template_alias)){
   	   echo '<option name="'.$name.'" id="chk-'.$name.'" value="'.$name.'">Template #'. $i .'</option>';
   	}else{				
   	   echo '<option name="'.$name.'" id="chk-'.$name.'" value="'.$name.'">'.$template_alias.'</option>';    	
   	}
	}
}



/*
*  eq_display_templates
*  Front end listing/display of templates
*
*  @since 1.0
*/

function eq_display_templates(){	
	//Repeater loop
	global $wpdb;
	$blog_id = $wpdb->blogid;
   $table_name = $wpdb->prefix . "easy_query";
   
   $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name where type != 'default' AND type != 'saved'"); // Count rows
   $rows = $wpdb->get_results("SELECT * FROM $table_name where type != 'default' AND type != 'saved'"); // Get all data
   ?>
   <div id="unlmited-container">
   <?php 
   if($rowcount > 0)
   { 
      $i = 0;
		foreach( $rows as $repeater ) 
		{    		
		   $i++;   
   		$repeater_file = $repeater->name;
   	   $repeater_name = 'Template #'.$i;
      	$repeater_alias = $repeater->alias;
      	         	
      	if(!empty($repeater_alias)){ // Set alias
         	$heading = $repeater_alias;
      	}else{
         	$heading = $repeater_name;
      	}	      		
		?>
		
		<div class="row template unlimited">   
			<h3 class="heading" data-default="<?php echo $repeater_name; ?>"><?php echo $heading; ?></h3>
			<div class="expand-wrap">
				<div class="wrap repeater-wrap" data-name="<?php echo $repeater_file; ?>" data-type="unlimited">
				   <div class="one_half">
   			      <label class="template-title" for="alias-<?php echo $repeater_file; ?>">
   			         <?php _e('Template Alias', 'easy-query'); ?>:
   			      </label>
   			      <?php   			         		         
      			      if(empty($repeater_alias)){
               	      echo '<input type="text" id="alias-'.$repeater_file.'" class="ewpq_repeater_alias" value="'.$repeater_name.'" maxlength="55">';
                     }else{				
               	      echo '<input type="text" id="alias-'.$repeater_file.'" class="ewpq_repeater_alias" value="'.$repeater_alias.'" maxlength="55">';            	
                     }
   			      ?>
				   </div>
				   <div class="one_half">
			         <label class="template-title" for="id-<?php echo $repeater_file; ?>">
			            <?php _e('Template ID', 'easy-query'); ?>:
                  </label>
                  <input type="text" class="disabled-input" id="id-<?php echo $repeater_file; ?>" value="<?php echo $repeater_file; ?>" readonly="readonly">
				   </div>
               				
               <label class="template-title extra-padding" for="template-<?php echo $repeater_file; ?>">
                  <?php _e('Enter the HTML and PHP code for this template', 'easy-query'); ?>:
               </label>
               
               <?php include( EQ_PATH . 'admin/includes/components/layouts.php'); ?>
               
   				<?php         
      				
      				if($blog_id > 1) // multisite
                     $filename = EQ_PATH. 'core/templates_'. $blog_id.'/'.$repeater_file.'.php';
               	else
               	   $filename = EQ_TEMPLATE_PATH.''.$repeater_file.'.php'; // File
            	   
      				$handle = fopen ($filename, "r");
      				$content = '';
      				if(filesize ($filename) != 0){
      				   $content = fread ($handle, filesize ($filename));		               
      				}
      				fclose ($handle);
   				?> 
   				<div class="textarea-wrap">
   					<textarea rows="10" id="<?php echo $repeater_file; ?>" class="_alm_repeater"><?php if($content) echo $content; ?></textarea>
   					<script>
                     var editor_<?php echo $repeater_file; ?> = CodeMirror.fromTextArea(document.getElementById("<?php echo $repeater_file; ?>"), {
                       mode:  "application/x-httpd-php",
                       lineNumbers: true,
                       lineWrapping: true,
                       indentUnit: 0,
                       matchBrackets: true,
                       viewportMargin: Infinity,
                       extraKeys: {"Ctrl-Space": "autocomplete"},
                     });
                   </script>
   				</div>
   				<button type="submit" class="button button-primary save-repeater" data-editor-id="<?php echo $repeater_file; ?>"><?php _e('Save Template', 'easy-query'); ?></button>
            	<div class="saved-response">&nbsp;</div>  	            	
   				
            	<?php include( EQ_PATH . 'admin/includes/components/repeater-options.php'); ?>
            	
				</div> 					           
			</div>
			<div class="clear"></div>
		</div>	
		<?php } 	
		//End Repeater foreach Loop 
		}
	//End If num_rows 	
	?>
	
   </div>
	
	<script>	
   jQuery(document).ready(function($) {	
      
      // Check alias'
	   $(document).on('keyup', '.ewpq_repeater_alias', function(){
	      var el = $(this),
	          heading = el.parent().parent().parent().parent().find('h3.heading');		      
	      var val = el.val(),
	          defaultVal = heading.data('default');
	      if(val === ''){
		      heading.text(defaultVal);
	      }else{
		      heading.text(val);
	      }
	   });
	   
	   // ADD template
	   $('#ewpq-add-template').on('click', function(){
	      var el = $(this);
	      if(!el.hasClass('active')){
	         el.addClass('active');
	         
	         // Create div
	         var container = $('#unlmited-container'),
				    div = $('<div class="row unlimited new" />');
            div.appendTo(container);
            div.fadeIn(250);
            
            // Run ajax
   		   $.ajax({
      			type: 'POST',
      			url: ewpq_admin_localize.ajax_admin_url,
      			data: {
      				action: 'ewpq_create_template',
      				nonce: ewpq_admin_localize.ewpq_admin_nonce,
      			},
      			dataType: "JSON",
      			success: function(data) {                     
                  div.load("<?php echo EQ_URL; ?>/admin/includes/components/template.php", {
                     id: data.id, 
                     alias: data.alias, 
                     defaultVal: data.defaultVal
                  }, function(){ // .load() complete 
                     div.addClass('done');
                     $('.unlimited-wrap', div).slideDown(350, 'ewpq_unlimited_ease', function(){                           
                        div.removeClass('new');
                        div.removeClass('done');                                            
                        el.removeClass('active');
                        $('.CodeMirror').each(function(i, el){
                            el.CodeMirror.refresh();
                        });
                     });
                  }); 
                  
      			},
      			error: function(xhr, status, error) {
      				responseText.html('<?php _e('<p>Error - Something went wrong and the template could not be created.</p>', 'easy-query'); ?>');
                  div.remove();
      				el.removeClass('active');
      			}
      		});
   		}
	   });
	   
	 
      // Undo Template Creation
      $(document).on('click', '.eq-undo', function(){
          var r = confirm("<?php _e('Are you sure you want to undo this template?', 'easy-query'); ?>");
	      
         if (r == true && !$(this).hasClass('deleting')) {
		      var el = $(this),
		          item = el.closest('.row.unlimited'),
					 id = el.data('id');
                 
            eq_delete_template(el, item, id);  
         } 
      });
      
      // Delete Template Button
	   $('.eq-delete').on('click', function(){   	   
	      var r = confirm("<?php _e('Are you sure you want to delete this template?', 'easy-query'); ?>");
	      
         if (r == true && !$(this).hasClass('deleting')) {
		      var el = $(this),
		          item = el.closest('.row.unlimited'),
					 id = el.data('id');
                
            eq_delete_template(el, item, id);  
         }           		   		   
	   });
	   
	   
	   
	   /*  eq_delete_template()
	    *  Delete template
	    */
	   function eq_delete_template(el, item, id){
   	   
   	   el.addClass('deleting');
         item.addClass('deleting');
         
   	   $.ajax({
   			type: 'POST',
   			url: ewpq_admin_localize.ajax_admin_url,
   			data: {
   				action: 'ewpq_delete_template',
   				repeater: id,
   				nonce: ewpq_admin_localize.ewpq_admin_nonce
   			},
   			dataType: "html",
   			success: function(data) {	
   				setTimeout(function() {
   				   item.addClass('deleted');
                  item.slideUp(350, 'ewpq_unlimited_ease', function(){
                     item.remove();
                  })
               }, 250);
   				console.log('Template Deleted');
   			},
   			error: function(xhr, status, error) {
   			   item.removeClass('deleting');
   			   el.removeClass('deleting');
   				responseText.html('<?php _e('<p>Error - Something went wrong and the template could not be deleted.</p>', 'easy-query'); ?>');
   			}
   		});
   		
	   }
	   
	   $.easing.ewpq_unlimited_ease = function (x, t, b, c, d) {
         if ((t /= d / 2) < 1) return c / 2 * t * t + b;
         return -c / 2 * ((--t) * (t - 2) - 1) + b;
      };
	   
	 });
		
	</script>
	<?php
 }	
 
 
 
/*
 *  ewpq_create_template
 *  Create new repeater template
 *
 *  @since 1.0
 */

function ewpq_create_template(){	   
   
   if (current_user_can( 'edit_theme_options' )){
      
      $nonce = $_POST["nonce"];
      // Check our nonce, if they don't match then bounce!
      if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
         die('Error - unable to verify nonce, please try again!');       
      
      global $wpdb;
   	$blog_id = $wpdb->blogid;
      $table_name = $wpdb->prefix . "easy_query";    
        
      $count = floatval($wpdb->get_var( "SELECT COUNT(*) FROM $table_name where type != 'default' AND type != 'saved'" ));
      $count = $count+2;
      
      $defaultVal = '<?php // '.__('Enter your template code here', 'easy-query').'.  ?>';   
      
      // Insert into DB
      $wpdb->insert($table_name , array(
         'name' => 'temp', 
         'template' => $defaultVal, 
         'alias' => '', 
         'type' => 'unlimited', 
         'pluginVersion' => EQ_VERSION
      ));  
           
      //$id = $wpdb->insert_id; // Get new primary key value (id)
      $id = mt_rand(100000, 999999); // generate unique ID
      $data_new = array('name' => 'template_'.$id);
      $data_previous = array('name' => 'temp');
      $wpdb->update($table_name , $data_new, $data_previous);
          
         
      // Set new template name      
      $template = 'template_'.$id;     
         
      // Create file on server         
      if($blog_id > 1)
         $f = EQ_PATH. 'core/templates_'. $blog_id.'/'.$template .'.php'; // File
   	else
   	   $f = EQ_TEMPLATE_PATH. ''.$template .'.php';
   	   
      $file = fopen( $f, "w" ) or die ("Error opening file"); // It doesn't exist, so create it.
      $w = fwrite($file, $defaultVal) or die("Error writing file");
      
      $return = '';
      $return["id"] = $template;
      $return["alias"] = __('Template #', 'easy-query') . '' .$count;
      $return["defaultVal"] = $defaultVal;
      
      wp_send_json($return);      
      
      die();
	   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
	
}	 
 
 
 
/*
 *  ewpq_delete_template
 *  Delete templates function
 *
 *  @since 1.0
 */

function ewpq_delete_template(){	
   
   if (current_user_can( 'edit_theme_options' )){
   
      $nonce = $_POST["nonce"];
      $template = Trim(stripslashes($_POST["repeater"])); // Repeater name for deletion
      
      // Check our nonce, if they don't match then bounce!
      if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
         die('Error - unable to verify nonce, please try again!');         
      
      global $wpdb;
      $blog_id = $wpdb->blogid;
      $table_name = $wpdb->prefix . "easy_query";      
      
      $wpdb->delete($table_name, array( 'name' => $template )); // delete from db
      
      
      // Delete file from server
      if($blog_id > 1)
         $file_to_delete = EQ_PATH. 'core/templates_'. $blog_id.'/'.$template .'.php'; // File
   	else
   	   $file_to_delete = EQ_TEMPLATE_PATH. ''.$template .'.php';
      
      if (file_exists($file_to_delete)) {
          unlink($file_to_delete); // Delete now
      } 
      // See if it exists again to be sure it was removed
      if (file_exists($file_to_delete)) {
          echo __('The template could not be deleted.', 'easy-query');
      } else {
          echo __('Template deleted successfully.', 'easy-query');
      }
      
      die();
	   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
	
}



/*
*  ewpq_query_generator
*  Get template data from database
*
*  @return   DB value
*  @since 1.0.0
*/

function ewpq_query_generator(){ 
   
   if (current_user_can( 'edit_theme_options' )){
      
      error_reporting(E_ALL|E_STRICT);   
   	$nonce = $_POST["nonce"];
      
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Error - unable to verify nonce, please try again.');
   		
      $template = Trim(stripslashes($_POST["template"])); // template   
      $f = EQ_TEMPLATE_PATH. ''. $template .'.php'; // file()   
      
   	$open_error = '<span class="saved-error"><b>'. __('Error Opening Template', 'easy-query') .'</b></span>';
      $open_error .= '<em>'. $f .'</em>';
      $open_error .=  __('Please check your file path and ensure your server is configured to allow Easy Query to read and write files within the plugin directory', 'easy-query');
       
   	$data = file_get_contents($f) or die($open_error); // Open file
   	
   	echo $data;   
   	
   	die();
	   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
	
}



/*
*  ewpq_save_repeater
*  Template Save function
*
*  @return   response
*  @since 1.0.0
*/

function ewpq_save_repeater(){
   
   if (current_user_can( 'edit_theme_options' )){
   
      global $wpdb;
   	$blog_id = $wpdb->blogid;
      
   	$nonce = $_POST["nonce"];
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Error - unable to verify nonce, please try again.');
   		
      // Get _POST Vars 
   	$c = Trim(stripslashes($_POST["value"])); // Template Value
   	$n = Trim(stripslashes($_POST["template"])); // Template name
   	$t = Trim(stripslashes($_POST["type"])); // Template type
   	$a = Trim(stripslashes($_POST["alias"])); // Template alias
      
      if($blog_id > 1) // multisite
         $f = EQ_PATH. 'core/templates_'. $blog_id.'/'.$n .'.php'; // File
   	else
   	   $f = EQ_TEMPLATE_PATH. ''.$n .'.php'; // File	   
   		
      $o_error = '<span class="saved-error"><b>'. __('Error Opening File', 'easy-query') .'</b></span>';
      $o_error .= '<em>'. $f .'</em>';
      $o_error .=  __('Please check your file path and ensure your server is configured to allow Easy Query to read and write files within the /easy-query/ plugin directory', 'easy-query');
      
      $w_error = '<span class="saved-error"><b>'. __('Error Saving File', 'easy-query') .'</b></span>';
      $w_error .= '<em>'. $f .'</em>';
      $w_error .=  __('Please check your file path and ensure your server is configured to allow Easy Query to read and write files within the /easy-query/ plugin directory', 'easy-query');
      
      $o = fopen($f, 'w+') or die($o_error); // Open file
   	
   	$w = fwrite($o, $c) or die($w_error); // Save/Write the file
   	
   	fclose($o); //now close it
   	
   	$table_name = $wpdb->prefix . "easy_query";	
   	
      if($t === 'unlimited'){ // Unlimited Templates	  
   	   $data_update = array('template' => "$c", 'alias' => "$a", 'pluginVersion' => EQ_VERSION);
   	   $data_where = array('name' => $n);
      }
      else{ // Custom Repeaters
   	   $data_update = array('template' => "$c", 'pluginVersion' => EQ_VERSION);
   	   $data_where = array('name' => "default");
      }
      
   	$wpdb->update($table_name , $data_update, $data_where);
   	
   	//Our results
   	if($w){
   	    echo '<span class="saved">Template Saved Successfully</span>';
   	} else {
   	    echo '<span class="saved-error"><b>'. __('Error Writing File', 'easy-query') .'</b></span><br/>Something went wrong and the data could not be saved.';
   	}
   	die();
		   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
	
}



/*
*  ewpq_update_repeater
*  Update repeater template from database
*
*  @return   DB value
*  @since 1.0.0
*/

function ewpq_update_repeater(){
   
   if (current_user_can( 'edit_theme_options' )){

   	$nonce = $_POST["nonce"];
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Error - unable to verify nonce, please try again.');
   		
      // Get _POST Vars  	
   	$n = Trim(stripslashes($_POST["template"])); // Repeater name
   	$t = Trim(stripslashes($_POST["type"])); // Repeater type (default | unlimited)
   	
   	// Get value from database
   	global $wpdb;
   	$table_name = $wpdb->prefix . "easy_query";	
   		
   	if($t === 'default')	$n = 'default';      
      
      $the_template = $wpdb->get_var("SELECT template FROM " . $table_name . " WHERE name = '$n'");
      
      echo $the_template; // Return repeater value
      
   	die();
		   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
	
}



/**
 * eq_create_file
 * Create/Update a query file
 *
 * @since 2.3
 */
function eq_create_file($type, $name, $template){
	
	if (current_user_can( 'edit_theme_options' )){
		$base_dir = EasyQuery::eq_get_template_path();
	   EasyQuery::eq_mkdir($base_dir);
	   $f = $base_dir .'/'. $type .'-'. $name .'.php';
	   
	   // Write Template
	   try {
	      $o = fopen($f, 'w+'); //Open file
	      if ( !$o ) {
	        throw new Exception(__('[Easy Query Pro] Unable to open template - '.$f.' - Please check your file path and ensure your server is configured to allow for read and write files.', 'easy-query'));
	      }
	      $w = fwrite($o, $template); //Save the file
	      if ( !$w ) {
	        throw new Exception(__('[Easy Query Pro] Error saving template - '.$f.' - Please check your file path and ensure your server is configured to allow for read and write files.', 'easy-query'));
	      }
	      fclose($o); //now close it
	   } catch ( Exception $e ) {
	      // Display error message in console.
	      echo '<script>console.log("' .$e->getMessage(). '");</script>';
	   }
   }
   
}



/**
 * eq_delete_file
 * Delete a query file
 *
 * @since 2.3
 */
function eq_delete_file($type, $name){
	
	if (current_user_can( 'edit_theme_options' )){		
		$base_dir = EasyQuery::eq_get_template_path();
		if(!$base_dir){
			return false;
		}
		
		$file_to_delete = $base_dir .'/'. $type .'-'. $name .'.php';
	   if (file_exists($file_to_delete)) {
	      unlink($file_to_delete);
	   }  
   } 
}



/*
*  ewpq_save_query
*  Save query from Query Builder
*
*  @return   null
*  @since 1.0.0
*/

function ewpq_save_query(){ 
   
   if (current_user_can( 'edit_theme_options' )){

      error_reporting(E_ALL|E_STRICT);   
      
   	$nonce = $_POST["nonce"];
   	$value = Trim(stripslashes($_POST["value"]));
   	$alias = Trim(stripslashes($_POST["alias"]));
      
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Error - unable to verify nonce, please try again.');
   		
      global $wpdb;
      $table_name = $wpdb->prefix . "easy_query";    
      
      $name = 'save_'.mt_rand(100000, 999999); // generate unique ID
      // Insert into DB
      $wpdb->insert($table_name , array(
         'name' => $name, 
         'template' => $value, 
         'alias' => $alias, 
         'type' => 'saved', 
         'pluginVersion' => EQ_VERSION,
      ));  
      
      echo __('Query saved successfully', 'easy-query'); 
      
      die();
		   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
	
}



/*
*  ewpq_create_query
*  Create a query 
*
*  @return  null
*  @since 2.3
*/

function ewpq_create_query(){ 
   
   if (current_user_can( 'edit_theme_options' )){

      error_reporting(E_ALL|E_STRICT);   
      
   	$nonce = $_POST["nonce"];
   	$value = '<?php // Enter PHP and HTML code here ?>';
   	$alias = Trim(stripslashes($_POST["alias"]));
      
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Error - unable to verify nonce, please try again.');
   		
      global $wpdb;
      $table_name = $wpdb->prefix . "easy_query";       
      $name = 'save_'.mt_rand(100000, 999999); // generate unique ID
      
      // Insert into DB
      $wpdb->insert($table_name , array(
         'name' => $name, 
         'template' => $value, 
         'alias' => $alias, 
         'type' => 'saved', 
         'pluginVersion' => EQ_VERSION,
      ));  
      
      
      $lastid = $wpdb->insert_id;
      eq_create_file('query', $lastid, $value);
      
      echo __('Query saved successfully', 'easy-query'); 
      
      wp_die();
		   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
		wp_die();
	}
	
}



/*
*  ewpq_view_saved_query
*  Viewing saved query
*
*  @return   query value
*  @since 1.0.0
*/

function ewpq_view_saved_query(){    
   
   if (current_user_can( 'edit_theme_options' )){
   
      error_reporting(E_ALL|E_STRICT);   
      
   	$nonce = $_POST["nonce"];
   	$id = Trim(stripslashes($_POST["id"]));
      
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Error - unable to verify nonce, please try again.');
   		
      global $wpdb;
      $table_name = $wpdb->prefix . "easy_query";    
      
      $value = $wpdb->get_results( "SELECT template, alias FROM $table_name where id = $id" );
      
      $template = $value[0]->template;
      $alias = $value[0]->alias;
      
      $return = [];
      $return['template'] = $template;
      $return['alias'] = $alias;   
      
      eq_create_file('query', $id, $template);   
      
      wp_send_json($return);
		   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
}



/*
*  ewpq_delete_saved_query
*  Delete saved query
*
*  @return   removed query
*  @since 1.0.0
*/

function ewpq_delete_saved_query(){ 
   
   if (current_user_can( 'edit_theme_options' )){
      
      error_reporting(E_ALL|E_STRICT);   
      
   	$nonce = $_POST["nonce"];
   	$id = Trim(stripslashes($_POST["id"]));
      
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Error - unable to verify nonce, please try again.');
   		
      global $wpdb;
      $table_name = $wpdb->prefix . "easy_query";    
      $wpdb->delete($table_name, array( 'id' => $id )); // delete from db      
      
      eq_delete_file('query', $id);
      
      echo __('Query deleted successfully', 'easy-query');
      
      wp_die();
		   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
	
}




/*
*  ewpq_update_saved_query
*  Update saved query
*
*  @return   updated query
*  @since 1.0.0
*/

function ewpq_update_saved_query(){ 
   
   if (current_user_can( 'edit_theme_options' )){
      
      error_reporting(E_ALL|E_STRICT);   
      
   	$nonce = $_POST["nonce"];
   	$id = Trim(stripslashes($_POST["id"]));
   	$alias = Trim(stripslashes($_POST["alias"]));   	
   	$value = Trim(stripslashes($_POST["value"]));
      
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Error - unable to verify nonce, please try again.');
   		
      global $wpdb;
      $table_name = $wpdb->prefix . "easy_query";    
            
      $data_update = array(
         'template' => $value,
         'alias' => $alias
      );
   	$data_where = array('id' => $id);
      
   	$wpdb->update($table_name , $data_update, $data_where);
   	
   	eq_create_file('query', $id, $value);
      
      echo '<span class="saved">'.__('Query successfully updated', 'easy-query').'</span>';
      
      wp_die();
		   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
	
}



/*
 *  ewpq_get_tax_terms
 *  Get taxonomy terms for shortcode builder
 *
 *  @return   Taxonomy Terms
 *  @since 1.0.0
 */

function ewpq_get_tax_terms(){	
   
   if (current_user_can( 'edit_theme_options' )){
      
   	$nonce = $_GET["nonce"];
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Get Bounced!');
   		
   	$taxonomy = (isset($_GET['taxonomy'])) ? $_GET['taxonomy'] : '';	
   	$tax_args = array(
   		'orderby'       => 'name', 
   		'order'         => 'ASC',
   		'hide_empty'    => false
   	);	
   	$terms = get_terms($taxonomy, $tax_args);
   	$returnVal = '';
   	if ( !empty( $terms ) && !is_wp_error( $terms ) ){		
   		$returnVal .= '<ul>';
   		foreach ( $terms as $term ) {
   			//print_r($term);
   			$returnVal .='<li><input type="checkbox" class="alm_element" name="tax-term-'.$term->slug.'" id="tax-term-'.$term->slug.'" data-type="'.$term->slug.'"><label for="tax-term-'.$term->slug.'">'.$term->name.'</label></li>';		
   		}
   		$returnVal .= '</ul>';		
   		echo $returnVal;
   		wp_die();
   	}else{
   		echo "<p class='warning'>No terms exist within this taxonomy</p>";
   		wp_die();
   	}
		   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
	
}



/*
 *  ewpq_admin_init
 *  Initiate the plugin, create our setting variables.
 *
 *  @since 1.0.0
 */

add_action( 'admin_init', 'ewpq_admin_init');
function ewpq_admin_init(){

	register_setting( 
		'ewpq-setting-group', 
		'ewpq_settings', 
		'_ewpq_sanitize_settings' 
	);
	
	register_setting(
		'easy_query_license', 
		'easy_query_license_key', 
		'easy_query_sanitize_license'
	);
	
	add_settings_section( 
		'ewpq_general_settings',  
		'Global Settings', 
		'ewpq_general_settings_callback', 
		'easy-wp-query' 
	);
	
	add_settings_field(  // Disable CSS
		'_ewpq_disable_css', 
		__('Disable CSS', 'easy-query' ), 
		'_ewpq_disable_css_callback', 
		'easy-wp-query', 
		'ewpq_general_settings' 
	);
	
	add_settings_field(  // Hide btn
		'_ewpq_hide_btn', 
		__('Editor Button', 'easy-query' ), 
		'ewpq_hide_btn_callback', 
		'easy-wp-query', 
		'ewpq_general_settings' 
	);
	
	add_settings_field(  // Load dynamic queries
		'_ewpq_disable_dynamic', 
		__('Dynamic Content', 'easy-query' ), 
		'ewpq_disable_dynamic_callback', 
		'easy-wp-query', 
		'ewpq_general_settings' 
	);	
	
}


/*
*  ewpq_general_settings_callback
*  Some general settings text
*
*  @since 1.0.0
*/

function ewpq_general_settings_callback() {
    echo '<p>' . __('Customize your version of Easy Query by updating the various settings below.', 'easy-query') . '</p>';
}


/*
*  _ewpq_sanitize_settings
*  Sanitize our form fields
*
*  @since 1.0.0
*/

function _ewpq_sanitize_settings( $input ) {
    return $input;
}


/*
*  ewpq_hide_btn_callback
*  Disbale the Easy Query shortcode button in the WordPress content editor
*
*  @since 1.0.0
*/

function ewpq_hide_btn_callback(){
	$options = get_option( 'ewpq_settings' );
	if(!isset($options['_ewpq_hide_btn'])) 
	   $options['_ewpq_hide_btn'] = '0';
	
	$html = '<input type="hidden" name="ewpq_settings[_ewpq_hide_btn]" value="0" /><input type="checkbox" id="ewpq_hide_btn" name="ewpq_settings[_ewpq_hide_btn]" value="1"'. (($options['_ewpq_hide_btn']) ? ' checked="checked"' : '') .' />';
	$html .= '<label for="ewpq_hide_btn">'.__('Hide Query Builder button in WYSIWYG editor.', 'easy-query').'</label>';	
	
	echo $html;
}


/*
*  _ewpq_disable_css_callback
*  Diabale Easy Query CSS.
*
*  @since 1.0.0
*/

function _ewpq_disable_css_callback(){
	$options = get_option( 'ewpq_settings' );
	if(!isset($options['_ewpq_disable_css'])) 
	   $options['_ewpq_disable_css'] = '0';
	
	$html = '<input type="hidden" name="ewpq_settings[_ewpq_disable_css]" value="0" />';
	$html .= '<input type="checkbox" id="ewpq_disable_css_input" name="ewpq_settings[_ewpq_disable_css]" value="1"'. (($options['_ewpq_disable_css']) ? ' checked="checked"' : '') .' />';
	$html .= '<label for="ewpq_disable_css_input">'.__('I want to use my own CSS styles', 'easy-query').'<br/><span style="display:block;"><i class="fa fa-file-text-o"></i> &nbsp;<a href="'.EQ_URL.'/core/css/easy-query.css" target="blank">'.__('View Easy Query CSS', 'easy-query').'</a></span></label>';
	
	echo $html;
}


/*
*  ewpq_disable_dynamic_callback
*  Disable the dynamic population of categories, tags and authors
*
*  @since 1.0.0
*/

function ewpq_disable_dynamic_callback(){
	$options = get_option( 'ewpq_settings' );		
	if(!isset($options['_ewpq_disable_dynamic'])) 
	   $options['_ewpq_disable_dynamic'] = '0';
	
	$html =  '<input type="hidden" name="ewpq_settings[_ewpq_disable_dynamic]" value="0" />';
	$html .= '<input type="checkbox" name="ewpq_settings[_ewpq_disable_dynamic]" id="_ewpq_disable_dynamic" value="1"'. (($options['_ewpq_disable_dynamic']) ? ' checked="checked"' : '') .' />';
	$html .= '<label for="_ewpq_disable_dynamic">'.__('Disable dynamic population of categories, tags and authors in the Query Builder.<span style="display:block">Recommended only if you have an extraordinary number of categories, tags and/or authors.', 'easy-query').'</label>';	
	
	echo $html;
}


/*
*  easy_query_sanitize_license
*  Sanitize our license activation
*
*  @since 1.0.0
*/

function easy_query_sanitize_license( $new ) {
	$old = get_option( 'easy_query_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'easy_query_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}


