<?php
/*
Plugin Name: Ajax Load More: Users
Plugin URI: https://connekthq.com/plugins/ajax-load-more/user/
Description: Ajax Load More extension to infinite scroll WordPress users.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: https://connekthq.com
Version: 1.1.1
License: GPL
Copyright: Darren Cooney & Connekt Media
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


define('ALM_USERS_PATH', plugin_dir_path(__FILE__));
define('ALM_USERS_URL', plugins_url('', __FILE__));
define('ALM_USERS_VERSION', '1.1.1');
define('ALM_USERS_RELEASE', 'August 6, 2019');



/*
*  alm_users_install
*  Install the Users add-on
*
*  @since 1.0
*/

register_activation_hook( __FILE__, 'alm_users_install' );
function alm_users_install() {
   if(!is_plugin_active('ajax-load-more/ajax-load-more.php')){	//if Ajax Load More is activated
   	die('You must install and activate <a href="https://wordpress.org/plugins/ajax-load-more/">Ajax Load More</a> before installing the Ajax Load More Users add-on.');
	}
}



if( !class_exists('ALMUsers') ):

   class ALMUsers{

   	function __construct(){
   		add_action( 'alm_users_installed', array(&$this, 'alm_users_installed') );
   	   add_action( 'wp_ajax_alm_users', array(&$this, 'alm_users_query') );
   	   add_action( 'wp_ajax_nopriv_alm_users', array(&$this, 'alm_users_query') );
   		add_filter( 'alm_users_shortcode', array(&$this, 'alm_users_shortcode'), 10, 7 );
   		add_filter( 'alm_users_preloaded', array(&$this, 'alm_users_preloaded'), 10, 4 );
   		add_action( 'alm_users_settings', array(&$this, 'alm_users_settings' ));
   	}



   	/*
   	*  alm_users_preloaded
   	*  Preload users if preloaded is true in alm shortcode
   	*
   	*  @since 1.0
   	*/
   	public function alm_users_preloaded($args, $preloaded_amount, $repeater, $theme_repeater){      	

         $id = (isset($args['id'])) ? $args['id'] : '';
         $post_id = (isset($args['post_id'])) ? $args['post_id'] : '';
         
	   	$offset = (isset($args['offset'])) ? $args['offset'] : 0;
	   	$preloaded_amount = (isset($preloaded_amount)) ? $preloaded_amount : $args['users_per_page'];
	   	$role = (isset($args['users_role'])) ? $args['users_role'] : '';
         $order = (isset($args['users_order'])) ? $args['users_order'] : 5;
         $orderby = (isset($args['users_orderby'])) ? $args['users_orderby'] : 'user_login';
         $include = (isset($args['users_include'])) ? $args['users_include'] : false;
         $exclude = (isset($args['users_exclude'])) ? $args['users_exclude'] : false;
         
         // Custom Fields
   		$meta_key = (isset($args['meta_key'])) ? $args['meta_key'] : '';
   		$meta_value = (isset($args['meta_value'])) ? $args['meta_value'] : '';
   		$meta_compare = (isset($args['meta_compare'])) ? $args['meta_compare'] : '';
   		if(empty($meta_compare))
   			$meta_compare = 'IN'; 
   		if($meta_compare === 'lessthan') $meta_compare = '<'; // do_shortcode fix (shortcode was rendering as HTML)
   		if($meta_compare === 'lessthanequalto') $meta_compare = '<='; // do_shortcode fix (shortcode was rendering as HTML)
   		$meta_relation = (isset($args['meta_relation'])) ? $args['meta_relation'] : '';
   		if(empty($meta_relation))
   			$meta_relation = 'AND';
   		$meta_type = (isset($args['meta_type'])) ? $args['meta_type'] : '';
   		if(empty($meta_type))
   			$meta_type = 'CHAR';   			
         
   		$data = $alm_found_posts ='';

   		if(!empty($role)){
      		
	         // Get decrypted role
	         $role = alm_role_decrypt($role);
	         
	         // Get query type
	         $role_query = self::get_role_query_type($role);
	         
	         // Get user role array	         
	         $role = self::get_role_as_array($role, $role_query);
            
	         // User Query
	         $preloaded_args = array(
		      	$role_query => $role,
		      	'number'	 	=> $preloaded_amount,
		      	'order' 		=> $order,
		      	'orderby' 	=> $orderby,
		      	'offset'    => $offset
	         );
	         // Include
	         if($include){
		         $preloaded_args['include'] = array($include);
	         }
	         // Exclude
	         if($exclude){
		         $preloaded_args['exclude'] = array($exclude);
	         }
	         
	         // Meta Query
	   		if(!empty($meta_key) && !empty($meta_value) || !empty($meta_key) && $meta_compare !== "IN"){
	      		
	      		// Parse multiple meta query    
	            $meta_query_total = count(explode(":", $meta_key)); // Total meta_query objects
	            $meta_keys = explode(":", $meta_key); // convert to array
	            $meta_value = explode(":", $meta_value); // convert to array
	            $meta_compare = explode(":", $meta_compare); // convert to array
	            $meta_type = explode(":", $meta_type); // convert to array
	            
	   			// Loop Meta Query
	            $preloaded_args['meta_query'] = array(
					   'relation' => $meta_relation
	            );
					for($mq_i = 0; $mq_i < $meta_query_total; $mq_i++){
						$preloaded_args['meta_query'][] = alm_get_meta_query($meta_keys[$mq_i], $meta_value[$mq_i], $meta_compare[$mq_i], $meta_type[$mq_i]);
					}
				}
	   	   
	         // Meta_key, used for ordering by meta value
	         if(!empty($meta_key)){
		         if (strpos($orderby, 'meta_value') !== false) { // Only order by meta_key, if $orderby is set to meta_value{_num}
		            $meta_key_single = explode(":", $meta_key);
	               $preloaded_args['meta_key'] = $meta_key_single[0];
	            }
	         }
	         
	         
	         /*
   			 *	alm_users_query_args_{id}
   			 *
   			 * ALM Users Filter Hook
   			 *
   			 * @return $args;
   			 */
   		   $preloaded_args = apply_filters('alm_users_query_args_'.$id, $preloaded_args, $post_id);	         


            // WP_User_Query
	         $user_query = new WP_User_Query( $preloaded_args );

	         $alm_found_posts = $user_query->total_users;
	         $alm_page = 0;
	         $alm_item = 0;
	         $alm_current = 0;

				if ( ! empty( $user_query->results ) ) {
					ob_start();

					foreach ( $user_query->results as $user ) {

                  $alm_item++;
                  $alm_current++;

						// Repeater Template
						if($theme_repeater != 'null' && has_action('alm_get_theme_repeater')){  // Theme Repeater
							do_action('alm_get_users_theme_repeater', $theme_repeater, $alm_found_posts, $alm_page, $alm_item, $alm_current, $user);
						}else{
							$type = alm_get_repeater_type($repeater);
							include(alm_get_current_repeater( $repeater, $type )); // Repeater
						}
						// End Repeater Template

					}
					$data = ob_get_clean();

				} else {
					$data = null;
				}

   		}

         $results = array(
	         'data' => $data,
	         'total' => $alm_found_posts
         );

			return $results;

   	}
   	
   	
   	
   	/*
   	*  alm_users_query
   	*  Query users via wp_user_query, send results via ajax
   	*  https://codex.wordpress.org/Class_Reference/WP_User_Query
   	*
   	*. @return $return   JSON
   	*  @since 1.0
   	*/
   	public function alm_users_query(){
      	
      	if(!isset($_GET)){ 
         	return false; 
         }
         
         $id = (isset($_GET['id'])) ? $_GET['id'] : '';
         $post_id = (isset($_GET['post_id'])) ? $_GET['post_id'] : '';
   		$page = (isset($_GET['page'])) ? $_GET['page'] : 0;
      	$offset = (isset($_GET['offset'])) ? $_GET['offset'] : 0;
      	$repeater = (isset($_GET['repeater'])) ? $_GET['repeater'] : 'default';
      	$type = alm_get_repeater_type($repeater);
      	$theme_repeater = (isset($_GET['theme_repeater'])) ? $_GET['theme_repeater'] : 'null';
      	$canonical_url = (isset($_GET['canonical_url'])) ? $_GET['canonical_url'] : $_SERVER['HTTP_REFERER'];
      	$queryType = (isset($_GET['query_type'])) ? $_GET['query_type'] : 'standard'; // Ajax Query Type
      
         // Users data array - from ajax-load-more.js
         $data = (isset($_GET['users'])) ? $_GET['users'] : '';
         if($data){
            $role = (isset($data['role'])) ? $data['role'] : '';
            $users_per_page = (isset($data['per_page'])) ? $data['per_page'] : 5;
            $order = (isset($data['order'])) ? $data['order'] : 5;
            $orderby = (isset($data['orderby'])) ? $data['orderby'] : 'login';
            $include = (isset($data['include'])) ? $data['include'] : false;
            $exclude = (isset($data['exclude'])) ? $data['exclude'] : false;
         }
         
         // Custom Fields
      	$meta_key = (isset($_GET['meta_key'])) ? $_GET['meta_key'] : '';
      	$meta_value = (isset($_GET['meta_value'])) ? $_GET['meta_value'] : '';
      	$meta_compare = (isset($_GET['meta_compare'])) ? $_GET['meta_compare'] : '';
      	if(empty($meta_compare))
      		$meta_compare = 'IN'; 
      	if($meta_compare === 'lessthan') $meta_compare = '<'; // do_shortcode fix (shortcode was rendering as HTML)
      	if($meta_compare === 'lessthanequalto') $meta_compare = '<='; // do_shortcode fix (shortcode was rendering as HTML)
      	$meta_relation = (isset($_GET['meta_relation'])) ? $_GET['meta_relation'] : '';
      	if(empty($meta_relation))
      		$meta_relation = 'AND';
      	$meta_type = (isset($_GET['meta_type'])) ? $_GET['meta_type'] : '';
      	if(empty($meta_type))
      		$meta_type = 'CHAR';
      		
      
         // Cache Add-on
         $cache_id = (isset($_GET['cache_id'])) ? $_GET['cache_id'] : '';
      
         // Preload Add-on
      	$preloaded = (isset($_GET['preloaded'])) ? $_GET['preloaded'] : false;
      	$preloaded_amount = (isset($_GET['preloaded_amount'])) ? $_GET['preloaded_amount'] : '5';
      	if(has_action('alm_preload_installed') && $preloaded === 'true'){
      		$old_offset = $preloaded_amount;
      	   $offset = $offset + $preloaded_amount;
      	   $alm_loop_count = $old_offset;
      	}else{
         	$alm_loop_count = 0;
      	}
      
         // SEO Add-on
      	$seo_start_page = (isset($_GET['seo_start_page'])) ? $_GET['seo_start_page'] : 1;
      
      
         if(!empty($role)){ // Role Defined
      
            // Get decrypted role
            $role = alm_role_decrypt($role);	         
            
            // Get query type
            $role_query = ALMUsers::get_role_query_type($role);
            
            // Get user role array	         
            $role = ALMUsers::get_role_as_array($role, $role_query);
      
            // User Query Args
            $args = array(
            	$role_query => $role,
            	'number' 	=> $users_per_page,
            	'order' 		=> $order,
            	'orderby' 	=> $orderby,
            	'offset' 	=> $offset + ($users_per_page*$page),
            );
            
            // Include
            if($include){
               $args['include'] = array($include);
            }
            
            // Exclude
            if($exclude){
               $args['exclude'] = array($exclude);
            }
            
            // Meta Query
      		if(!empty($meta_key) && !empty($meta_value) || !empty($meta_key) && $meta_compare !== "IN"){
         		
         		// Parse multiple meta query    
               $meta_query_total = count(explode(":", $meta_key)); // Total meta_query objects
               $meta_keys = explode(":", $meta_key); // convert to array
               $meta_value = explode(":", $meta_value); // convert to array
               $meta_compare = explode(":", $meta_compare); // convert to array
               $meta_type = explode(":", $meta_type); // convert to array
               
      			// Loop Meta Query
               $args['meta_query'] = array(
      			   'relation' => $meta_relation
               );
      			for($mq_i = 0; $mq_i < $meta_query_total; $mq_i++){
      				$args['meta_query'][] = alm_get_meta_query($meta_keys[$mq_i], $meta_value[$mq_i], $meta_compare[$mq_i], $meta_type[$mq_i]);
      			}
      		}
      	   
            // Meta_key, used for ordering by meta value
            if(!empty($meta_key)){
               if (strpos($orderby, 'meta_value') !== false) { // Only order by meta_key, if $orderby is set to meta_value{_num}
                  $meta_key_single = explode(":", $meta_key);
                  $args['meta_key'] = $meta_key_single[0];
               }
            }
	         
	         
	         /*
   			 *	alm_users_query_args_{id}
   			 *
   			 * ALM Users Filter Hook
   			 *
   			 * @return $args;
   			 */
   		   $args = apply_filters('alm_users_query_args_'.$id, $args, $post_id);
      
      
      		// WP_User_Query
            $user_query = new WP_User_Query( $args );    
                  
            if($queryType === 'totalposts') {
      	      
      	      $return = array(
      		   	'totalposts' =>  (!empty($user_query->results)) ? $user_query->total_users : 0
      		   ); 
      	      
            } else {
      
      	      $alm_page = $page;
      	      $alm_item = 0;
      	      $alm_current = 0;
      	      $alm_page_count = ($page == 0) ? 1 : $page + 1;
      	
      			if (!empty($user_query->results)) {
      				
      				ob_start();
      	
      				$alm_post_count = count($user_query->results); // total for this query
      				$alm_found_posts = $user_query->total_users; // total of entire query
      	
      				foreach ( $user_query->results as $user ) {
      	
      					$alm_item++;
      	            $alm_current++;
      	            $alm_item = ($alm_page_count * $users_per_page) - $users_per_page + $alm_loop_count; // Get current item
      	
      					// Repeater Template
      					if($theme_repeater != 'null' && has_action('alm_get_theme_repeater')){  // Theme Repeater
      						do_action('alm_get_users_theme_repeater', $theme_repeater, $alm_found_posts, $alm_page, $alm_item, $alm_current, $user);
      					}else{
      						include(alm_get_current_repeater( $repeater, $type )); // Repeater
      					}
      					// End Repeater Template
      	
      				}
      				$data = ob_get_clean();
      	
      	
      				/*
      	          *	alm_cache_file
      	          *
      	          * Cache Add-on hook
      	          * If Cache is enabled, check the cache file
      	          *
      	          * @return null
      	          */
      	         if(!empty($cache_id) && has_action('alm_cache_installed')){
      	            apply_filters('alm_cache_file', $cache_id, $page, $seo_start_page, $data, $preloaded);
      	         }
      	
      			} else {
      				$data = null;
      			}
      	
      			/*
      			 *	alm_debug
      			 *
      			 * ALM Core Filter Hook
      			 *
      			 * @return $alm_query/false;
      			 */
      			$debug = (apply_filters('alm_debug', false)) ? $alm_query : false;
      			
      			
      			// Build return JSON
      			$return = array(
      				'html' => $data,
      				'meta' => array(
      					'postcount'  => (isset($alm_post_count)) ? $alm_post_count : null,
      					'totalposts' => (isset($alm_found_posts)) ? $alm_found_posts : null,
      					'debug' 		 => $debug
      				)
      			);
      		}
      	
      	} else { // Role is empty
      		
      		// Build return JSON
      		$return = array(
      			'html' => null,
      			'meta' => array(
      				'postcount'  => null,
      				'totalposts' => null,
      				'debug' 		 => $debug
      			)
      		);
      		
      	}
      
      	wp_send_json($return);

   	}
   	
   	
   	
   	/*
   	*  get_role_query_type
   	*  Return the role query parameter
   	*  https://codex.wordpress.org/Class_Reference/WP_User_Query#User_Role_Parameter
   	*
   	*  @return string 
   	*  @since 1.1
   	*/
   	public static function get_role_query_type($role){
      	return ($role === 'all') ? 'role' : 'role__in';
   	}
   	
   	
   	
   	/*
   	*  get_role_as_array
   	*  Return the user role(s) as an array
   	*  https://codex.wordpress.org/Class_Reference/WP_User_Query#User_Role_Parameter
   	*
   	*  @return $role array
   	*  @since 1.1
   	*/
   	public static function get_role_as_array($role = 'all'){
      	
      	if($role !== 'all'){                
            $role = preg_replace('/\s+/', '', $role); // Remove whitespace from $role                                 
            $role = explode(',', $role); // Convert $role to Array 
         } else {
            $role = '';
         }
         
         return $role;
   	}
   	


   	/*
   	*  alm_users_shortcode
   	*  Build Users shortcode params and send back to core ALM
   	*
   	*  @since 1.0
   	*/

   	function alm_users_shortcode($users_role, $users_include, $users_exclude, $users_per_page, $users_order, $users_orderby){

   		$return = ' data-users="true"';
   		$return .= ' data-users-role="'. alm_role_encrypt($users_role).'"';
   		$return .= ' data-users-include="'.$users_include.'"';
   		$return .= ' data-users-exclude="'.$users_exclude.'"';
   		$return .= ' data-users-per-page="'.$users_per_page.'"';
   		$return .= ' data-users-order="'.$users_order.'"';
   		$return .= ' data-users-orderby="'.$users_orderby.'"';

		   return $return;
   	}



   	/*
   	*  alm_users_installed
   	*  an empty function to determine if users is true.
   	*
   	*  @since 1.0
   	*/

   	function alm_users_installed(){
   	   //Empty return
   	}



   	/*
   	*  alm_users_settings
   	*  Create the Comments settings panel.
   	*
   	*  @since 1.0
   	*/

   	function alm_users_settings(){
      	register_setting(
      		'alm_users_license',
      		'alm_users_license_key',
      		'alm_users_sanitize_license'
      	);
   	}

   }


	function alm_role_encrypt($string, $key=5) {
		$result = '';
		for($i=0, $k= strlen($string); $i<$k; $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result .= $char;
		}
		return base64_encode($result);
	}

	function alm_role_decrypt($string, $key=5) {
		$result = '';
		$string = base64_decode($string);
		for($i=0,$k=strlen($string); $i< $k ; $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}



   /*
   *  alm_users_sanitize_license
   *  Sanitize the license activation
   *
   *  @since 1.0.0
   */

   function alm_users_sanitize_license( $new ) {
   	$old = get_option( 'alm_users_license_key' );
   	if( $old && $old != $new ) {
   		delete_option( 'alm_users_license_status' ); // new license has been entered, so must reactivate
   	}
   	return $new;
   }



   /*
   *  ALMUsers
   *  The main function responsible for returning Ajax Load More Users.
   *
   *  @since 1.0
   */

   function ALMUsers(){
   	global $ALMUsers;
   	if( !isset($ALMUsers) ){
   		$ALMUsers = new ALMUsers();
   	}
   	return $ALMUsers;
   }
   ALMUsers(); // initialize


endif; // class_exists check


/* Software Licensing */
function alm_users_plugin_updater() {
   if(!has_action('alm_pro_installed') && class_exists('EDD_SL_Plugin_Updater')){ // Don't check for updates if Pro is activated
		$license_key = trim( get_option( 'alm_users_license_key' ) );
		$edd_updater = new EDD_SL_Plugin_Updater( ALM_STORE_URL, __FILE__, array(
				'version' 	=> ALM_USERS_VERSION,
				'license' 	=> $license_key,
				'item_id'   => ALM_USERS_ITEM_NAME,
				'author' 	=> 'Darren Cooney'
			)
		);
	}
}
add_action( 'admin_init', 'alm_users_plugin_updater', 0 );
/* End Software Licensing */
