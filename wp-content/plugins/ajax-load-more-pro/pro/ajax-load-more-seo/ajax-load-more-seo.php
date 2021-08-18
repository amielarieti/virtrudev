<?php
/*
Plugin Name: Ajax Load More: SEO
Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/search-engine-optimization/
Description: Ajax Load More extension to generate unique paging URLs with each query.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: https://connekthq.com
Version: 1.9.3
License: GPL
Copyright: Darren Cooney & Connekt Media
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('ALM_SEO_PATH', plugin_dir_path(__FILE__));
define('ALM_SEO_URL', plugins_url('', __FILE__));
define('ALM_SEO_VERSION', '1.9.3');
define('ALM_SEO_RELEASE', 'May 1, 2020');


/*
*  alm_seo_install
*  Install the SEO add-on
*
*  @since 1.0
*/

register_activation_hook( __FILE__, 'alm_seo_install' );
function alm_seo_install() {
   //if Ajax Load More is activated
   if(!is_plugin_active('ajax-load-more/ajax-load-more.php')){
   	die(__('You must install and activate <a href="https://wordpress.org/plugins/ajax-load-more/">Ajax Load More</a> before installing the ALM SEO Add-on.', 'ajax-load-more-seo'));
	}
}


if( !class_exists('ALMSEO') ):

   class ALMSEO{

   	function __construct(){
   		add_action('alm_seo_installed', array(&$this, 'alm_is_seo_installed'));
   		add_action('wp_enqueue_scripts', array(&$this, 'alm_seo_enqueue_scripts'));
   		add_action('alm_seo_settings', array(&$this, 'alm_seo_settings'));
   		add_filter('alm_seo_shortcode', array(&$this, 'alm_seo_shortcode'), 10, 3);
   		load_plugin_textdomain( 'ajax-load-more-seo', false, dirname(plugin_basename( __FILE__ )).'/lang/'); //load text domain
   	}



   	/*
   	*  alm_seo_shortcode
   	*  This function will build SEO shortcode params and send back to core ALM
   	*
   	*  @return $return string
   	*  @since 1.2
   	*/

   	function alm_seo_shortcode($seo, $preloaded, $options){

		   $seo_scrolltop = '30';
   		if(isset($options['_alm_seo_scrolltop'])){
   			$seo_scrolltop = $options['_alm_seo_scrolltop'];
   		}

		   $seo_controls = '1';
   		if(isset($options['_alm_seo_browser_controls'])){
   			$seo_controls = $options['_alm_seo_browser_controls'];
   		}

			$seo_enable_scroll = 'false';
   		if(isset($options['_alm_seo_scroll'])){
   			$seo_enable_scroll = $options['_alm_seo_scroll'];
   			if($seo_enable_scroll == '1'){
      		   $seo_enable_scroll = 'true';
            }else{
      		   $seo_enable_scroll = 'false';
      		}
         }else{
            $seo_enable_scroll = 'false';
   		}

   		// GA send Pageview
   		if(isset($options['_alm_seo_ga'])){
      		$seo_send_pageview = $options['_alm_seo_ga'];
      		if($seo_send_pageview == '1'){
      		   $seo_send_pageview = 'true';
            }else{
      		   $seo_send_pageview = 'false';
      		}
         } else {
            $seo_send_pageview = 'true';
         }

   		// Permalink Structure
   		// @updated 1.6
   		$permalink_structure = get_option('permalink_structure');
   		if(empty($permalink_structure)){ // empty option value is ?page=12345
      		$seo_permalink = "default";
   		}else{
      		$seo_permalink = 'pretty';
   		}


		   // Get $paged var from WP
		   if ( get_query_var('paged') ) {
           $current_page = get_query_var('paged');
         } elseif ( get_query_var('page') ) {
           $current_page = get_query_var('page');
         } else {
           $current_page = 1;
         }

         // If preloaded then minus 1 page from SEO
         $current_page = ($preloaded === 'true') ? $current_page - 1 : $current_page;


			// Build data atts
         $return = ' data-seo="'.$seo.'"';
			$return .= ' data-seo-start-page="'.$current_page.'"';
			$return .= ' data-seo-scroll="'.$seo_enable_scroll.'"';
			$return .= ' data-seo-scrolltop="'.$seo_scrolltop.'"';
			$return .= ' data-seo-controls="'.$seo_controls.'"';
			$return .= ' data-seo-permalink="'.$seo_permalink.'"';
			$return .= ' data-seo-pageview="'.$seo_send_pageview.'"';



			/*
	   	 *	alm_seo_remove_trailing_slash (SEO Add-on hook)
	   	 * This function will remove the trailing slash from the URL.
	   	 *
	   	 * @return $trailing_slash;
	   	 * @since 1.7
	   	 */
			$trailing_slash = apply_filters('alm_seo_remove_trailing_slash', '');
			if($trailing_slash) $return .= ' data-seo-trailing-slash="false"';



			/*
	   	 *	alm_seo_remove_trailing_slash (SEO Add-on hook)
	   	 * This function will remove the leading slash from the URL.
	   	 *
	   	 * @return $trailing_slash;
	   	 * @since 1.7
	   	 */
			$leading_slash = apply_filters('alm_seo_leading_slash', '');
			if($leading_slash) $return .= ' data-seo-leading-slash="true"';


			return $return;
   	}



   	/*
   	*  alm_seo_enqueue_scripts
   	*  Enqueue our scripts
   	*
   	*  @since 1.0
   	*/

   	function alm_seo_enqueue_scripts(){

	   	// Use minified libraries if SCRIPT_DEBUG is turned off
	   	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

   		wp_register_script( 'ajax-load-more-seo', plugins_url( '/js/alm-seo'. $suffix .'.js', __FILE__ ), array('ajax-load-more'),  ALM_SEO_VERSION, true );
   	}



   	/*
   	*  alm_is_seo_installed
   	*  an empty function to determine if seo is true.
   	*
   	*  @since 1.0
   	*/

   	function alm_is_seo_installed(){
   	   //Empty return
   	}



   	/*
   	*  alm_seo_settings
   	*  Create the SEO settings panel.
   	*
   	*  @since 1.2
   	*/

   	function alm_seo_settings(){
      	register_setting(
      		'alm_seo_license',
      		'alm_seo_license_key',
      		'alm_seo_sanitize_license'
      	);
   	   add_settings_section(
	   		'alm_seo_settings',
	   		'SEO Settings',
	   		'alm_seo_settings_callback',
	   		'ajax-load-more'
	   	);
	   	add_settings_field(
	   		'_alm_seo_ga',
	   		__('Google Analytics', 'ajax-load-more-seo' ),
	   		'_alm_seo_ga_callback',
	   		'ajax-load-more',
	   		'alm_seo_settings'
	   	);
	   	add_settings_field(
	   		'_alm_seo_scroll',
	   		__('Scroll to Page', 'ajax-load-more-seo' ),
	   		'_alm_seo_scroll_callback',
	   		'ajax-load-more',
	   		'alm_seo_settings'
	   	);
	   	add_settings_field(
	   		'_alm_seo_scrolltop',
	   		__('Scroll Top', 'ajax-load-more-seo' ),
	   		'_alm_seo_scrolltop_callback',
	   		'ajax-load-more',
	   		'alm_seo_settings'
	   	);
	   	add_settings_field(
	   		'_alm_seo_browser_controls',
	   		__('Back/Fwd Buttons', 'ajax-load-more-seo' ),
	   		'_alm_seo_browser_controls_callback',
	   		'ajax-load-more',
	   		'alm_seo_settings'
	   	);
   	}

   }


   /* SEO Settings (Displayed in ALM Core) */


	/*
	*  alm_seo_settings_callback
	*  SEO Setting Heading
	*
	*  @since 1.0
	*/

	function alm_seo_settings_callback() {
	   $html = '<p>' . __('Customize your installation of the <a href="http://connekthq.com/plugins/ajax-load-more/seo/">Search Engine Optimization</a> add-on.', 'ajax-load-more-seo') . '</p>';

	   echo $html;
	}



	/*
	*  _alm_seo_ga_callback
	*  Send pageviews to Google Analytics
	*
	*  @since 1.2
	*/

	function _alm_seo_ga_callback(){
		$options = get_option( 'alm_settings' );
		if(!isset($options['_alm_seo_ga']))
		   $options['_alm_seo_ga'] = '1';

		$html = '<input type="hidden" name="alm_settings[_alm_seo_ga]" value="0" /><input type="checkbox" id="alm_seo_ga" name="alm_settings[_alm_seo_ga]" value="1"'. (($options['_alm_seo_ga']) ? ' checked="checked"' : '') .' />';
		$html .= '<label for="alm_seo_ga">'.__('Send page views to Google Analytics.', 'ajax-load-more-seo').'<br/><span>'.__('Must have a reference to your Google Analytics tracking code on the page.', 'ajax-load-more-seo').'</span></label>';

		echo $html;
	}



	/*
	*  _alm_seo_scroll_callback
	*  Set the speed of auto scroll
	*
	*  @since 1.0
	*/

	function _alm_seo_scroll_callback() {

	   $options = get_option( 'alm_settings' );

	   if(!isset($options['_alm_seo_scroll'])){
			$options['_alm_seo_scroll'] = '0';
		}

		$html = '<input type="hidden" name="alm_settings[_alm_seo_scroll]" value="0" />';
		$html .= '<input type="checkbox" name="alm_settings[_alm_seo_scroll]" id="alm_scroll_page" value="1"'. (($options['_alm_seo_scroll']) ? ' checked="checked"' : '') .' />';
		$html .= '<label for="alm_scroll_page">'.__('Enable window scrolling', 'ajax-load-more-seo').'<br/><span>'.__('If scrolling is enabled, the users window will scroll to the current page on \'Load More\' action.', 'ajax-load-more-seo').'</span></label>';

		echo $html;
	}



	/*
	*  _alm_seo_scrolltop_callback
	*  Set the scrlltop value of window scrolling
	*
	*  @since 1.0
	*/

	function _alm_seo_scrolltop_callback() {

	    $options = get_option( 'alm_settings' );

	    if(!isset($options['_alm_seo_scrolltop']))
		   $options['_alm_seo_scrolltop'] = '30';


		echo '<label for="alm_settings[_alm_seo_scrolltop]">'.__('Set the scrolltop position of the window when scrolling to post.', 'ajax-load-more-seo').'</label><br/><input type="number" class="sm" id="alm_settings[_alm_seo_scrolltop]" name="alm_settings[_alm_seo_scrolltop]" step="1" min="0" value="'.$options['_alm_seo_scrolltop'].'" placeholder="30" /> ';

	}






	/*
	*  _alm_seo_browser_controls_callback
	*  Disable back/fwd button when URLs updated (uses replaceState vs pushState)
	*
	*  @since 1.7
	*/

	function _alm_seo_browser_controls_callback() {

	   $options = get_option( 'alm_settings' );

		if(!isset($options['_alm_seo_browser_controls']))
		   $options['_alm_seo_browser_controls'] = '1';

		$html = '<input type="hidden" name="alm_settings[_alm_seo_browser_controls]" value="0" />';
		$html .='<input type="checkbox" id="_alm_seo_browser_controls" name="alm_settings[_alm_seo_browser_controls]" value="1"'. (($options['_alm_seo_browser_controls']) ? ' checked="checked"' : '') .' />';
		$html .= '<label for="_alm_seo_browser_controls">'.__('Enable Back/Fwd Browser Buttons.', 'ajax-load-more-seo').'<br/><span>'.__('Allow users to navigate Ajax generated content using the back and forward browser buttons.', 'ajax-load-more-seo').'</span></label>';

		echo $html;
	}



   /*
   *  alm_seo_sanitize_license
   *  Sanitize our license activation
   *
   *  @since 1.3.0
   */

   function alm_seo_sanitize_license( $new ) {
   	$old = get_option( 'alm_seo_license_key' );
   	if( $old && $old != $new ) {
   		delete_option( 'alm_seo_license_status' ); // new license has been entered, so must reactivate
   	}
   	return $new;
   }



   /*
   *  ALMSEO
   *  The main function responsible for returning Ajax Load More SEO.
   *
   *  @since 1.0
   */

   function ALMSEO(){
   	global $ALMSEO;
   	if(!isset($ALMSEO)){
   		$ALMSEO = new ALMSEO();
   	}
   	return $ALMSEO;
   }


   // initialize
   ALMSEO();

endif; // class_exists check


/* Software Licensing */

function alm_seo_plugin_updater() {
	if(!has_action('alm_pro_installed') && class_exists('EDD_SL_Plugin_Updater')){ // Don't check for updates if Pro is activated
		$license_key = trim( get_option( 'alm_seo_license_key' ) ); // retrieve our license key from the DB
		$edd_updater = new EDD_SL_Plugin_Updater( ALM_STORE_URL, __FILE__, array(
				'version' 	=> ALM_SEO_VERSION,
				'license' 	=> $license_key,
				'item_id'   => ALM_SEO_ITEM_NAME,
				'author' 	=> 'Darren Cooney'
			)
		);
	}
}
add_action( 'admin_init', 'alm_seo_plugin_updater', 0 );

/* End Software Licensing */
