<?php

/*
Plugin Name: Ajax Load More: Call to Actions
Plugin URI: http://connekthq.com/plugins/ajax-load-more/call-to-actions/
Description: Ajax Load More extension for displaying advertisements and call to actions.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: http://connekthq.com
Version: 1.0.4.1
License: GPL
Copyright: Darren Cooney & Connekt Media
*/

// @codingStandardsIgnoreStart

define( 'ALM_CTA_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_CTA_URL', plugins_url( '', __FILE__ ) );
define( 'ALM_CTA_VERSION', '1.0.4.1' );
define( 'ALM_CTA_RELEASE', 'February 16, 2021' );

/**
 * Install the add-on.
 *
 * @author ConnektMedia
 * @since 1.0
 */
function alm_cta_install() {
	if ( ! is_plugin_active( 'ajax-load-more/ajax-load-more.php' ) ) { // If Ajax Load More is activated.
		wp_die( 'You must install and activate <a href="https://wordpress.org/plugins/ajax-load-more/">Ajax Load More</a> before installing the Ajax Load More Call to Actions add-on.' );
	}
}
register_activation_hook( __FILE__, 'alm_cta_install' );

if ( ! class_exists( 'ALMCTA' ) ) :

	/**
	 * ALM Call to Action Class.
	 *
	 * @since 1.0
	 */
	class ALMCTA {

		/**
		 * Construct class.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'alm_cta_installed', array( &$this, 'alm_cta_installed' ) );
			add_filter( 'alm_cta_shortcode', array( &$this, 'alm_cta_shortcode' ), 10, 4 );
			add_action( 'alm_cta_inc', array( &$this, 'alm_cta_inc' ), 10, 8 );
			add_filter( 'alm_cta_pos_array', array( &$this, 'alm_cta_pos_array' ), 10, 6 );
		}

		/**
		 * Build array of CTA position values.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 * @param string $seo_start_page  Start page number.
		 * @param string $page            Current page number.
		 * @param string $posts_per_page  Posts per Page.
		 * @param string $alm_found_posts Total posts variable.
		 * @param string $cta_val         The value for the CTA.
		 * @param string $paging          True/false.
		 * @return array
		 */
		public function alm_cta_pos_array( $seo_start_page, $page, $posts_per_page, $alm_found_posts, $cta_val, $paging ) {

			if ( 'true' === $paging ) {
				$cta_array[] = $cta_val;

			} else {
				if ( $seo_start_page > 1 && $page < $seo_start_page ) {
					// If is SEO first load.
					$posts_per_page = floor( $posts_per_page / $seo_start_page ); // Get orginal $posts_per_page value.
					$pages          = $alm_found_posts / $posts_per_page;
					for ( $i = 0; $i < $pages; $i++ ) {
						$cta_array[] = $cta_val + ( $i * $posts_per_page );
					}
				} else {
					$cta_array[] = $cta_val;
				}
			}
			return $cta_array;
		}

		/**
		 * Build shortcode params and send back to core ALM.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 * @param string $cta                Is CTA true/false.
		 * @param string $cta_position       Position of the CTA.
		 * @param string $cta_repeater       Repeater Template.
		 * @param string $cta_theme_repeater Theme Repeater.
		 * @return string
		 */
		public function alm_cta_shortcode( $cta, $cta_position, $cta_repeater, $cta_theme_repeater ) {
			$shortcode  = ' data-cta="' . $cta . '"';
			$shortcode .= ' data-cta-position="' . $cta_position . '"';
			$shortcode .= ' data-cta-repeater="' . $cta_repeater . '"';
			$shortcode .= ' data-cta-theme-repeater="' . $cta_theme_repeater . '"';
			return $shortcode;
		}

		/**
		 * Get call to action.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 * @param string  $cta_repeater       Repeater Template.
		 * @param string  $cta_theme_repeater Theme Repeater.
		 * @param string  $alm_found_posts    Total posts.
		 * @param string  $alm_page           Current page variable.
		 * @param string  $alm_item           Item in loop vairable.
		 * @param string  $alm_current        The current element variable.
		 * @param boolean $preloaded          Is this Preloaded?.
		 * @param array   $args               Query args.
		 * @return HTMLElement/string
		 */
		public function alm_cta_inc( $cta_repeater, $cta_theme_repeater, $alm_found_posts, $alm_page, $alm_item, $alm_current, $preloaded = false, $args = [] ) {

			if ( $preloaded ) {
				ob_start();
			}
			if ( 'null' !== $cta_theme_repeater && has_filter( 'alm_get_theme_repeater' ) ) {
				// Theme Repeater.
				$type = alm_get_repeater_type( $cta_theme_repeater );
				do_action( 'alm_get_theme_repeater', $cta_theme_repeater, $alm_found_posts, $alm_page, $alm_item, $alm_current, $args );

			} else {
				// Standard Repeaters.
				$type = alm_get_repeater_type( $cta_repeater );
				$file = alm_get_current_repeater( $cta_repeater, $type );
				include $file;

			}
			if ( $preloaded ) {
				$return = ob_get_clean();
				return $return;
			}
		}

		/**
		 * An empty function to determine if preload is true.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 */
		public function alm_cta_installed() {
			// Empty.
		}

		/**
		 * Create the settings panel.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 */
		public function alm_cta_settings() {
			register_setting(
				'alm_cta_license',
				'alm_cta_license_key',
				'alm_cta_sanitize_license'
			);
		}

	}

	/**
	 * Sanitize our license activation
	 *
	 * @author ConnektMedia
	 * @since 1.0
	 * @param string $new The license key.
	 * @return string
	 */
	function alm_cta_sanitize_license( $new ) {
		$old = get_option( 'alm_cta_license_key' );
		if ( $old && $new !== $old ) {
			// New license has been entered, must reactivate.
			delete_option( 'alm_cta_license_status' );
		}
		return $new;
	}

	/**
	 * The main function responsible for returning Ajax Load More ALternating Templates.
	 *
	 * @author ConnektMedia
	 * @since 1.0
	 * @return class
	 */
	function alm_cta() {
		global $alm_cta;
		if ( ! isset( $alm_cta ) ) {
			$alm_cta = new ALMCTA();
		}
		return $alm_cta;
	}

	alm_cta();

endif;


/**
 * Software Licensing.
 *
 * @author ConnektMedia
 * @since 1.0
 */
function alm_cta_plugin_updater() {
	if ( ! has_action( 'alm_pro_installed' ) && class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		$license_key = trim( get_option( 'alm_cta_license_key' ) );
		$edd_updater = new EDD_SL_Plugin_Updater(
			ALM_STORE_URL,
			__FILE__,
			array(
				'version' => ALM_CTA_VERSION,
				'license' => $license_key,
				'item_id' => ALM_CTA_ITEM_NAME,
				'author'  => 'Darren Cooney'
			)
		);
	}
}
add_action( 'admin_init', 'alm_cta_plugin_updater', 0 );
