<?php
/*
Plugin Name: Ajax Load More: Layouts
Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/theme-repeaters/
Description: Ajax Load More extension that adds predefined layouts for your repeater templates.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: http://connekthq.com
Version: 1.3.1
Copyright: Darren Cooney & Connekt Media
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define('ALM_LAYOUTS_VERSION', '1.3.1');
define('ALM_LAYOUTS_RELEASE', 'April 22, 2020');
define('ALM_LAYOUTS_PATH', plugin_dir_path(__FILE__)); 
define('ALM_LAYOUTS_URL', plugins_url('', __FILE__));	 


/*
*  alm_layouts_install
*  
*  Activation hook
*
*  @since 1.0
*/

function alm_layouts_install() {   
   //if Ajax Load More is activated
   if(!is_plugin_active('ajax-load-more/ajax-load-more.php')){	
   	die('You must install and activate <a href="https://wordpress.org/plugins/ajax-load-more/">Ajax Load More</a> before installing the ALM Local Repeaters Add-on.');
	}
}
register_activation_hook( __FILE__, 'alm_layouts_install' );




if( !class_exists('alm_layouts') ):
   class alm_layouts{	      
   	function __construct(){		   	  	
   		add_action( 'after_setup_theme', array(&$this, 'alm_layouts_image_sizes' ));
   		add_action( 'alm_layouts_installed', array(&$this, 'alm_layouts_installed') );
   		add_action( 'alm_layouts_settings', array(&$this, 'alm_layouts_settings' ));	
   		add_action( 'alm_layouts_custom_css', array(&$this, 'alm_layouts_custom_css' ), 10, 2);	
   		add_action( 'wp_enqueue_scripts', array(&$this, 'alm_layouts_enqueue_scripts' )); 		
         add_action( 'alm_get_layouts_add_on', array(&$this, 'alm_get_layouts_add_on' ));          		
   	}  
   	
   	
   	
   	/*
   	*  alm_layouts_custom_css
   	*  Return custom CSS from ALM Settings page
   	*
   	*  @since 1.0
   	*/
   	
   	function alm_layouts_custom_css($num){
      	if($num === 1){         	
      	   $options = get_option( 'alm_settings' );
      	   if(isset($options['_alm_layouts_css'])){
         	   if($options['_alm_layouts_css']){
         		   echo '<style>'. $options['_alm_layouts_css'] .'</style>';
         		}
      		}
   		}
   	}
   	
   	
   	
   	/*
   	*  alm_layouts_image_sizes
   	*  Add the required image sizes
   	*
   	*  @since 1.0
   	*/
   	
   	function alm_layouts_image_sizes(){   
			add_image_size( 'alm-cta', 800, 450, true); // cta
			add_image_size( 'alm-gallery', 800, 600, true); // gallery
   	}  
      
      
      
      /*
      *  alm_get_layouts_add_on
      *  Get custom layouts list
      *
      *  @since 1.0
      */
      
      function alm_get_layouts_add_on(){
         include( ALM_LAYOUTS_PATH . 'admin/includes/layout-options.php');  
      }
   	
   	
   	
   	/*
   	*  alm_layouts_installed
   	*  an empty function to determine if add-on is activated.
   	*
   	*  @since 1.0
   	*/
   	
   	function alm_layouts_installed(){
   	   //Empty return
   	} 
   	
   	
   	
   	/*
   	*  alm_layouts_enqueue_scripts
   	*  Enqueue our scripts
   	*
   	*  @since 1.0
   	*/
   
   	function alm_layouts_enqueue_scripts(){
	   	 		  	
   		// Enqueue CSS
   		if(!alm_do_inline_css('_alm_inline_css')){ // Not inline
	   		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min'; // Use minified libraries if SCRIPT_DEBUG is turned off
         	$file = ALM_LAYOUTS_URL.'/core/css/ajax-load-more-layouts'. $suffix .'.css';          	
         	if(class_exists('ALM_ENQUEUE')){
            	ALM_ENQUEUE::alm_enqueue_css('ajax-load-more-layouts', $file);
         	}
   		}	
   	}  
   	
   	
   	
   	/*
   	*  alm_layouts_settings
   	*  Create the Layouts settings panel.
   	*
   	*  @since 1.0
   	*/
   	
   	function alm_layouts_settings(){
      	register_setting(
      		'alm_layouts_license', 
      		'alm_layouts_license_key', 
      		'alm_layouts_sanitize_license'
      	);	      	      	
      	
   	   add_settings_section( 
	   		'alm_layouts_settings',  
	   		'Layouts Settings', 
	   		'alm_layouts_callback', 
	   		'ajax-load-more' 
	   	);	   
	   	
	   	add_settings_field( 
	   		'_alm_layouts_css', 
	   		__('Styling', 'ajax-load-more'), 
	   		'alm_layouts_css_callback', 
	   		'ajax-load-more', 
	   		'alm_layouts_settings' 
	   	);
	   	
   	}	  	   	
   	
   }   
   
   
   /*
   *  alm_layouts_sanitize_license
   *  Sanitize our license activation
   *
   *  @since 1.0
   */
   
   function alm_layouts_sanitize_license( $new ) {
   	$old = get_option( 'alm_layouts_license_key' );
   	if( $old && $old != $new ) {
   		delete_option( 'alm_layouts_license_status' ); // new license has been entered, so must reactivate
   	}
   	return $new;
   }  
   
   /*
	*  alm_layouts_callback
	*  Section setting heading
	*
	*  @since 1.0
	*/
	
	function alm_layouts_callback() {
	   $html = '<p>' . __('Customize your installation of the <a href="https://connekthq.com/plugins/ajax-load-more/add-ons/layouts/">Layouts</a> add-on.', ALM_NAME) . '</p>';
	   
	   echo $html;
	}
	
	
	
	/*
	*  alm_layouts_css_callback
	*  Custom CSS for layouts
	*
	*  @since 1.0
	*/
	
	function alm_layouts_css_callback(){
		$options = get_option( 'alm_settings' );
		
		$html = '<label for="_alm_layouts_css">'.__('Enter Custom Layout CSS <span style="display: block;">Use this section to inject custom CSS related to the Layouts add-on.</span>', 'ajax-load-more');
		$html .= '<span style="display:block;"><i class="fa fa-file-text-o"></i> &nbsp;<a target="blank" href="'.ALM_LAYOUTS_URL.'/core/css/ajax-load-more-layouts.css">View Layouts CSS</a></span>';
		$html .='</label>';
		
		$html .= '<textarea id="_alm_layouts_css" name="alm_settings[_alm_layouts_css]">';
		$html .= $options['_alm_layouts_css'];
		$html .= '</textarea>';	
		
		$html .= '<label style="cursor: default;"><span style="display:block">You should prefix all CSS overrides with <pre style="display:inline;">.alm-layouts .alm-listing .alm-layout{ }</pre></span></label>';
		
		echo $html;
	}
      
      
   /*
   *  Helper Functions
   *  A library opf helpers for layouts
   *
   *  @since 1.0
   */   
   
   // Get custom excerpt
	function alm_get_excerpt($limit, $after = null) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
		} else {
			$excerpt = implode(" ",$excerpt);
		}
		$excerpt = preg_replace('`[[^]]*]`','',$excerpt);		
		if($after)
		   $excerpt = $excerpt . $after;
		   
		if($excerpt)
		   echo '<p>'.$excerpt.'</p>';
	}
	
	// Is item odd?
	function alm_is_odd($number){
		if ($number % 2 !== 0) {
		  echo "odd";
		}
	}  
	
	// Is last item in 3 column layout
	function alm_is_last($number){
		if ($number % 3 == 0) {
		  echo "last";
		}
	}  
	
	// Is last item in 4 column layout
	function alm_is_4col_last($number){
		if ($number % 4 == 0) {
		  echo "last";
		}
	} 
         
   	
   	
   /*
   *  ALMLAYOUTS
   *  The main function responsible for returning Ajax Load More Local Templates.
   *
   *  @since 1.0
   */	
   
   function alm_layouts(){
   	global $alm_layouts;
   
   	if( !isset($alm_layouts) )
   	{
   		$alm_layouts = new alm_layouts();
   	}
   
   	return $alm_layouts;
   }
      
   
   // initialize
   alm_layouts();

endif; // class_exists check


/* Software Licensing */
function alm_layouts_plugin_updater() {
   if(!has_action('alm_pro_installed') && class_exists('EDD_SL_Plugin_Updater')){ // Don't check for updates if Pro is activated
   	$license_key = trim( get_option( 'alm_layouts_license_key' ) ); // retrieve our license key from the DB
   	$edd_updater = new EDD_SL_Plugin_Updater( ALM_STORE_URL, __FILE__, array( 
   			'version' 	=> ALM_LAYOUTS_VERSION,
   			'license' 	=> $license_key,
   			'item_id'   => ALM_LAYOUTS_ITEM_NAME,
   			'author' 	=> 'Darren Cooney'
   		)
   	);
   }
}
add_action( 'admin_init', 'alm_layouts_plugin_updater', 0 );	
/* End Software Licensing */

