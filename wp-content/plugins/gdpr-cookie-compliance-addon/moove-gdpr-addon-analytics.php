<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; } // Exit if accessed directly

/**
 * Moove_GDPR_Addon_Analytics File Doc Comment
 *
 * @category Moove_GDPR_Addon_Analytics
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

/**
 * Moove_GDPR_Addon_Analytics Class Doc Comment
 *
 * @category Class
 * @package  Moove_GDPR_Addon_Analytics
 * @author   Moove Agency
 */
class Moove_GDPR_Addon_Analytics {

	/**
	 * Construct
	 */
	function __construct() {
		// Register Custom Post Type.
	}

	/**
	 * AJAX Analytics for GDPR Cookie Compliance, following events are defined :

		1. AJAX script loading
	 * script_inject, (obj) cookies

		2. Accepting all cookies
	 * accept_all, (str) ''

		3. Show infobar
	 * show_infobar, (str) ''

		4. Opened modal from link
	 * opened_modal_from_link, (str) ''

		5. Clicked to tab navigation
	 * clicked_to_tab, (str) tab_id
	 */
	public static function moove_gdpr_premium_save_analytics() {
		if ( isset( $_POST['gdpr_uvid'] ) ) :
			$_analytics = new Moove_GDPR_Addon_Analytics();
			wp_verify_nonce( 'ga_nonce', 'gdpr_addon_nonce' );
			$event            = isset( $_POST['event'] ) ? sanitize_text_field( wp_unslash( $_POST['event'] ) ) : false;
			$gdpr_uvid        = sanitize_text_field( wp_unslash( $_POST['gdpr_uvid'] ) );
			$extras           = isset( $_POST['extras'] ) && is_array( $_POST['extras'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['extras'] ) ) : false;
			$sanitized_extras = array();
			$analytics_id     = $_analytics->get_analytics_by_uvid( $gdpr_uvid );

			if ( $analytics_id && intval( $analytics_id ) ) :
				$user_analytics = intval( $analytics_id );
			else :
				$analytics_title = 'GDPR Analytics - ' . $gdpr_uvid;
				// Create analytics object.
				$analytics_record = array(
					'post_title'  => $analytics_title,
					'post_status' => 'publish',
					'post_type'   => 'gdpr_analytics',
				);

				// Insert the post into the database.
				$user_analytics = wp_insert_post( $analytics_record );
				update_post_meta( $user_analytics, 'gdpr_uvid', $gdpr_uvid, false );
			endif;

			if ( $extras && is_array( $extras ) && 'script_injected' === $event ) :
				update_post_meta( $user_analytics, 'script_injected', wp_json_encode( $extras ), false );
			endif;

			if ( $extras && is_array( $extras ) && 'script_inject' === $event ) :
				foreach ( $extras as $key => $value ) :
					$key   = sanitize_text_field( $key );
					$value = intval( $value );
					if ( $key && $value ) :
						$sanitized_extras[ $key ] = $value;
					endif;
				endforeach;

				$value = isset( $extras['strict'] ) ? intval( $extras['strict'] ) : 0;
				update_post_meta( $user_analytics, 'cookies_strict', $value, false );

				$value = isset( $extras['advanced'] ) ? intval( $extras['advanced'] ) : 0;
				update_post_meta( $user_analytics, 'cookies_advanced', $value, false );

				$value = isset( $extras['thirdparty'] ) ? intval( $extras['thirdparty'] ) : 0;
				update_post_meta( $user_analytics, 'cookies_thirdparty', $value, false );

			elseif ( 'clicked_to_tab' === $event ) :
				$tab_name                               = sanitize_text_field( $extras );
				$filtered_tab_name                      = str_replace( '#', '', $tab_name );
				$existing_content                       = get_post_meta( $user_analytics, 'gdpr_' . $event, true );
				$existing_content                       = json_decode( $existing_content, true ) ? json_decode( $existing_content, true ) : array();
				$existing_content[ $filtered_tab_name ] = $tab_name;
				$sanitized_extras                       = $existing_content;
				update_post_meta( $user_analytics, 'clicked_to_tab_' . $filtered_tab_name, '1', false );
			else :
				$sanitized_extras = sanitize_text_field( $extras );
			endif;

			do_action( 'gdpr_cc_addon_analytics_tracking', $user_analytics, $event, $extras, $sanitized_extras );

			update_post_meta( $user_analytics, 'gdpr_' . $event, wp_json_encode( $sanitized_extras, true ), false );

			if ( $event ) :

				$return = array(
					'success' => true,
					'data'    => array(
						'event'        => $event,
						'extras'       => $sanitized_extras,
						'analytics_id' => $user_analytics,
						'debug'        => '',
					),
					'message' => 'Success',
				);
			else :
				$return = array(
					'success' => false,
					'data'    => array(),
					'message' => 'No event found!',
				);
			endif;

		else :
			$return = array(
				'success' => false,
				'data'    => array(),
				'message' => 'GDPR Analytics ID not found!',
			);
		endif;

		echo wp_json_encode( $return );
		die();
	}

