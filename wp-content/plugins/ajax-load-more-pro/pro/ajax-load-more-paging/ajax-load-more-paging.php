<?php
/*
Plugin Name: Ajax Load More: Paging
Plugin URI: https://connekthq.com/plugins/ajax-load-more/paging/
Description: Ajax Load More extension to replace infinite scroll with paging navigation.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: https://connekthq.com
Version: 1.5.4
License: GPL
Copyright: Darren Cooney & Connekt Media
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ALM_PAGING_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_PAGING_URL', plugins_url( '', __FILE__ ) );
define( 'ALM_PAGING_VERSION', '1.5.4' );
define( 'ALM_PAGING_RELEASE', 'September 10, 2020' );


/*
*  alm_seo_install
*  Install the SEO add-on
*
*  @since 1.0
*/

register_activation_hook( __FILE__, 'alm_paging_install' );
function alm_paging_install() {
   //if Ajax Load More is activated
   if(!is_plugin_active('ajax-load-more/ajax-load-more.php')){
   	die('You must install and activate <a href="https://wordpress.org/plugins/ajax-load-more/">Ajax Load More</a> before installing the ALM SEO Add-on.');
	}
}



if( !class_exists('ALMPAGING') ):

   class ALMPAGING{

   	function __construct(){
   		add_action('alm_paging_installed', array(&$this, 'alm_paging_installed'));
   		add_action('wp_enqueue_scripts', array(&$this, 'alm_paging_enqueue_scripts'));
   		add_action('admin_enqueue_scripts', array(&$this, 'alm_paging_admin_enqueue_scripts'));
   		add_action('alm_paging_settings', array(&$this, 'alm_paging_settings'));
   		add_filter('alm_paging_shortcode', array(&$this, 'alm_paging_shortcode'), 10, 9);
   		load_plugin_textdomain( 'ajax-load-more-paging', false, dirname(plugin_basename( __FILE__ )).'/lang/'); //load text domain
   	}



   	/*
   	*  alm_paging_shortcode
   	*  Build Paging shortcode params and send back to core ALM
   	*
   	*  @since 1.2
   	*/
   	function alm_paging_shortcode($paging, $controls, $show_at_most, $classes, $first_label, $last_label, $previous_label, $next_label, $scroll){
         $return = ' data-paging="'.$paging.'"';
         $return .= ' data-paging-controls="'.$controls.'"';
         $return .= ' data-paging-show-at-most="'.$show_at_most.'"';
         $return .= ' data-paging-classes="'.$classes.'"';
         $return .= ' data-paging-first-label="'.$first_label.'"';
         $return .= ' data-paging-last-label="'.$last_label.'"';
         $return .= ' data-paging-previous-label="'.$previous_label.'"';
         $return .= ' data-paging-next-label="'.$next_label.'"';

			if($scroll){
				$scroll_values = explode(':', $scroll);
				if($scroll_values[0] === 'true'){
					$return .= ' data-paging-scroll="true"';
					if(isset($scroll_values[1])){
						$return .= ' data-paging-scrolltop="'. $scroll_values[1] .'"';
					} else {
						$return .= ' data-paging-scrolltop="100"';
					}
				}
			}

         return $return;

   	}



   	/*
   	*  alm_paging_enqueue_scripts
   	*  Enqueue our paging script
   	*
   	*  @since 1.0
   	*/

   	function alm_paging_enqueue_scripts(){

	   	// Use minified libraries if SCRIPT_DEBUG is turned off
	   	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

   		// Enqueue JS
   		wp_register_script( 'ajax-load-more-paging', plugins_url( '/core/js/alm-paging'. $suffix .'.js', __FILE__ ), array('ajax-load-more'), ALM_PAGING_VERSION, true );


   		// Enqueue CSS
	   	$options = get_option( 'alm_settings' );

   		if(!alm_do_inline_css('_alm_inline_css') && !alm_css_disabled('_alm_paging_disable_css')){ // Not inline or disabled
         	$file = ALM_PAGING_URL.'/core/css/ajax-load-more-paging'. $suffix .'.css';
         	if(class_exists('ALM_ENQUEUE')){
            	ALM_ENQUEUE::alm_enqueue_css('ajax-load-more-paging', $file);
         	}
   		}
   	}




   	/*
   	*  alm_paging_admin_enqueue_scripts
   	*  Enqueue our paging scripts in the admin
   	*
   	*  @since 1.0
   	*/

   	function alm_paging_admin_enqueue_scripts(){
			wp_enqueue_style( 'alm-paging', ALM_PAGING_URL. '/core/css/ajax-load-more-paging.css');
   	}



   	/*
   	*  alm_paging_installed
   	*  an empty function to determine if paging is true.
   	*
   	*  @since 1.0
   	*/

   	function alm_paging_installed(){
   	   //Empty return
   	}



   	/*
   	*  alm_paging_settings
   	*  Create the Paging settings panel.
   	*
   	*  @since 1.2
   	*/

   	function alm_paging_settings(){
      	register_setting(
      		'alm_paging_license',
      		'alm_paging_license_key',
      		'alm_paging_sanitize_license'
      	);
   	   add_settings_section(
	   		'alm_paging_settings',
	   		'Paging Settings',
	   		'alm_paging_settings_callback',
	   		'ajax-load-more'
	   	);
	   	add_settings_field(  // Disbale CSS
				'_alm_paging_disable_css',
				__('Disable Paging CSS', 'ajax-load-more-paging' ),
				'alm_paging_disable_css_callback',
				'ajax-load-more',
				'alm_paging_settings'
			);
	   	add_settings_field(
	   		'_alm_paging_color',
	   		__('Paging Color', 'ajax-load-more-paging' ),
	   		'_alm_paging_color_callback',
	   		'ajax-load-more',
	   		'alm_paging_settings'
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

	function alm_paging_settings_callback() {
	   $html = '<p>' . __('Customize your installation of the <a href="http://connekthq.com/plugins/ajax-load-more/paging/">Paging</a> add-on.', 'ajax-load-more-paging') . '</p>';

	   echo $html;
	}


	/*
	*  alm_paging_disable_css_callback
	*  Diabale Paging CSS.
	*
	*  @since 1.0
	*/

	function alm_paging_disable_css_callback(){
		$options = get_option( 'alm_settings' );
		if(!isset($options['_alm_paging_disable_css']))
		   $options['_alm_paging_disable_css'] = '0';

		$html = '<input type="hidden" name="alm_settings[_alm_paging_disable_css]" value="0" />';
		$html .= '<input type="checkbox" id="alm_paging_disable_css_input" name="alm_settings[_alm_paging_disable_css]" value="1"'. (($options['_alm_paging_disable_css']) ? ' checked="checked"' : '') .' />';
		$html .= '<label for="alm_paging_disable_css_input">'.__('I want to use my own CSS styles.', 'ajax-load-more-paging').'<br/><span style="display:block;"><i class="fa fa-file-text-o"></i> &nbsp;<a href="'.ALM_PAGING_URL.'/core/css/ajax-load-more-paging.css" target="blank">'.__('View Paging CSS', 'ajax-load-more-paging').'</a></span></label>';

		echo $html;
	}




	/*
	*  _alm_paging_color_callback
	*  Get the color of the paging element
	*
	*  @since 1.0
	*/

	function _alm_paging_color_callback() {

	   $options = get_option( 'alm_settings' );

		if(!isset($options['_alm_paging_color']))
		   $options['_alm_paging_color'] = '0';

	   $color = $options['_alm_paging_color'];

		 $selected0 = '';
		 if($color == 'default') $selected0 = 'selected="selected"';

		 $selected1 = '';
		 if($color == 'blue') $selected1 = 'selected="selected"';

		 $selected2 = '';
		 if($color == 'green') $selected2 = 'selected="selected"';

		 $selected3 = '';
		 if($color == 'red') $selected3 = 'selected="selected"';

		 $selected4 = '';
		 if($color == 'purple') $selected4 = 'selected="selected"';

		 $selected5 = '';
		 if($color == 'grey') $selected5 = 'selected="selected"';

		 $selected6 = '';
		 if($color == 'white') $selected6 = 'selected="selected"';

	    $html =  '<label for="alm_settings_paging_color">'.__('Choose your paging navigation color', 'ajax-load-more-paging').'.</label><br/>';
	    $html .= '<select id="alm_settings_paging_color" name="alm_settings[_alm_paging_color]">';
	    $html .= '<option value="default" ' . $selected0 .'>Default</option>';
	    $html .= '<option value="blue" ' . $selected1 .'>Blue</option>';
	    $html .= '<option value="green" ' . $selected2 .'>Green</option>';
	    $html .= '<option value="red" ' . $selected3 .'>Red</option>';
	    $html .= '<option value="purple" ' . $selected4 .'>Purple</option>';
	    $html .= '<option value="grey" ' . $selected5 .'>Grey</option>';
	    $html .= '<option value="white" ' . $selected6 .'>White</option>';
	    $html .= '</select>';

	    $html .= '<div class="clear"></div>';
	    $html .= '<div class="ajax-load-more-wrap pages paging-'.$color.'"><span class="pages">'.__('Preview', 'ajax-load-more-paging') .'</span>';
	    $html .= '<ul class="alm-paging" style="opacity: 1;"><li class="active"><a href="javascript:void(0);"><span>1</span></a></li><li><a href="javascript:void(0);"><span>2</span></a></li><li><a href="javascript:void(0);"><span>3</span></a></li><li><a href="javascript:void(0);"><span>4</span></a></li><li><a href="javascript:void(0);"><span>5</span></a></li></ul>';
	    $html .= '</div>';
	    echo $html;
	    ?>

	<script>
    	//Button preview
    	var colorArray = "paging-default paging-grey paging-purple paging-green paging-red paging-blue paging-white";
    	jQuery("select#alm_settings_paging_color").change(function() {
    		var color = jQuery(this).val();
			jQuery('.ajax-load-more-wrap.pages').removeClass(colorArray);
			jQuery('.ajax-load-more-wrap.pages').addClass('paging-'+color);
		});
		jQuery("select#alm_settings_paging_color").click(function(e){
			e.preventDefault();
		});

		// Check if Disable CSS  === true
		if(jQuery('input#alm_paging_disable_css_input').is(":checked")){
	      jQuery('select#alm_settings_paging_color').parent().parent().hide(); // Hide button color
    	}
    	jQuery('input#alm_paging_disable_css_input').change(function() {
    		var el = jQuery(this);
	      if(el.is(":checked")) {
	      	el.parent().parent('tr').next('tr').hide(); // Hide paging color
	      }else{
	      	el.parent().parent('tr').next('tr').show(); // show paging color
	      }
	   });

    </script>

	<?php
	}



	/*
   *  alm_paging_sanitize_license
   *  Sanitize our license activation
   *
   *  @since 1.0.0
   */

   function alm_paging_sanitize_license( $new ) {
   	$old = get_option( 'alm_paging_license_key' );
   	if( $old && $old != $new ) {
   		delete_option( 'alm_paging_license_status' ); // new license has been entered, so must reactivate
   	}
   	return $new;
   }




   /*
   *  ALMPAGING
   *  The main function responsible for returning Ajax Load More SEO.
   *
   *  @since 1.0
   */

   function ALMPAGING(){
   	global $ALMPAGING;

   	if( !isset($ALMPAGING) )
   	{
   		$ALMPAGING = new ALMPAGING();
   	}

   	return $ALMPAGING;
   }


   // initialize
   ALMPAGING();

endif; // class_exists check



/* Software Licensing */
function alm_paging_plugin_updater() {
	if(!has_action('alm_pro_installed') && class_exists('EDD_SL_Plugin_Updater')){ // Don't check for updates if Pro is activated
		$license_key = trim( get_option( 'alm_paging_license_key' ) ); // retrieve our license key from the DB
		$edd_updater = new EDD_SL_Plugin_Updater( ALM_STORE_URL, __FILE__, array(
				'version' 	=> ALM_PAGING_VERSION,
				'license' 	=> $license_key,
				'item_id'   => ALM_PAGING_ITEM_NAME,
				'author' 	=> 'Darren Cooney'
			)
		);
	}
}
add_action( 'admin_init', 'alm_paging_plugin_updater', 0 );
/* End Software Licensing */
