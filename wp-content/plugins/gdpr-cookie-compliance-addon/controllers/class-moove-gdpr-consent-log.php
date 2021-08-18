<?php
/**
 * Moove_GDPR_Consent_Log File Doc Comment
 *
 * @category Moove_GDPR_Consent_Log
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Moove_GDPR_Consent_Log' ) ) {
	/**
	 * Moove_GDPR_Consent_Log Class Doc Comment
	 *
	 * @category Class
	 * @package  Moove_GDPR_Consent_Log
	 * @author   Moove Agency
	 */
	class Moove_GDPR_Consent_Log {

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
			if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->base_prefix}gdpr_cc_consentlog'" ) != $wpdb->base_prefix . 'gdpr_cc_consentlog' ) :
				$wpdb->query(
					"CREATE TABLE {$wpdb->base_prefix}gdpr_cc_consentlog(
	          id INTEGER NOT NULL auto_increment,
	          ip_address VARCHAR(255) NOT NULL DEFAULT 1,
	          option_value LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	          log_date INTEGER,
	          user_id INTEGER DEFAULT 0,
	          extras LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	          PRIMARY KEY (id)
	        );"
				);
			endif;
		}

		/**
		 * GDPR Table name
		 */
		private static function _table() {
			global $wpdb;
			$tablename = 'gdpr_cc_consentlog';
			return $wpdb->base_prefix . $tablename;
		}

		/**
		 * Returns SQL statement to get all logs
		 * @param date $start_date Start date. 
		 * @param date $end_date End date.
		 */
		private static function _fetch_sql_all( $start_date, $end_date ) {
			global $wpdb;
			$table_name = self::_table();
			$result     = false;
			$results 		= "SELECT * FROM `$table_name` ORDER BY log_date DESC";
			if ( $start_date && $end_date ) :
				$start_date = strtotime( $start_date  );
				$end_date 	= strtotime( $end_date . ' +1 day' );
				$results  	= $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `log_date` >= %s AND `log_date` < %s ORDER BY log_date DESC", $start_date, $end_date );
			endif;			
			return $results;
		}


		/**
		 * Insert LOG entry to database
		 * @param array $data Data array.
		 */
		public function create_log_entry( $data ) {
			global $wpdb;		
			return $wpdb->insert( self::_table(), $data );
		}

		/**
		 * Returns LOG entry from database
		 * @param date $start_date Start date. 
		 * @param date $end_date End date.
		 */
		public function get_log_entry( $start_date = null, $end_date = null ) {
			global $wpdb;		
			$data = false;
			$data = $wpdb->get_results( self::_fetch_sql_all( $start_date, $end_date ), OBJECT_K );
		
			return $data;
		}

		/**
		 * Returns LOG entry from database
		 * @param date $start_date Start date. 
		 * @param date $end_date End date.
		 */
		public function export_log( $start_date = null, $end_date = null ) {
			global $wpdb;		
			$data = false;

			$data = $wpdb->get_results( self::_fetch_sql_all( $start_date, $end_date ), OBJECT_K );

			self::generate_csv_export( $data, $start_date, $end_date );
			return true;;
		}
		
		/**
		 * Function used to generate csv file
		 */
		private function generate_csv_export( $data, $start_date, $end_date ) {
			$array_keys = apply_filters( 'gdpr_cl_csv_header', array( 'Date/Time', 'IP Address', 'User Details' ) );

      $date_format = get_option('date_format');
			$time_format = get_option('time_format');

			/**
			 * Cookie category names 
			 */
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
			$gdpr_options         = get_option( $option_name );
			$count 								= 0;

			$strict_label = isset( $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ) : esc_html__( 'Strictly Necessary Cookies', 'gdpr-cookie-compliance' );

			$thirdparty_label = isset( $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_performance_cookies_tab_title' . $wpml_lang ] ) : esc_html__( '3rd Party Cookies', 'gdpr-cookie-compliance' );

			$advanced_label = isset( $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ) : esc_html__( 'Additional Cookies', 'gdpr-cookie-compliance' );

			$array_keys[] = $strict_label;

			$has_thirdparty = false;
			$has_advanced 	= false;
			if ( isset( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) :
				$array_keys[] 	= $thirdparty_label;
				$has_thirdparty = true;
			endif;
			if ( isset( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) :
				$array_keys[] = $advanced_label;
				$has_advanced = true;
			endif;

			$log_array = array();
      if ( $data && is_array( $data ) && ! empty( $data ) ) :
        foreach ( $data as $log_entry ) :
        	$strict 		= 'no';
					$thirdpary 	= 'no';
					$advanded 	= 'no';
					$cookies 		= isset( $log_entry->option_value ) ? json_decode( $log_entry->option_value, true ) : false;
					if ( $cookies ) :
						$strict 			= isset( $cookies['strict'] ) && intval( $cookies['strict'] ) ? 'yes' : 'no';
						if ( $has_thirdparty ) :
							$thirdparty 	= isset( $cookies['thirdparty'] ) && intval( $cookies['thirdparty'] ) ? 'yes' : 'no';
						endif;
						if ( $has_advanced ) :
							$advanced 		= isset( $cookies['advanced'] ) && intval( $cookies['advanced'] ) ? 'yes' : 'no';
						endif;
					endif;

          $csv_entry = array(
            'date'            	=>  esc_attr( isset( $log_entry->log_date ) ? date( $date_format . ' @ ' . $time_format, $log_entry->log_date ) : 'false' ),
            'ip_address'      	=>  esc_attr( isset( $log_entry->ip_address ) ? $log_entry->ip_address : 'false' ),
            'user_details'			=> 	esc_attr( isset( $log_entry->user_id ) ? apply_filters('gdpr_consent_log_user_info', $log_entry->user_id ) : 'Not logged in' ),            
          );

          $csv_entry['strict']				= 	$strict;
          if ( $has_thirdparty ) :
	          $csv_entry['thirdparty'] 	= 	$thirdparty;
	        endif;
	        if ( $has_advanced ) :
          	$csv_entry['advanced']		= 	$advanced;
          endif;

          $log_array[] = apply_filters( 'gdpr_cl_csv_row', $csv_entry, $data );
        endforeach;
      endif;

      /** Open raw memory as file, no need for temp files, be careful not to run out of memory thought. */
      $f = fopen( 'php://memory', 'w' );
      /** Loop through array  */
      
      $activity_filename = 'gdpr-cookie-compliance' . '-(' . $start_date . '_' . $end_date . ')-consent-log.csv';
      fputcsv( $f, $array_keys );
      foreach ( $log_array as $line ) {
        /** Default php csv handler. */
        fputcsv( $f, $line, ',' );
      }
      /** Rewrind the "file" with the csv lines. */
      fseek( $f, 0 );
      /** Modify header to be downloadable csv file. */
      header( 'Content-Type: application/csv' );
      header( 'Content-Disposition: attachement; filename="' . $activity_filename . '";' );
      /** Send file to browser for download. */
      fpassthru( $f );
      exit;
        
		}

		/**
		 * Remove all consent log values from database
		 */
		public static function reset_log() {
			global $wpdb;
			$table_name = self::_table();
			return $wpdb->query( "DELETE FROM `$table_name`" );
		}

		/**
		 * Remove all consent log values from database
		 */
		public static function delete_log_entry( $id = null ) {
			if ( $id && intval( $id ) ) :
				global $wpdb;
				$table_name = self::_table();
				return $wpdb->get_results( $wpdb->prepare( "DELETE FROM `$table_name` WHERE `id` = %s", $id ) );
			endif;
			return false;
		}

	}
	new Moove_GDPR_Consent_Log();
}