	/**
	 * Get user analytics by user tracking ID
	 *
	 * @param string $uvid User tracking ID.
	 */
	public static function get_analytics_by_uvid( $uvid = false ) {
		if ( $uvid ) :
			$args      = array(
				'post_type'      => 'gdpr_analytics',
				'posts_per_page' => 1,
				'post_status'    => 'publish',
				'meta_key'       => 'gdpr_uvid',
				'meta_value'     => $uvid,
			);
			$analytics = new WP_Query( $args );
			if ( $analytics->have_posts() ) :
				while ( $analytics->have_posts() ) :
					$analytics->the_post();
					return get_the_ID();
				endwhile;
				wp_reset_postdata();
			endif;
		endif;
		return false;
	}

	/**
	 * Registering Analytics Custom Post Type
	 */
	public static function gdpr_analytics_cpt() {

		$labels = array(
			'name'                  => _x( 'GDPR Analytics', 'Post Type General Name', 'gdpr-cookie-compliance-addon' ),
			'singular_name'         => _x( 'GDPR Analytics', 'Post Type Singular Name', 'gdpr-cookie-compliance-addon' ),
			'menu_name'             => __( 'GDPR Analytics', 'gdpr-cookie-compliance-addon' ),
			'name_admin_bar'        => __( 'Post Type', 'gdpr-cookie-compliance-addon' ),
			'archives'              => __( 'Item Archives', 'gdpr-cookie-compliance-addon' ),
			'attributes'            => __( 'Item Attributes', 'gdpr-cookie-compliance-addon' ),
			'parent_item_colon'     => __( 'Parent Item:', 'gdpr-cookie-compliance-addon' ),
			'all_items'             => __( 'All Items', 'gdpr-cookie-compliance-addon' ),
			'add_new_item'          => __( 'Add New Item', 'gdpr-cookie-compliance-addon' ),
			'add_new'               => __( 'Add New', 'gdpr-cookie-compliance-addon' ),
			'new_item'              => __( 'New Item', 'gdpr-cookie-compliance-addon' ),
			'edit_item'             => __( 'Edit Item', 'gdpr-cookie-compliance-addon' ),
			'update_item'           => __( 'Update Item', 'gdpr-cookie-compliance-addon' ),
			'view_item'             => __( 'View Item', 'gdpr-cookie-compliance-addon' ),
			'view_items'            => __( 'View Items', 'gdpr-cookie-compliance-addon' ),
			'search_items'          => __( 'Search Item', 'gdpr-cookie-compliance-addon' ),
			'not_found'             => __( 'Not found', 'gdpr-cookie-compliance-addon' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'gdpr-cookie-compliance-addon' ),
			'featured_image'        => __( 'Featured Image', 'gdpr-cookie-compliance-addon' ),
			'set_featured_image'    => __( 'Set featured image', 'gdpr-cookie-compliance-addon' ),
			'remove_featured_image' => __( 'Remove featured image', 'gdpr-cookie-compliance-addon' ),
			'use_featured_image'    => __( 'Use as featured image', 'gdpr-cookie-compliance-addon' ),
			'insert_into_item'      => __( 'Insert into item', 'gdpr-cookie-compliance-addon' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'gdpr-cookie-compliance-addon' ),
			'items_list'            => __( 'Items list', 'gdpr-cookie-compliance-addon' ),
			'items_list_navigation' => __( 'Items list navigation', 'gdpr-cookie-compliance-addon' ),
			'filter_items_list'     => __( 'Filter items list', 'gdpr-cookie-compliance-addon' ),
		);
		$args   = array(
			'label'               => __( 'GDPR Analytics', 'gdpr-cookie-compliance-addon' ),
			'description'         => __( 'GDPR Cookie Compliance Analytics', 'gdpr-cookie-compliance-addon' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'custom-fields' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => false,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
			'capability_type'     => 'page',
		);
		register_post_type( 'gdpr_analytics', $args );

	}

}

$gdpr_analyitcs = new Moove_GDPR_Addon_Analytics();
