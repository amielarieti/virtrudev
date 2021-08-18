<?php
/*
Plugin Name: Ajax Load More: Theme Repeaters
Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/theme-repeaters/
Description: Ajax Load More extension allowing repeater template selection from the current theme directory.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: http://connekthq.com
Version: 1.1.3.2
License: GPL
Copyright: Darren Cooney & Connekt Media
*/

// @codingStandardsIgnoreStart

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ALM_THEME_REPEATERS_VERSION', '1.1.3.2' );
define( 'ALM_THEME_REPEATERS_RELEASE', 'February 16, 2021' );

/**
 * Activation hook.
 *
 * @since 1.0
 */
function alm_theme_repeaters_install() {
	// if Ajax Load More is activated.
	if ( ! is_plugin_active( 'ajax-load-more/ajax-load-more.php' ) ) {
		wp_die( 'You must install and activate <a href="https://wordpress.org/plugins/ajax-load-more/">Ajax Load More</a> before installing the Theme Repeaters Add-on.' );
	}
}
register_activation_hook( __FILE__, 'alm_theme_repeaters_install' );


if ( ! class_exists( 'ALMTHEMEREPEATERS' ) ) :

	/**
	 * Theme Repeaters Class.
	 */
	class ALMTHEMEREPEATERS {

		/**
		 * Construct function.
		 * 
		 * @author ConnektMedia
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'alm_theme_repeaters_installed', array( &$this, 'alm_theme_repeaters_installed' ) );
			add_action( 'alm_theme_repeaters_settings', array( &$this, 'alm_theme_repeaters_settings' ) );
			add_filter( 'alm_get_theme_repeater', array( &$this, 'alm_get_theme_repeater' ), 10, 6 );
			add_filter( 'alm_get_acf_gallery_theme_repeater', array( &$this, 'alm_get_acf_gallery_theme_repeater' ), 10, 6 );
			add_filter( 'alm_get_term_query_theme_repeater', array( &$this, 'alm_get_term_query_theme_repeater' ), 10, 6 );
			add_filter( 'alm_get_users_theme_repeater', array( &$this, 'alm_get_users_theme_repeater' ), 10, 6 );
			add_filter( 'alm_get_rest_theme_repeater', array( &$this, 'alm_get_rest_theme_repeater' ), 10, 1 );
			add_action( 'alm_theme_repeaters_selection', array( &$this, 'alm_theme_repeaters_selection' ) );
			load_plugin_textdomain( 'ajax-load-more-theme-repeaters', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * An empty function to determine if Local Templates is activated.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 */
		public function alm_theme_repeaters_installed() {
			// Empty hook.
		}

		/**
		 * Create the Local Templates settings panel.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 */
		public function alm_theme_repeaters_settings() {
			register_setting(
				'alm_theme_repeaters_license',
				'alm_theme_repeaters_license_key',
				'alm_theme_repeaters_sanitize_license'
			);
			add_settings_section(
				'alm_theme_repeaters_settings',
				'Theme Repeater Settings',
				'alm_theme_repeaters_callback',
				'ajax-load-more'
			);
			add_settings_field(  // Theme Repeater directory.
				'_alm_theme_repeaters_dir',
				__( 'Directory Selection', 'ajax-load-more-theme-repeaters' ),
				'alm_theme_repeaters_dir_callback',
				'ajax-load-more',
				'alm_theme_repeaters_settings'
			);
		}

		/**
		 * Security prevention. Don't allow users to back out and move directories.
		 *
		 * @author ConnektMedia
		 * @since 1.1
		 * @param string $filepath The full path the file.
		 * @return string
		 */
		public function alm_verfify_filepath( $filepath ) {
			$filepath = str_replace( '../', '', $filepath );
			$filepath = str_replace( '..%2f', '', $filepath );
			return $filepath;
		}

		/**
		 * Get the current Theme Repeater dir.
		 *
		 * @author ConnektMedia
		 * @since 1.1
		 * @return string
		 */	
		public function alm_get_theme_repeaters_dir() {
			$options = get_option( 'alm_settings' );
			if ( ! isset( $options['_alm_theme_repeaters_dir'] ) ) {
				$options['_alm_theme_repeaters_dir'] = 'alm_templates';
			}
			return $options['_alm_theme_repeaters_dir'];
		}

		/**
		 * Get the complete file path to the Theme Repeater.
		 *
		 * @author ConnektMedia
		 * @return $file
		 * @since 1.1
		 * @param string $theme_repeater The Theme Repeater name.
		 */
		public function alm_get_theme_repeater_file( $theme_repeater ) {

			// Check for Child Theme.
			if ( is_child_theme() ) {
				$file = get_stylesheet_directory() . $theme_repeater;
			} else {
				$file = get_template_directory() . $theme_repeater;
			}

			// Confirm file exists and run secondary security check.
			if ( ! file_exists( $file ) || false !== strpos( $file, './' ) ) {
				$file = alm_get_default_repeater();
			}

			return $file;
		}

		/**
		 * Get the theme repeater template.
		 *
		 * @author ConnektMedia
		 * @param string $theme_repeater The Theme Repeater.
		 * @param string $alm_found_posts ALM Variable.
		 * @param string $alm_page ALM Variable.
		 * @param string $alm_item ALM Variable.
		 * @param string $alm_current ALM Variable.
		 * @param array  $args Array of query and setup variables.
		 * @since 1.0
		 */
		public function alm_get_theme_repeater( $theme_repeater = 'null', $alm_found_posts = 0, $alm_page = 0, $alm_item = 0, $alm_current = 0, $args = array() ) {

			if ( 'null' !== $theme_repeater ) {

				// Get template directory.
				$dir = $this->alm_get_theme_repeaters_dir();

				// Security prevention.
				$theme_repeater = '/' . $dir . '/' . $this->alm_verfify_filepath( $theme_repeater );

				// Get the complete file path.
				$file = $this->alm_get_theme_repeater_file( $theme_repeater );
				include $file;

			} else {
				include alm_get_default_repeater(); // Include default repeater template.
			}
		}

		/**
		 * Get the theme repeater template for ACF Galleries.
		 *
		 * @author ConnektMedia
		 * @since 1.0.8
		 * @param string $theme_repeater The Theme Repeater.
		 * @param string $alm_found_posts ALM Variable.
		 * @param string $alm_page ALM Variable.
		 * @param string $alm_item ALM Variable.
		 * @param string $alm_current ALM Variable.
		 * @param string $image The image path.
		 */
		public function alm_get_acf_gallery_theme_repeater( $theme_repeater = 'null', $alm_found_posts = 0, $alm_page = 0, $alm_item = 0, $alm_current = 0, $image = array() ) {
			if ( 'null' !== $theme_repeater ) {

				// Get template directory.
				$dir = $this->alm_get_theme_repeaters_dir();

				// Security prevention.
				$theme_repeater = '/' . $dir . '/' . $this->alm_verfify_filepath( $theme_repeater );

				// Get the complete file path.
				$file = $this->alm_get_theme_repeater_file( $theme_repeater );
				include $file;

			} else {
				include alm_get_default_repeater(); // Include default repeater template.
			}
		}

		/**
		 * Get the theme repeater template for Term Query.
		 *
		 * @author ConnektMedia
		 * @since 1.2
		 * @param string $theme_repeater The Theme Repeater.
		 * @param string $alm_found_posts ALM Variable.
		 * @param string $alm_page ALM Variable.
		 * @param string $alm_item ALM Variable.
		 * @param string $alm_current ALM Variable.
		 * @param string $term The term slug.
		 */
		public function alm_get_term_query_theme_repeater( $theme_repeater = 'null', $alm_found_posts = 0, $alm_page = 0, $alm_item = 0, $alm_current = 0, $term = array() ) {
			if ( 'null' !== $theme_repeater ) {

				// Get template directory.
				$dir = $this->alm_get_theme_repeaters_dir();

				// Security prevention.
				$theme_repeater = '/' . $dir . '/' . $this->alm_verfify_filepath( $theme_repeater );

				// Get the complete file path.
				$file = $this->alm_get_theme_repeater_file( $theme_repeater );				
				include $file;

			} else {
				include alm_get_default_repeater(); // Include default repeater template.
			}
		}

		/**
		 * Get the theme repeater template for Users add-on.
		 *
		 * @author ConnektMedia
		 * @since 1.1
		 * @param string $theme_repeater The Theme Repeater.
		 * @param string $alm_found_posts ALM Variable.
		 * @param string $alm_page ALM Variable.
		 * @param string $alm_item ALM Variable.
		 * @param string $alm_current ALM Variable.
		 * @param string $user The user slug.
		 */
			public function alm_get_users_theme_repeater( $theme_repeater = 'null', $alm_found_posts = 0, $alm_page = 0, $alm_item = 0, $alm_current = 0, $user = array() ) {
				if ( 'null' !== $theme_repeater ) {

					// Get template directory.
					$dir = $this->alm_get_theme_repeaters_dir();

					// Security prevention.
					$theme_repeater = '/' . $dir . '/' . $this->alm_verfify_filepath( $theme_repeater );

					// Get the complete file path.
					$file = $this->alm_get_theme_repeater_file( $theme_repeater );
					include $file;

				} else {
					include alm_get_default_repeater(); // Include default repeater template.
				}
			}

		/**
		 * Get the theme repeater template for the REST API add-on.
		 *
		 * @author ConnektMedia
		 * @since 1.1
		 * @param string $theme_repeater The Theme Repeater.
		*/	
		public function alm_get_rest_theme_repeater( $theme_repeater ) {
			if ( 'null' !== $theme_repeater ) {
				// Get template directory.
				$dir = $this->alm_get_theme_repeaters_dir();

				// Security prevention.
				$theme_repeater = '/' . $dir . '/' . $this->alm_verfify_filepath( $theme_repeater );

				// Get the complete file path.
				$file = $this->alm_get_theme_repeater_file( $theme_repeater );
				include $file;

			} else {
				include alm_get_default_repeater(); // Include default repeater template.
			}
		}

		/**
		 * List the templates within the /alm_templates dir. within the current theme directory
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 */
		public function alm_theme_repeaters_selection() {
			$options = get_option( 'alm_settings' );
			if ( ! isset( $options['_alm_theme_repeaters_dir'] ) ) {
				$options['_alm_theme_repeaters_dir'] = 'alm_templates';
			}
			?>
			<div class="select-theme-repeater">
				<span class="or">or</span>
				<section>
					<div class="shortcode-builder--label">	      	   
						<h4><?php _e( 'Theme Repeater', 'ajax-load-more-theme-repeaters' ); ?></h4>
						<p><?php _e( 'Select a repeater template from the <span>' . $options['_alm_theme_repeaters_dir'] . '</span> (<a href="admin.php?page=ajax-load-more" target="_parent">update</a>) directory within your current theme folder.', 'ajax-load-more-theme-repeaters' ); ?></p>
						</div>
						<div class="shortcode-builder--fields">    
						<div class="inner">  	            
							<?php
							// Get template location.
							if ( is_child_theme() ) {
								$dir = get_stylesheet_directory() . '/' . $options['_alm_theme_repeaters_dir'];	      	         
							} else {     	         
								$dir = get_template_directory() . '/' . $options['_alm_theme_repeaters_dir'];
							}

							$count = 0;
							echo '<select name="theme-repeater-select" class="alm_element"><option value="" selected="selected">-- ' . __( 'Select Theme Repeater', 'ajax-load-more-theme-repeaters' ) . ' --</option>';
							foreach ( glob( $dir . '/*' ) as $file ) {
								$count++;
								$file = realpath( $file );
								$link = substr( $file, strlen( $dir ) + 1 );

								// Only display .php, .html files files.
								$file_extension = strtolower( substr( basename( $file ), strrpos( basename( $file ), '.' ) + 1 ) );
								if ( 'php' === $file_extension ) {
									echo '<option value="' . basename( $file ) . '">' . basename( $file ) . '</option>';
								}
							}
							if ($count==0) {
								echo '<option value="null">' . __( 'No Templates Found', 'ajax-load-more-theme-repeaters' ) . '</option>';
							}
							echo '</select>';
							?>
						</div>
					</div>
				</section>
			</div>
			<?php
		}
	}

	/**
	 * Theme Repeater Settings Heading.
	 *
	 * @author ConnektMedia
	 * @since 1.0
	 */	
	function alm_theme_repeaters_callback() {
		echo '<p>' . __( 'Customize your installation of the <a href="http://connekthq.com/plugins/ajax-load-more/theme-repeaters/">Theme Repeaters</a> add-on.', 'ajax-load-more-theme-repeaters' ) . '</p>';
	}

	/**
	 * Select directory for theme level repeaters.
	 *
	 * @author ConnektMedia
	 * @since 1.0
	 */	
	function alm_theme_repeaters_dir_callback(){
		$options = get_option( 'alm_settings' );
		if ( ! isset( $options['_alm_theme_repeaters_dir'] ) ) {
			$options['_alm_theme_repeaters_dir'] = '/alm_templates';
		}

		$html  = '<p>' . __('Select the directory that will hold your <strong>Theme Repeater</strong> templates - all templates <u>must</u> be stored in a top level directory within your current theme folder.', 'ajax-load-more-theme-repeaters' ) . '</p>';
		$html .= '<p class="notify">' . __(' If a directory has not been specified, Ajax Load More will attempt to load templates from <span><i class="fa fa-folder"></i> alm_templates</span>', 'ajax-load-more-theme-repeaters' ) . '</p>';

		$html .= '<div class="alm-dir-listing theme-repeaters"><ul>';      

		$theme = wp_get_theme();
		$html .= '<p class="theme-title"><i class="fa fa-folder-open"></i> ' . $theme->get( 'Name' ) . '</p>';

		if ( is_child_theme() ) {
			$dir = new DirectoryIterator( get_stylesheet_directory() );
		} else {
			$dir = new DirectoryIterator( get_template_directory() );
		}

		$dir_array = array();
		foreach ( $dir as $fileinfo ) {
			if ( $fileinfo->isDir() && ! $fileinfo->isDot() ) {
				$dir_array[] = $fileinfo->getFilename();
			}
		}

		sort( $dir_array );
		foreach ( $dir_array as $directory ) {
			$html .= '<li>';
			if ( $directory === $options['_alm_theme_repeaters_dir'] ) {
				$html .= '<input type="radio" id="dir_' . $directory . '" name="alm_settings[_alm_theme_repeaters_dir]" value="' . $directory . '" checked="checked">';
			}else{
				$html .= '<input type="radio" id="dir_' . $directory . '" name="alm_settings[_alm_theme_repeaters_dir]" value="' . $directory . '">';
			}
			$html .= '<label for="dir_' . $directory . '"><i class="fa fa-folder"></i> ' . $directory . '</label></li>';
		}
		$html .= '</ul></div>';
		echo $html;
	}

	/**
	 * Sanitize our license activation.
	 *
	 * @author ConnektMedia
	 * @since 1.0
	 */
	function alm_theme_repeaters_sanitize_license( $new ) {
		$old = get_option( 'alm_theme_repeaters_license_key' );
		if( $old && $old != $new ) {
			delete_option( 'alm_theme_repeaters_license_status' ); // New license has been entered, so must reactivate.
		}
		return $new;
	} 

	/**
	 * The main function responsible for returning Ajax Load More Local Templates.
	 *
	 * @author ConnektMedia
	 * @since 1.0
	 * @return class
	*/
	function ALMTHEMEREPEATERS() {
		global $ALMTHEMEREPEATERS;   
		if ( ! isset( $ALMTHEMEREPEATERS ) ) {
			$ALMTHEMEREPEATERS = new ALMTHEMEREPEATERS();
		}   
		return $ALMTHEMEREPEATERS;
	}

	ALMTHEMEREPEATERS(); // initialize.

endif; // class_exists check.


/**
 * Software Licensing.
 *
 * @author ConnektMedia
 * @since 1.0
 */
function alm_theme_repeaters_plugin_updater() {
	if ( ! has_action( 'alm_pro_installed' ) && class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		// Don't check for updates if Pro is activated.
		$license_key = trim( get_option( 'alm_theme_repeaters_license_key' ) ); // Retrieve our license key from the DB.
		$edd_updater = new EDD_SL_Plugin_Updater( 
			ALM_STORE_URL,
			__FILE__,
			array(
				'version' => ALM_THEME_REPEATERS_VERSION,
				'license' => $license_key,
				'item_id' => ALM_THEME_REPEATERS_ITEM_NAME,
				'author'  => 'Darren Cooney'
			)
		);
	}
}
add_action( 'admin_init', 'alm_theme_repeaters_plugin_updater', 0 );
