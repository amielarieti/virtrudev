<?php
   		

/**
 * alm_cache_create
 * Create Cache from HTML data
 *
 * @since 1.0
 */   
function alm_cache_create_html() {   
		
	//if ( !check_ajax_referer( 'ajax_load_more_nonce', 'security' , false)){
	if (!wp_verify_nonce($_POST['security'], 'ajax_load_more_nonce')) {	
		wp_send_json_error( 'Invalid security token.' );
		wp_die();
	};

	$html = (isset($_POST['html'])) ? trim(stripcslashes($_POST['html'])) : false;
	$cache_id = (isset($_POST['cache_id'])) ? $_POST['cache_id'] : '';
	$cache_logged_in = (isset($_POST['cache_logged_in'])) ? $_POST['cache_logged_in'] : false;
	$do_create_cache = ($cache_logged_in === 'true' && is_user_logged_in()) ? false : true;	
	$canonical_url = (isset($_POST['canonical_url'])) ? esc_url($_POST['canonical_url']) : esc_url($_SERVER['HTTP_REFERER']);	
	$name = (isset($_POST['name'])) ? $_POST['name'] : 0; // The name for the cached page 	
	
	
	if( !$html || !$name || !has_action('alm_cache_installed') || !$do_create_cache ) { // Exit if required
		return false;
	}


   /*
	 *	alm_cache_create_dir
	 *
	 * Cache Add-on hook
	 * Create cache directory + meta .txt file
	 *
	 * @return null
	 */
   if(!empty($cache_id) && has_action('alm_cache_create_dir') && $do_create_cache){
      apply_filters('alm_cache_create_dir', $cache_id, $canonical_url);
   }	


   /*
	 *	alm_html_cache_file
	 *
	 * Cache Add-on hook
	 * If Cache is enabled, check the cache file
	 *
	 * @param $cache_id          String     ID of the ALM cache
	 * @param $do_create_cache   Boolean    Should cache be created for this user
	 *
	 * @updated 3.2.1
	 * @return null
	 */
   if(!empty($cache_id)){
      apply_filters('alm_html_cache_file', $cache_id, $name, $html);
   }		         		

	$return = array(
      'success' => true,
      'msg' => 'Cache created for post '. $name .'.'
   );
   
   wp_send_json($return);
   
}
	
add_action( 'wp_ajax_alm_cache_from_html', 'alm_cache_create_html' );
add_action( 'wp_ajax_nopriv_alm_cache_from_html', 'alm_cache_create_html' );
