<?php
/**
 * Moove_GDPR_Updater File Doc Comment
 *
 * @category Moove_GDPR_Updater
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Moove_GDPR_Updater' ) ) {
	/**
	 * Moove_GDPR_Updater Class Doc Comment
	 *
	 * @category Class
	 * @package  Moove_GDPR_Updater
	 * @author   Moove Agency
	 */
	class Moove_GDPR_Updater {
		/**
		 * Update data
		 *
		 * @var array $update_data Update array
		 */
		public $update_data = array();

		/**
		 * Active plugins
		 *
		 * @var array $active_plugins Active plugins.
		 */
		public $active_plugins = array();

		/**
		 * Construct
		 */
		public function __construct() {
			if ( class_exists( 'Moove_GDPR_License_Manager' ) ) :
				add_action( 'gdpr_plugin_updater_notice', array( &$this, 'gdpr_plugin_updater_notice' ) );
				global $pagenow;
				$allowed_pages = array( 'update-core.php', 'plugins.php' );
				$plugin_slug   = false;
				$lm            = new Moove_GDPR_License_Manager();
				$plugin_slug   = $lm->get_add_on_plugin_slug();
				wp_verify_nonce( 'gdpr_nonce', 'gdpr_cookie_compliance_nonce' );
				if ( in_array( $pagenow, $allowed_pages ) ) :
					$this->gdpr_plugin_updater_notice();
					$this->gdpr_check_for_updates();
				elseif ( ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'moove-gdpr' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) ) :
					$this->gdpr_check_for_updates();
				endif;
				add_filter( 'plugins_api', array( &$this, 'plugins_api' ), 10, 3 );
				add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'set_update_data' ) );
				add_filter( 'upgrader_source_selection', array( &$this, 'upgrader_source_selection' ), 10, 4 );
				if ( $plugin_slug ) :
					add_action( "in_plugin_update_message-{$plugin_slug}", array( &$this, 'gdpr_update_message_content' ), 10, 2 );
				endif;
			endif;
		}

		/**
		 * Update message
		 *
		 * @param array  $plugin_data Plugin data.
		 * @param object $response Response.
		 */
		public function gdpr_update_message_content( $plugin_data, $response ) {
			if ( isset( $plugin_data['package'] ) && ! $plugin_data['package'] ) :
				$gdpr_default_content = new Moove_GDPR_Content();
				$option_key           = $gdpr_default_content->moove_gdpr_get_key_name();
				$gdpr_key             = function_exists( 'get_site_option' ) ? get_site_option( $option_key ) : get_option( $option_key );
				$license_key          = isset( $gdpr_key['key'] ) ? sanitize_text_field( $gdpr_key['key'] ) : false;
				$renew_link           = MOOVE_SHOP_URL . '?renew=' . $license_key;
				$license_manager      = admin_url( 'admin.php' ) . '?page=moove-gdpr&amp;tab=licence';
				$purchase_link        = 'https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/';
				if ( $license_key && isset( $gdpr_key['activation'] ) ) :
					// Expired.
					echo ' Update is not available until you <a href="' . esc_url( $renew_link ) . '" target="_blank">renew your licence</a>. You can also update your licence key in the <a href="' . esc_url( $license_manager ) . '" target="_blank">Licence Manager</a>.';
				elseif ( $license_key && isset( $gdpr_key['deactivation'] ) ) :
					// Deactivated.
					echo ' Update is not available until you <a href="' . esc_url( $purchase_link ) . '" target="_blank">purchase a licence</a>. You can also update your licence key in the <a href="' . esc_url( $license_manager ) . '" target="_blank">Licence Manager</a>.';
				elseif ( ! $license_key ) :
					// No license key installed.
					echo ' Update is not available until you <a href="' . esc_url( $purchase_link ) . '" target="_blank">purchase a licence</a>. You can also update your licence key in the <a href="' . esc_url( $license_manager ) . '" target="_blank">Licence Manager</a>.';
				endif;
			endif;
			return array();
		}

		/**
		 * Updater notice
		 */
		public function gdpr_plugin_updater_notice() {
			update_option( 'gpdr_last_checked', strtotime( 'yesterday' ) );
			delete_site_transient( 'update_plugins' );
		}

		/**
		 * Update checker
		 */
		public function gdpr_check_for_updates() {
			$this->update_data = get_option( 'gdpr_update_data' );
			$active            = get_option( 'active_plugins' );
			$last_checked      = get_option( 'gpdr_last_checked' );
			$now               = strtotime( 'now' );
			$check_interval    = 6 * HOUR_IN_SECONDS;

			foreach ( $active as $slug ) :
				$this->active_plugins[ $slug ] = true;
			endforeach;

			// transient expiration.
			if ( ( $now - $last_checked ) > $check_interval ) :
				$this->update_data = $this->get_addon_updates();
				update_option( 'gdpr_update_data', $this->update_data );
				update_option( 'gpdr_last_checked', $now );
				$plugins     = get_site_transient( 'update_plugins' );
				$lm          = new Moove_GDPR_License_Manager();
				$plugin_slug = $lm->get_add_on_plugin_slug();
				$gdpr_default_content = new Moove_GDPR_Content();
				$option_key           = $gdpr_default_content->moove_gdpr_get_key_name();
				$gdpr_key             = function_exists( 'get_site_option' ) ? get_site_option( $option_key ) : get_option( $option_key );
				$license_key          = sanitize_text_field( $gdpr_key['key'] );

				if ( $plugin_slug ) :
					if ( $license_key && ! isset( $gdpr_key['deactivation'] ) ) :
						if ( isset( $plugins->response[ $plugin_slug ] ) ) :
							$plugins->response[ $plugin_slug ]->new_version = $this->update_data[ $plugin_slug ]['new_version'];
							$plugins->response[ $plugin_slug ]->package     = $this->update_data[ $plugin_slug ]['package'];
							set_site_transient( 'update_plugins', $plugins );
						endif;
					else :
						if ( isset( $plugins->response[ $plugin_slug ] ) ) :
							$plugins->response[ $plugin_slug ]->new_version = $this->update_data[ $plugin_slug ]['new_version'];
							$plugins->response[ $plugin_slug ]->package     = '';
							set_site_transient( 'update_plugins', $plugins );
						endif;
					endif;
				endif;
			endif;
		}


		/**
		 * Fetch the latest GitHub tags and build the plugin data array
		 */
		public function get_addon_updates() {
			$plugin_data          = array();
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_key           = $gdpr_default_content->moove_gdpr_get_key_name();
			$gdpr_key             = function_exists( 'get_site_option' ) ? get_site_option( $option_key ) : get_option( $option_key );

			$license_key          = sanitize_text_field( $gdpr_key['key'] );

			if ( $license_key ) :
				if ( ! function_exists( 'get_plugins' ) ) :
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				endif;
				$plugins = function_exists( 'get_plugins' ) ? get_plugins() : array();

				foreach ( $plugins as $slug => $info ) :


					if ( isset( $info['TextDomain'] ) && 'gdpr-cookie-compliance-addon' === $info['TextDomain'] ) :
						$license_manager  = new Moove_GDPR_License_Manager();
						$is_valid_license = $license_manager->get_premium_add_on( $license_key, 'update' );
						$temp             = array(
							'plugin'      => $slug,
							'slug'        => trim( dirname( $slug ), '/' ),
							'name'        => $info['Name'],
							'description' => $info['Description'],
							'new_version' => false,
							'package'     => false,
						);
						if ( $is_valid_license && isset( $is_valid_license['valid'] ) ) :

							$plugin_token   = isset( $is_valid_license['data'] ) && isset( $is_valid_license['data']['download_token'] ) && $is_valid_license['data']['download_token'] ? $is_valid_license['data']['download_token'] : false;
							$plugin_version = isset( $is_valid_license['data'] ) && isset( $is_valid_license['data']['version'] ) && $is_valid_license['data']['version'] ? $is_valid_license['data']['version'] : 0;
							$plugin_slug = $license_manager->get_add_on_plugin_slug();

							$plugins = get_plugins();
							if ( isset( $plugins[$plugin_slug] ) && isset( $plugins[$plugin_slug]['Version'] ) && $plugins[$plugin_slug]['Version'] < $plugin_version ) :
								$temp['new_version'] = $plugin_version;
							endif;

							$temp['package']     = ! isset( $gdpr_key['deactivation'] ) ? $plugin_token : '';

						endif;
						$plugin_data[ $slug ] = $temp;
					endif;
				endforeach;
			endif;
			return $plugin_data;
		}


		/**
		 * Get plugin info for the "View Details" popup
		 *
		 * @param bool   $default Default.
		 * @param string $action Action.
		 * @param array  $args Args.
		 */
		public function plugins_api( $default = false, $action, $args ) {
			if ( 'plugin_information' === $action ) {
				$plugin_data       = array();
				$this->update_data = get_option( 'gdpr_update_data' );
				if ( is_array( $this->update_data ) && ! empty( $this->update_data ) ) :
					foreach ( $this->update_data as $slug => $data ) :
						if ( $data['slug'] === $args->slug ) :
							if ( class_exists( 'Moove_GDPR_Controller' ) ) :
								$gdpr_controller = new Moove_GDPR_Controller();
								$plugin_details  = $gdpr_controller->get_gdpr_plugin_details( 'gdpr-cookie-compliance' );
								unset( $plugin_details->sections['screenshot'] );
								unset( $plugin_details->sections['changelog'] );
								unset( $plugin_details->sections['installation'] );
								$plugin_details->name         = $data['name'];
								$plugin_details->slug         = $data['plugin'];
								$plugin_details->version      = $data['new_version'];
								$plugin_details->last_updated = '';
								$plugin_details->banners      = array(
									'high' => 'https://ps.w.org/gdpr-cookie-compliance/assets/banner-772x250.png',
								);
								return (object) $plugin_details;
							endif;
						endif;
					endforeach;
				endif;
			}
			return $default;
		}

		/**
		 * Set update data
		 *
		 * @param string $transient Transient key.
		 */
		public function set_update_data( $transient ) {
			if ( empty( $transient->checked ) ) {
				return $transient;
			}
			if ( isset( $this->update_data ) && is_array( $this->update_data ) ) :
				foreach ( $this->update_data as $plugin => $info ) {
					if ( isset( $this->active_plugins[ $plugin ] ) ) {
						$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
						$version     = $plugin_data['Version'];

						if ( version_compare( $version, $info['new_version'], '<' ) ) {
							$transient->response[ $plugin ] = (object) $info;
						}
					}
				}
			endif;
			return $transient;
		}

		/**
		 * Rename the plugin folder
		 *
		 * @param string $source Source.
		 * @param string $remote_source Remote source.
		 * @param object $upgrader Upgrader.
		 * @param bool   $hook_extra Hook extra.
		 */
		public function upgrader_source_selection( $source, $remote_source, $upgrader, $hook_extra = null ) {
			global $wp_filesystem;
			$plugin = isset( $hook_extra['plugin'] ) ? $hook_extra['plugin'] : false;
			if ( isset( $this->update_data[ $plugin ] ) && $plugin ) :
				$lm          = new Moove_GDPR_License_Manager();
				$plugin_slug = $lm->get_add_on_plugin_slug();
				$temp_slug   = basename( trailingslashit( $source ) );
				$plugin_slug = explode( '/', $plugin_slug );
				$plugin_slug = isset( $plugin_slug[0] ) && $plugin_slug[0] ? $plugin_slug[0] : 'gdpr-cookie-compliance-addon';

				if ( $temp_slug !== $plugin_slug ) :
					$new_source = trailingslashit( $remote_source );
					$new_source = str_replace( $temp_slug, $plugin_slug, $new_source );
					$wp_filesystem->move( $source, $new_source );
					return trailingslashit( $new_source );
				endif;
			endif;
			return $source;
		}

		/**
		 * Hide update notice
		 */
		public function moove_hide_update_notice() {
			wp_verify_nonce( 'gdpr_nonce', 'gdpr_cookie_compliance_nonce' );
			$version = isset( $_POST['version'] ) ? sanitize_text_field( wp_unslash( $_POST['version'] ) ) : false;
			if ( $version ) :
				$current_user = wp_get_current_user();
				$user_id      = isset( $current_user->ID ) ? $current_user->ID : false;
				update_option( 'gdpr_hide_update_notice_' . $user_id, $version );
			endif;
		}

	}
	new Moove_GDPR_Updater();
}
