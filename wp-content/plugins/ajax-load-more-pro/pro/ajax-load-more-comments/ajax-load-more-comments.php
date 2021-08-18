<?php
/*
Plugin Name: Ajax Load More: Comments
Plugin URI: https://connekthq.com/plugins/ajax-load-more/comments/
Description: Ajax Load More extension to infinite scroll blog comments.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: https://connekthq.com
Version: 1.2.0.1
License: GPL
Copyright: Darren Cooney & Connekt Media
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly	


define('ALM_COMMENTS_PATH', plugin_dir_path(__FILE__));
define('ALM_COMMENTS_URL', plugins_url('', __FILE__));
define('ALM_COMMENTS_VERSION', '1.2.0.1');
define('ALM_COMMENTS_RELEASE', 'November 18, 2019');



/*
*  alm_comments_install
*  Install the Comments add-on
*
*  @since 1.0
*/

register_activation_hook( __FILE__, 'alm_comments_install' );
function alm_comments_install() {   
   if(!is_plugin_active('ajax-load-more/ajax-load-more.php')){	//if Ajax Load More is activated
   	die('You must install and activate <a href="https://wordpress.org/plugins/ajax-load-more/">Ajax Load More</a> before installing the Ajax Load More Comments add-on.');
	}	
}


$GLOBALS['alm_comment_repeater'] = '';
$GLOBALS['alm_comment_repeater_type'] = '';
		

