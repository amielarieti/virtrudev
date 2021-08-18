<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Moove_GDPR_Addon_Controller File Doc Comment
 *
 * @category Moove_GDPR_Addon_Controller
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

/**
 * Moove_GDPR_Addon_Controller Class Doc Comment
 *
 * @category Class
 * @package  Moove_Controller
 * @author   Moove Agency
 */
class Moove_GDPR_Addon_Controller {

	/**
	 * Construct
	 */
	function __construct() {		
		
	}

	function gdpr_get_post_meta( $post_id = false, $key = '' ) {
	    global $wpdb;
	    if ( $post_id && $key ) :
		    $test = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value from $wpdb->postmeta WHERE post_id = %d and meta_key = %s", $post_id, $key ) );
		    return $test;
		endif;
		return '';
	}

}