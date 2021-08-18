<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; } // Exit if accessed directly

/**
 * Moove_GDPR_Addon_GeoIP File Doc Comment
 *
 * @category Moove_GDPR_Addon_GeoIP
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

/**
 * Moove_GDPR_Addon_GeoIP Class Doc Comment
 *
 * @category Class
 * @package  Moove_GDPR_Addon_GeoIP
 * @author   Moove Agency
 */
class Moove_GDPR_Addon_GeoIP {

	/**
	 * Global variable used as primary key
	 *
	 * @var primary_key Primary key.
	 */
	public static $primary_key = 'id';

	/**
	 * Construct
	 */
	public function __construct() {

		global $wpdb;
		/**
		 * Creating database structure on the first time
		 */
		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->base_prefix}gdpr_cc_geoip'" ) != $wpdb->base_prefix . 'gdpr_cc_geoip' ) :
			$wpdb->query(
				"CREATE TABLE {$wpdb->base_prefix}gdpr_cc_geoip(
          id INTEGER NOT NULL auto_increment,
          ip_address VARCHAR(255) NOT NULL DEFAULT 1,
          option_value LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          PRIMARY KEY (id)
        );"
			);
			$this->cleanup_geoip_location_data();
		endif;

		add_action( 'moove_gdpr_addon_anonimise_ip', array( &$this, 'moove_gdpr_addon_anonimise_ip' ) );
	}

	/**
	 * GDPR Table name
	 */
	private static function _table() {
		global $wpdb;
		$tablename = 'gdpr_cc_geoip';
		return $wpdb->base_prefix . $tablename;
	}

	/**
	 * Cleaning up the options from previously stored geo data
	 */
	private static function cleanup_geoip_location_data() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'options';
		return $wpdb->get_results( "DELETE FROM `$table_name`  WHERE `option_name` LIKE 'gdpr_addon_geo_%'" );
	}

	/**
	 * Insert LOG entry to database
	 * @param array $data Data array.
	 */
	public function create_log_entry( $data ) {
		global $wpdb;
		try {
			$this->cleanup_geoip_location_data();
		} catch ( Exception $e ) {
			
		}
		return $wpdb->insert( self::_table(), $data );
	}

	/**
	 * Returns SQL statement to get all logs
	 * @param date $start_date Start date. 
	 * @param date $end_date End date.
	 */
	private static function _fetch_sql_all( $ip_address ) {
		global $wpdb;
		$table_name = self::_table();
		$results  	= $wpdb->prepare( "SELECT `option_value` FROM `$table_name` WHERE `ip_address` = %s LIMIT 1", $ip_address );
		return $results;
	}

	/**
	 * Returns LOG entry from database
	 * @param date $start_date Start date. 
	 * @param date $end_date End date.
	 */
	public function get_log_entry( $ip_address = false ) {
		global $wpdb;
		$data = false;
		if( $ip_address ) :
			$data = $wpdb->get_results( self::_fetch_sql_all( $ip_address ), ARRAY_A );
			$data = isset( $data[0] ) && isset( $data[0]['option_value'] ) ? $data[0]['option_value'] : false;
		endif;	
		return $data;
	}

	/**
	 * Convert IP Address to be antonym
	 *
	 * @param string $ip_address IP address.
	 */
	public function moove_gdpr_addon_anonimise_ip( $ip_address ) {
		// LIVE
		return preg_replace( [ '/\.\d*$/', '/[\da-f]*:[\da-f]*$/' ], [ '.0', '0000:0000' ], $ip_address );
	}

	/**
	 * Checking visitor IP
	 */
	public function get_visitor_ip( $filter = true ) {
		// Get real visitor IP behind CloudFlare network.
		if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) :
			$remote = isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) : false;
			$client = isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) : false;
		else :
			$remote = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : false;
			$client = isset( $_SERVER['HTTP_CLIENT_IP'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) ) : false;
		endif;

		$forward = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) : false;

		if ( filter_var( $client, FILTER_VALIDATE_IP ) ) {
			$ip = $client;
		} elseif ( filter_var( $forward, FILTER_VALIDATE_IP ) ) {
			$ip = $forward;
		} else {
			$ip = $remote;
		}
		if ( $filter ) :
			$ip = apply_filters( 'moove_gdpr_addon_anonimise_ip', $ip );
		endif;
		return $ip;
	}

	/**
	 * Checking IP address by using GeoPlugin service
	 *
	 * @param string $ip IP address.
	 */
	public function get_details_by_ip( $ip = false ) {
		if ( $ip ) :
			try {
				$data = @file_get_contents( 'http://www.geoplugin.net/php.gp?ip=' . $ip );
				$loc = maybe_unserialize( $data );
				if ( $loc && is_array( $loc ) && isset( $loc['geoplugin_inEU'] ) ) :
					return array(
						'success' => true,
						'data'    => $loc,
					);

				else :

					return array(
						'success' => false,
						'data'    => array(),
					);
				endif;
			} catch ( Exception $e ) {

				return array(
					'success' => false,
					'data'    => array(),
				);
			}
		endif;
		return array(
			'success' => false,
			'data'    => array(),
		);
	}

}