if( !class_exists('ALMComments') ):

   class ALMComments{	   
      
   	function __construct(){		
   		 
   		add_action( 'alm_comments_installed', array(&$this, 'alm_comments_installed') );	
   	   add_action( 'wp_ajax_alm_comments', array(&$this, 'alm_comments_query') );
   	   add_action( 'wp_ajax_nopriv_alm_comments', array(&$this, 'alm_comments_query') );
   		add_filter( 'alm_comments_shortcode', array(&$this, 'alm_comments_shortcode'), 10, 7 ); 
   		add_filter( 'alm_comments_preloaded', array(&$this, 'alm_comments_preloaded'), 10, 1 ); 	
   		add_action( 'alm_comments_settings', array(&$this, 'alm_comments_settings' ));
   	}   	  
   	
   	
   	
   	/*
   	*  alm_comments_preloaded
   	*  Preload comments if preloaded is true in alm shortcode
   	*
   	*  @since 1.1
   	*/
   	public function alm_comments_preloaded($args){  
	   	         	
      	$comments = (isset($args['comments'])) ? $args['comments'] : false;	
         $comments_callback = (isset($args['comments_callback'])) ? $args['comments_callback'] : '';
         $comments_template = (isset($args['comments_template'])) ? $args['comments_template'] : 'none';	
         $GLOBALS['alm_comment_repeater'] = $comments_template;
   		$comments_template_type = preg_split('/(?=\d)/', $comments_template, 2); // split $comments_template value at number to determine type
   		$comments_template_type = $comments_template_type[0]; // default | repeater | template_      
         $GLOBALS['alm_comment_repeater_type'] = $comments_template_type;      				
         
   		$comments_order = (isset($args['order'])) ? $args['order'] : 'DESC';	
   		$comments_orderby = (isset($args['orderby'])) ? $args['orderby'] : 'date';	
   		$comments_post_id = (isset($args['comments_post_id'])) ? $args['comments_post_id'] : 'null';	
   		$comments_per_page = (isset($args['comments_per_page'])) ? $args['comments_per_page'] : '5';	
   		$comments_type = (isset($args['comments_type'])) ? $args['comments_type'] : 'comment';	
   		$comments_style = (isset($args['comments_style'])) ? $args['comments_style'] : 'ul';
         $orderby = (isset($args['orderby'])) ? $args['orderby'] : 'date';	
         $order = (isset($args['order'])) ? $args['order'] : 'DESC';
         $offset = (isset($_GET['offset'])) ? $_GET['offset'] : 0;	


   		// If callback is empty, look for a template selected
   		if($comments_callback === ''){
      		$comments_callback = ($comments_template_type !== 'none') ? 'alm_comment' : $comments_callback;
   		}
   		
		   $alm_comments_args = array(
      		'status' => 'approve',
      		'post_id' => $comments_post_id,
      		'number' => 999,
      		'offset' => $offset,
      		/*
	      		Offset does not work unless we limit the amount using 'number'.
	      		Also, offsetting this query results in the newest comments (nested or top level) being removed and not necessarily the correct one
	      	*/ 
      		'orderby' => $orderby,
      		'order' => $order
   		);      		
   		$alm_comments_query = get_comments($alm_comments_args); // Query for comments by post id
   		      		
   		ob_start();
         
         wp_list_comments(array(
            'style' 		=> $comments_style,
            'page' 		=> 1,
            'per_page' 	=> intval($comments_per_page),
            'callback' 	=> $comments_callback,
            'type' 		=> $comments_type,
				'reverse_top_level' => false
         ), $alm_comments_query); 
         
         $data = ob_get_clean();	
         			
			return $data;    	
      	
   	}
   	
   	
   	
   	/**
   	 * alm_comments_query
   	 * Query comments, send results via ajax as JSON
   	 *
   	 * @since 1.0
   	 */
   	function alm_comments_query(){
	   	
	   	if(!isset($_GET)){ return false; } 
		
		   $queryType = (isset($_GET['query_type'])) ? $_GET['query_type'] : 'standard';	// 'standard' or 'totalposts'; totalposts returns $alm_found_posts         
		         
		   // Comment data array - from ajax-load-more.js
		   $data = (isset($_GET['comments'])) ? $_GET['comments'] : '';
		   $offset = (isset($_GET['offset'])) ? $_GET['offset'] : 0;	
		   $orderby = (isset($_GET['orderby'])) ? $_GET['orderby'] : 'date';	
		   $order = (isset($_GET['order'])) ? $_GET['order'] : 'DESC';	
		            
		   $preloaded = (isset($_GET['preloaded'])) ? $_GET['preloaded'] : false;              
		   
		   if($data){
		      
				$comments = (isset($data['comments'])) ? $data['comments'] : false;	
		      $comments_callback = (isset($data['callback'])) ? $data['callback'] : '';
		      $comments_template = (isset($data['template'])) ? $data['template'] : 'none';	
		      $GLOBALS['alm_comment_repeater'] = $comments_template;
				$comments_template_type = preg_split('/(?=\d)/', $comments_template, 2); // split $comments_template value at number to determine type
				$comments_template_type = $comments_template_type[0]; // default | repeater | template_      
		      $GLOBALS['alm_comment_repeater_type'] = $comments_template_type;      				
				$page = (isset($_GET['page'])) ? $_GET['page'] : 0;    			
		      
				$comments_order = (isset($data['order'])) ? $data['order'] : 'DESC';	
				$comments_orderby = (isset($data['orderby'])) ? $data['orderby'] : 'date';	
				$comments_post_id = (isset($data['post_id'])) ? $data['post_id'] : 'null';	
				$comments_per_page = (isset($data['per_page'])) ? $data['per_page'] : '5';	
				$comments_type = (isset($data['type'])) ? $data['type'] : 'comment';	
				$comments_style = (isset($data['style'])) ? $data['style'] : 'ul';	  
				
   			// Paging Add-on
   			$paging = (isset($_GET['paging'])) ? $_GET['paging'] : 'false';   
   			
   			if($paging === 'true' && $preloaded === 'true'){
      			$page = $page - 1;
   			} 		
				
				// If callback is empty, look for a template selected
				if($comments_callback === ''){
		   		$comments_callback = ($comments_template_type !== 'none') ? 'alm_comment' : $comments_callback;
				}		
				
				// Preloaded - add a page
				$page = ($preloaded === 'true') ? $page + 1 : $page;
				
				if($comments === 'true'){
		   		
		   		$alm_comments_args = array(
		      		'status' 	=> 'approve',
		      		'post_id' 	=> $comments_post_id,
		      		'number' 	=> 999,
		      		'offset' 	=> $offset,
		      		/*
			      		Offset does not work unless we limit the amount using 'number'.
			      		Also, offsetting this query results in the newest comments (nested or top level) being removed and not necessarily the correct one
			      	*/ 
		      		'orderby' 	=> $orderby,
		      		'order' 		=> $order,
		   		);
		   		         		
		   		$post_comments = get_comments($alm_comments_args); // Query comments by post id  
		   		
		   		// Total overall comments   
		   		$alm_found_comments = 0;
		   		
					foreach($post_comments as $comment) {
					   // if the comment has no parent, count it!
					   // This is becasue wp_list_comments does not count replies
					   if($comment->comment_parent == 0) { 
						   $alm_found_comments++; 
						}
					}  
										
					if($queryType === 'totalposts'){	
																	
						// Paging add-on	
									
						$return = array(
							'totalposts' => $alm_found_comments
						);
						wp_send_json($return); 
						
		         } else {
			         
		   			// Standard ALM
		   			
		      		ob_start();
		
		      		wp_list_comments(array(
		               'style' 		=> $comments_style,
		               'page' 		=> $page + 1,
		               'per_page' 	=> intval($comments_per_page),
		               'callback' 	=> $comments_callback,
		               'type' 		=> $comments_type,
							'reverse_top_level' => false
		            ), $post_comments);               
		            
		            $comment_data = ob_get_clean();
						
						if($comment_data){   								          
		               $return = array(
		                  'html' => $comment_data,
		                  'meta'  => array(
		                     'postcount' => intval($comments_per_page),
		                     'totalposts' => $alm_found_comments
		                  )
		               );               
		            } else{               
		               $return = array(
		                  'html' => '',
		                  'meta'  => array(
		                     'postcount' => null,
		                     'totalposts' => null
		                  )
		               );
		            }
		            
		            wp_send_json($return);            
		         }         
				}		
		   } 			   
			wp_die();			
   	}
   	
   	
   	
   	/**
   	 * alm_comments_shortcode
   	 * Build Comments shortcode params and send back to core ALM
   	 *
   	 * @since 1.0
   	 */
   	
   	function alm_comments_shortcode($comments, $comments_per_page, $comments_type, $comments_style, $comments_template, $comments_callback, $comments_post_id){
   		
   		$return = ' data-comments="'.$comments.'"';
   		$return .= ' data-comments_per_page="'.$comments_per_page.'"';
   		$return .= ' data-comments_type="'.$comments_type.'"';
   		$return .= ' data-comments_style="'.$comments_style.'"';
   		$return .= ' data-comments_template="'.$comments_template.'"';
   		$return .= ' data-comments_callback="'.$comments_callback.'"';
   		$return .= ' data-comments_post_id="'.$comments_post_id.'"';
		   
		   return $return;
   	}
	    	
   	
   	
   	/*
   	*  alm_comments_installed
   	*  an empty function to determine if comments is true.
   	*
   	*  @since 1.0
   	*/
   	
   	function alm_comments_installed(){
   	   //Empty return
   	}	
   	
   	
   	
   	/*
   	*  alm_comments_settings
   	*  Create the Comments settings panel.
   	*
   	*  @since 1.0
   	*/
   	
   	function alm_comments_settings(){
      	register_setting(
      		'alm_comments_license', 
      		'alm_comments_license_key', 
      		'alm_comments_sanitize_license'
      	);
   	}
   	
   }  
   
   
   /*
   	*  alm_comment
   	*  Custom comment styling callback (wp_list_comments())
   	*
   	*  @since 1.0
   	*/
   function alm_comment($comment, $args, $depth) {
      $comment_repeater = $GLOBALS['alm_comment_repeater'];
      $comment_repeater_type = $GLOBALS['alm_comment_repeater_type'];
      include( alm_get_current_repeater($comment_repeater, $comment_repeater_type) );//Include repeater template
   
   } 
   
   
   
   /*
   *  alm_comments_sanitize_license
   *  Sanitize our license activation
   *
   *  @since 1.0.0
   */
   
   function alm_comments_sanitize_license( $new ) {
   	$old = get_option( 'alm_comments_license_key' );
   	if( $old && $old != $new ) {
   		delete_option( 'alm_comments_license_status' ); // new license has been entered, so must reactivate
   	}
   	return $new;
   }
   
   	
   	
   /*
   *  ALMComments
   *  The main function responsible for returning Ajax Load More Comments.
   *
   *  @since 1.0
   */	
   
   function ALMComments(){
   	global $alm_comments;
   
   	if( !isset($alm_comments) ){
   		$alm_comments = new ALMComments();
   	}
   
   	return $alm_comments;
   }
   
   // initialize
   ALMComments();

endif; // class_exists check




/* Software Licensing */
function alm_comments_plugin_updater() {	
	if(!has_action('alm_pro_installed') && class_exists('EDD_SL_Plugin_Updater')){ // Don't check for updates if Pro is activated
		$license_key = trim( get_option( 'alm_comments_license_key' ) );
		$edd_updater = new EDD_SL_Plugin_Updater( ALM_STORE_URL, __FILE__, array( 
				'version' 	=> ALM_COMMENTS_VERSION,
				'license' 	=> $license_key,
				'item_id'   => ALM_COMMENTS_ITEM_NAME,
				'author' 	=> 'Darren Cooney'
			)
		);
	}
}
add_action( 'admin_init', 'alm_comments_plugin_updater', 0 );	
/* End Software Licensing */
