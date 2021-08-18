<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; } // Exit if accessed directly

/**
 * Moove_GDPR_Addon_Actions File Doc Comment
 *
 * @category  Moove_GDPR_Addon_Actions
 * @package   moove-gdpr
 * @author    Moove Agency
 */

/**
 * Moove_GDPR_Addon_Actions Class Doc Comment
 *
 * @category Class
 * @package  Moove_GDPR_Addon_Actions
 * @author   Moove Agency
 */
class Moove_GDPR_Addon_Actions {

	/**
	 * Construct
	 */
	public function __construct() {
		$this->gdpr_addon_register_scripts();
		$this->gdpr_addon_register_ajax_actions();
		add_action( 'gdpr_info_bar_class_extension', array( &$this, 'gdpr_info_bar_class_extension' ), 10, 1 );
		add_action( 'gdpr_cookie_filter_settings', array( &$this, 'gdpr_cookie_filter_settings' ) );
		add_action( 'gdpr_info_bar_notice_content', array( &$this, 'gdpr_info_bar_notice_content' ), 10, 1 );
		add_action( 'gdpr_info_bar_button_extensions', array( &$this, 'gdpr_info_bar_button_extensions' ) );
		add_action( 'gdpr_settings_tab_nav_extensions', array( &$this, 'gdpr_settings_tab_nav_extensions' ), 10, 1 );
		add_action( 'gdpr_settings_tab_content', array( &$this, 'gdpr_settings_tab_content' ), 10, 2 );
		add_action( 'admin_menu', array( &$this, 'gdpr_download_export_csv' ) );
		add_action( 'gdpr_cookie_compliance_donate_section', array( &$this, 'gdpr_cookie_compliance_donate_section' ), 10, 1 );
		add_action( 'gdpr_cookie_compliance_premium_section', array( &$this, 'gdpr_cookie_compliance_premium_section' ), 10, 1 );
		add_action( 'gdpr_cookie_compliance_faq_section_link', array( &$this, 'gdpr_cookie_compliance_faq_section_link' ), 10, 1 );
		add_action( 'gdpr_cookie_compliance_forum_section_link', array( &$this, 'gdpr_cookie_compliance_forum_section_link' ), 10, 1 );
		add_action( 'gdpr_extend_loc_data', array( &$this, 'gdpr_extend_loc_data' ), 10, 1 );
		add_action( 'moove_gdpr_inline_styles', array( &$this, 'gdpr_cc_addon_styles' ), 10, 3 );
		add_action( 'init', array( 'Moove_GDPR_Addon_Analytics', 'gdpr_analytics_cpt' ), 0 );
		add_action( 'gdpr_cookie_analytics_section', array( &$this, 'gdpr_cookie_analytics_section_loader' ), 10, 2 );
		add_action( 'gdpr_infobar_base_module', array( &$this, 'gdpr_infobar_base_module_selected_posts' ), 10, 1 );
		add_shortcode( 'gdpr_cookie_settings_content', array( &$this, 'gdpr_register_cookie_settings_shortcode' ) );
		add_filter( 'the_content', array( &$this, 'gdpr_block_content_iframes' ), 100, 1 );
		add_filter( 'gdpr_iframe_blocker_filter', array( &$this, 'gdpr_iframe_blocker_filter' ), 10, 1 );
		add_action( 'gdpr_tab_cbm_ph', array( &$this, 'gdpr_tab_cbm_ph' ), 10, 1 );
		add_action( 'gdpr_tab_cbm_ps', array( &$this, 'gdpr_tab_cbm_ps' ), 10, 1 );
		add_action( 'gdpr_tab_code_section_nav_extension', array( &$this, 'gdpr_tab_code_section_nav_extension' ), 10, 1 );
		add_action( 'gdpr_tab_code_section_content_extension', array( &$this, 'gdpr_tab_code_section_content_extension' ), 10, 1 );
		add_action( 'gdpr_ps_check_language_extensions', array( &$this, 'gdpr_ps_check_language_extensions' ), 10, 3 );
		add_action( 'gdpr_lss_extension', array( &$this, 'gdpr_lss_extension' ), 10, 2 );
		add_action( 'gdpr_support_sidebar_class', array( &$this, 'gdpr_support_sidebar_class' ), 10, 1 );
		add_action( 'gdpr_addon_keephtml', array( &$this, 'gdpr_addon_keephtml' ), 10, 2 );
		add_shortcode( 'gdpr_iframe_blocker', array( &$this, 'gdpr_iframe_blocker_shortcode' ), 10, 2 );
		add_action( 'gdpr_iframe_blocker_cookie', array( &$this, 'gdpr_iframe_blocker_cookie' ), 10, 2 );
		add_action( 'gdpr_modules_content_extension', array( &$this, 'gdpr_modules_content_extension' ), 10, 2 );
		add_action( 'gdpr_consent_log_user_info', array( &$this, 'gdpr_consent_log_user_info' ), 10, 1 );
		add_action( 'admin_init', array( &$this, 'gdpr_consent_log_export_check' ) );
		add_action( 'gdpr_cc_geolocation_status', array( &$this, 'gdpr_cc_geolocation_status' ), 10, 2 );
		add_action( 'gdpr_cc_general_modal_settings', array( &$this, 'gdpr_cc_general_buttons_settings_analytics' ), 10 );
		add_action( 'gdpr_disable_main_assets_enqueue', array( &$this, 'gdpr_check_main_assets' ), 10, 1 );
		add_action( 'gdpr_cc_banner_is_user_restricted', array( &$this, 'gdpr_cc_banner_is_user_restricted' ), 10, 1 );

		add_action( 'gdpr_modal_base_module', array( &$this, 'gdpr_cc_banner_is_user_restricted_content' ), 10, 1 );
		add_action( 'gdpr_infobar_base_module', array( &$this, 'gdpr_cc_banner_is_user_restricted_content' ), 10, 1 );
		add_action( 'gdpr_branding_styles_module', array( &$this, 'gdpr_cc_banner_is_user_restricted_content' ), 10, 1 );
		add_action( 'gdpr_floating_button_module', array( &$this, 'gdpr_cc_banner_is_user_restricted_content' ), 10, 1 );

		add_action( 'gdpr_ifb_blocked_content_filter', array( &$this, 'gdpr_ifb_blocked_content_filter_cbm' ), 10, 2 );
		/**
		 * Full Screen Mode Layout
		 */
		add_action( 'gdpr_infobar_content_module', array( &$this, 'gdpr_infobar_fullscreen_layout_extension' ), 10, 1 );
	}

	public static function gdpr_check_main_assets( $value ) {
		$value = apply_filters( 'gdpr_cc_banner_is_user_restricted', $value );
		return $value;
	}

	public static function gdpr_ifb_blocked_content_filter_cbm( $content, $initial_content ) {
		$is_restricted = apply_filters( 'gdpr_cc_banner_is_user_restricted', false );
		$content = $is_restricted ? $initial_content : $content;
		return $content;
	}

	public static function gdpr_cc_banner_is_user_restricted_content( $content ) {
		$is_restricted = apply_filters( 'gdpr_cc_banner_is_user_restricted', false );
		$content = $is_restricted ? '' : $content;
		return $content;
	}

	public static function gdpr_cc_banner_is_user_restricted( $value ) {
		if ( ! is_admin() ) :
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$gdpr_options         = get_option( $option_name );
			$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
			$gdpr_options         = is_array( $gdpr_options ) ? $gdpr_options : array();

			if ( $gdpr_options && isset( $gdpr_options['gdpr_load_plugin'] ) ) :
				$gdpr_lp = intval( $gdpr_options['gdpr_load_plugin'] );

				$disabled_roles = isset( $gdpr_options['gdpr_hide_by_role'] ) && $gdpr_options['gdpr_hide_by_role'] ? json_decode( $gdpr_options['gdpr_hide_by_role'], true ) : array();

				$current_user_id = get_current_user_id();

				if ( $gdpr_lp === 1 && $current_user_id ) :
					$user_meta  = get_userdata( $current_user_id );
					$user_roles = $user_meta->roles;

					if ( $user_roles ) :
						foreach ( $user_roles as $role ) :
							if ( in_array( $role, $disabled_roles, true ) ) :
								$value = true;
							endif;
						endforeach;
					endif;
				endif;

				if ( $gdpr_lp === 2 ) :
					// Only logged-in users.
					$value = is_user_logged_in() ? false : true;

					$user_meta  = get_userdata( $current_user_id );
					$user_roles = $user_meta->roles;
					if ( $user_roles ) :
						foreach ( $user_roles as $role ) :
							if ( in_array( $role, $disabled_roles, true ) ) :
								$value = true;
							endif;
						endforeach;
					endif;

				endif;

				if ( $gdpr_lp === 3 ) :
					// Only users who are not logged-in.
					$value = is_user_logged_in() ? true : false;
				endif;

			endif;
		endif;

		return $value;
	}

	/**
	 * GDRP Remove Analytics from General Settings screen on Reset Settings
	 */
	public static function gdpr_cc_general_buttons_settings_analytics() {
		try {
			if ( isset( $_POST ) && isset( $_POST['moove_gdpr_reset_nonce'] ) ) :
				$nonce = sanitize_key( $_POST['moove_gdpr_reset_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_reset_nonce_field' ) ) :
					die( 'Security check' );
				else :
					if ( isset( $_POST['gdpr_reset_settings'] ) && intval( $_POST['gdpr_reset_settings'] )  === 1 ) :
						// Analytics reset
						global $wpdb;
						$_post_type = 'gdpr_analytics';
						if ( post_type_exists( $_post_type ) ) :
							$result = $wpdb->query(
								$wpdb->prepare(
									' DELETE posts,pt,pm
				            FROM wp_posts posts
				            LEFT JOIN wp_term_relationships pt ON pt.object_id = posts.ID
				            LEFT JOIN wp_postmeta pm ON pm.post_id = posts.ID
				            WHERE posts.post_type = %s
				            ',
									$_post_type
								)
							);
						endif;

						// Consent Log reset
						$log_controller = new Moove_GDPR_Consent_Log();
						$log_controller->reset_log();
					endif;
				endif;
			endif;
		} catch (Exception $e) {
			
		}		
	}

	public static function gdpr_cc_geolocation_status( $status = 'false', $modal_options ) {
		$geo_setup = false;

		if ( isset( $modal_options['moove_gdpr_cc_geo_status'] ) ) :
			// New support - checkbox layout
			$geo_status = intval( $modal_options['moove_gdpr_cc_geo_status'] ) === 1;
			$geo_setup 	= isset( $modal_options['moove_gdpr_cc_geo_setup'] ) ? json_decode( $modal_options['moove_gdpr_cc_geo_setup'], true ) : array();
		else :
			// Legacy support - radio layout
			$geo_setup 	= isset( $modal_options['moove_gdpr_geolocation_eu'] ) ? $modal_options['moove_gdpr_geolocation_eu'] : '';
			$geo_setup 	= is_array( $geo_setup ) ? $geo_setup : array( intval( $geo_setup ) => 'true' );
			$geo_status = isset( $geo_setup[0] ) ? false : true;
		endif;

		$status = $geo_status ? 'true' : 'false';

		return $status;
	}



	public static function gdpr_infobar_fullscreen_layout_extension( $content ) {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$gdpr_options         = get_option( $option_name );
		$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
		$gdpr_options         = is_array( $gdpr_options ) ? $gdpr_options : array();

		if ( isset( $gdpr_options['moove_gdpr_full_screen_enable'] ) && intval( $gdpr_options['moove_gdpr_full_screen_enable'] ) === 1 ) :
			if ( $gdpr_options['moove_gdpr_full_screen_banner_layout'] && intval( $gdpr_options['moove_gdpr_full_screen_banner_layout'] ) === 1 ) :
				$infobar_content        = isset( $gdpr_options[ 'moove_gdpr_info_bar_content' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_info_bar_content' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_info_bar_content' . $wpml_lang ] : $content;
				$infobar_content        = str_replace( '[setting]', '<setting>', $infobar_content );
				$infobar_content        = str_replace( '[/setting]', '</setting>', $infobar_content );
				$infobar_content        = preg_match_all( '/<setting>(.*?)<\/setting>/s', $infobar_content, $matches );
				$settings_label 				= isset( $gdpr_options[ 'moove_gdpr_infobar_settings_button_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_infobar_settings_button_label' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_infobar_settings_button_label' . $wpml_lang ] : false;
				$settings_label 				= $settings_label ? $settings_label : ucfirst( isset( $matches[1][0] ) && $matches[1][0] ? $matches[1][0] : __( 'settings', 'gdpr-cookie-compliance-addon' ) );

				$content .= do_shortcode("[gdpr_cookie_settings_content settings_button_label='".$settings_label."']");
			endif;
		endif;
		return $content;
	}

	public static function gdpr_consent_log_export_check() {
		if ( isset( $_GET['consent_export'] ) && isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === 'moove-gdpr' ) :
			$export_interval	= sanitize_text_field( wp_unslash( $_GET['consent_export'] ) );
			$export_interval 	= explode( '_', $export_interval );
			$start_date 			= isset( $export_interval[0] ) && intval( $export_interval[0] ) ? intval( $export_interval[0] ) : false;
			$end_date 				= isset( $export_interval[1] ) && intval( $export_interval[1] ) ? intval( $export_interval[1] ) : false;
			if ( $start_date && $end_date ) :
				$start_date 	= date( 'Y-m-d', $start_date );
				$end_date 		= date( 'Y-m-d', $end_date );

				$consent_cnt = new Moove_GDPR_Consent_Log();
				$consent_cnt->export_log( $start_date, $end_date );
			endif;
		endif;
	}

	/**
	 * User detail in log
	 */
	public static function gdpr_consent_log_user_info( $user_id ) {
		$data 				= 'Not logged in';
		$_data_cached = wp_cache_get( 'gdpr_cl_uinfo' . $user_id );
		if ( ! $_data_cached ) :
			if ( intval( $user_id ) ) :
				$user = get_user_by( 'ID', $user_id );
				$data = $user->user_email;
				wp_cache_set( 'gdpr_cl_uinfo' . $user_id, $data );
			endif;
		else :
			$data = $_data_cached;
		endif;
		return $data;
	}

	/**
	 * Iframe blocker shortcode
	 * @param array $atts Atts.
	 * @param string $content Content.
	 */
	public static function gdpr_iframe_blocker_shortcode( $atts = array(), $content = null ) {
		$content = apply_filters( 'gdpr_iframe_blocker_filter', $content );
		return $content;
	}

	/**
	 * Cookie declaration front-end shortcode
	 */
	public static function gdpr_modules_content_extension( $content, $type = '' ) {
		$view_controller = new Moove_GDPR_Addon_View();
		$content         = $view_controller->load( 'moove.shortcode.cookie-declarations', array( 'content' => $content, 'type'	=> $type ) );
		apply_filters( 'gdpr_addon_keephtml', $content, true );
	}

	/**
	 * Sanitize filter allowing html tags and styles with attributes
	 *
	 * @param string  $content
	 * @param boolean $echo Option echo the value or return.
	 */
	public static function gdpr_addon_keephtml( $content, $echo = false ) {
		if ( $echo ) :
			echo $content;
		else :
			return $content;
		endif;
	}

	/**
	 * Filter to update iframe blocker cookie type
	 */
	public static function gdpr_iframe_blocker_cookie( $cookie ) {
		return $cookie;
	}

	/**
	 * Sidebar extra class extension
	 *
	 * @param string $class Current class.
	 */
	public function gdpr_support_sidebar_class( $class ) {
		return 'm-plugin-box-highlighted';
	}

	/**
	 * Language script injection
	 *
	 * @param array $scripts_array Language specific scripts.
	 */
	public function gdpr_lss_extension( $scripts_array, $wp_lang = '' ) {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$wpml_lang            = $wp_lang && $wp_lang !== '' ? $wp_lang : $gdpr_default_content->moove_gdpr_get_wpml_lang();
		$gdpr_options         = get_option( $option_name );

		if ( $wpml_lang ) :

			$form_key                            = 'moove_gdpr_lss_head_advanced_' . $wpml_lang;
			$scripts_array['advanced']['header'] = isset( $gdpr_options[ $form_key ] ) && $gdpr_options[ $form_key ] ? $scripts_array['advanced']['header'] . $gdpr_options[ $form_key ] : $scripts_array['advanced']['header'];

			$form_key                          = 'moove_gdpr_lss_body_advanced_' . $wpml_lang;
			$scripts_array['advanced']['body'] = isset( $gdpr_options[ $form_key ] ) && $gdpr_options[ $form_key ] ? $scripts_array['advanced']['body'] . $gdpr_options[ $form_key ] : $scripts_array['advanced']['body'];

			$form_key                            = 'moove_gdpr_lss_footer_advanced_' . $wpml_lang;
			$scripts_array['advanced']['footer'] = isset( $gdpr_options[ $form_key ] ) && $gdpr_options[ $form_key ] ? $scripts_array['advanced']['footer'] . $gdpr_options[ $form_key ] : $scripts_array['advanced']['footer'];

			$form_key                              = 'moove_gdpr_lss_head_third_party_' . $wpml_lang;
			$scripts_array['thirdparty']['header'] = isset( $gdpr_options[ $form_key ] ) && $gdpr_options[ $form_key ] ? $scripts_array['thirdparty']['header'] . $gdpr_options[ $form_key ] : $scripts_array['thirdparty']['header'];

			$form_key                            = 'moove_gdpr_lss_body_third_party_' . $wpml_lang;
			$scripts_array['thirdparty']['body'] = isset( $gdpr_options[ $form_key ] ) && $gdpr_options[ $form_key ] ? $scripts_array['thirdparty']['body'] . $gdpr_options[ $form_key ] : $scripts_array['thirdparty']['body'];

			$form_key                              = 'moove_gdpr_lss_footer_third_party_' . $wpml_lang;
			$scripts_array['thirdparty']['footer'] = isset( $gdpr_options[ $form_key ] ) && $gdpr_options[ $form_key ] ? $scripts_array['thirdparty']['footer'] . $gdpr_options[ $form_key ] : $scripts_array['thirdparty']['footer'];

		endif;
		return $scripts_array;
	}

	/**
	 * Language extension checker
	 *
	 * @param array  $empty_scripts Scripts array.
	 * @param array  $post_data Postdata.
	 * @param string $type Tab type.
	 */
	public function gdpr_ps_check_language_extensions( $empty_scripts, $post_data, $type ) {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
		$gdpr_options         = get_option( $option_name );

		if ( $wpml_lang ) :

			if ( ( isset( $post_data[ 'moove_gdpr_lss_head_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ) && strlen( $post_data[ 'moove_gdpr_lss_head_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ) !== 0 ) || ( isset( $post_data[ 'moove_gdpr_lss_body_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ) && strlen( $post_data[ 'moove_gdpr_lss_body_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ) !== 0 ) || ( isset( $post_data[ 'moove_gdpr_lss_footer_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ) && strlen( $post_data[ 'moove_gdpr_lss_footer_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ) !== 0 ) ) :

				$keys = array( 'head', 'body', 'footer' );

				foreach ( $keys as $key ) :
					$form_key = 'moove_gdpr_lss_' . $key . '_' . $type . '_' . $wpml_lang;
					if ( isset( $post_data[ $form_key ] ) ) :
						$form_value                = $post_data[ $form_key ];
						$value                     = wp_unslash( $form_value );
						$gdpr_options[ $form_key ] = maybe_serialize( $value );
					endif;
			endforeach;
				update_option( $option_name, $gdpr_options );

				$empty_scripts = false;
		else :
			$empty_scripts = true;
		endif;
	endif;

		return $empty_scripts;
	}

	/**
	 * Nav section extension
	 *
	 * @param string $type Section type.
	 */
	public function gdpr_tab_code_section_nav_extension( $type ) {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
		if ( $wpml_lang ) :
			?>
			<li>
				<a href="#<?php echo esc_attr( $type ); ?>_language_specific_scripts"><?php esc_html_e( 'Language Specific Scripts', 'gdpr-cookie-compliance' ); ?></a>
			</li>
			<?php
		endif;
	}

	/**
	 * Tab code content extension by language variations
	 *
	 * @param string $type Could be third-party or advanced.
	 */
	public function gdpr_tab_code_section_content_extension( $type ) {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
		$gdpr_options         = get_option( $option_name );
		if ( $wpml_lang ) :
			$language_name = gdpr_get_display_language_by_locale( str_replace( '_', '', $wpml_lang ) );
			?>

			<div class="gdpr-tab-code-section" id="<?php echo esc_attr( $type ); ?>_language_specific_scripts">
				<?php /* translators: %s: language name */ ?>
				<h4 class="gdpr-lang-spec-title"><?php printf( esc_html__( 'Add scripts that you would like to be inserted to the pages of your current language variation %s when user accepts these cookies.', 'gdpr-cookie-compliance' ), '<span>' . esc_attr( $language_name ) ) . '</span>'; ?></h4>

				<table>
					<tbody>
						<tr class="moove_gdpr_lss_head_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>">
							<td scope="row" colspan="2" style="padding: 0 0 20px 0;">
								<h3 style="margin-bottom: 0;"><?php esc_html_e( 'Head Scripts', 'gdpr-cookie-compliance' ); ?></h3>
								<?php
								$content = isset( $gdpr_options[ 'moove_gdpr_lss_head_' . $type . '_' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_lss_head_' . $type . '_' . $wpml_lang ] ? maybe_unserialize( $gdpr_options[ 'moove_gdpr_lss_head_' . $type . '_' . $wpml_lang ] ) : '';
								?>
								<textarea name="moove_gdpr_lss_head_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>" id="moove_gdpr_lss_head_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>" class="large-text code" rows="13"><?php apply_filters( 'gdpr_addon_keephtml', $content, true ); ?></textarea>
								<div class="gdpr-code"></div>
								<!--  .gdpr-code -->
								<p class="description" id="moove_gdpr_lss_head_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>-description"><?php esc_html_e( 'For example, you can use it for Google Tag Manager script or any other 3rd party code snippets.', 'gdpr-cookie-compliance' ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>

				<hr />

				<table>
					<tbody>
						<tr class="moove_gdpr_lss_body_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>">
							<td scope="row" colspan="2" style="padding: 0 0 20px 0;">
								<h3 style="margin-bottom: 0;"><?php esc_html_e( 'Body Scripts', 'gdpr-cookie-compliance' ); ?></h3>
								<?php
								$content = isset( $gdpr_options[ 'moove_gdpr_lss_body_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ) && $gdpr_options[ 'moove_gdpr_lss_body_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ? maybe_unserialize( $gdpr_options[ 'moove_gdpr_lss_body_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ) : '';
								?>
								<textarea name="moove_gdpr_lss_body_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>" id="moove_gdpr_lss_body_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>" class="large-text code" rows="13"><?php apply_filters( 'gdpr_addon_keephtml', $content, true ); ?></textarea>
								<div class="gdpr-code"></div>
								<!--  .gdpr-code -->
								<p class="description" id="moove_gdpr_lss_body_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>-description"><?php esc_html_e( 'For example, you can use it for Google Tag Manager script or any other 3rd party code snippets.', 'gdpr-cookie-compliance' ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>

				<hr />

				<table>
					<tbody>
						<tr class="moove_gdpr_lss_footer_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>">
							<td scope="row" colspan="2" style="padding: 0 0 20px 0;">
								<h3 style="margin-bottom: 0;"><?php esc_html_e( 'Footer Scripts', 'gdpr-cookie-compliance' ); ?></h3>
								<?php
								$content = isset( $gdpr_options[ 'moove_gdpr_lss_footer_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ) && $gdpr_options[ 'moove_gdpr_lss_footer_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ? maybe_unserialize( $gdpr_options[ 'moove_gdpr_lss_footer_' . esc_attr( $type ) . '_' . esc_attr( $wpml_lang ) ] ) : '';
								?>
								<textarea name="moove_gdpr_lss_footer_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>" id="moove_gdpr_lss_footer_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>" class="large-text code" rows="13"><?php apply_filters( 'gdpr_addon_keephtml', $content, true ); ?></textarea>
								<div class="gdpr-code"></div>
								<!--  .gdpr-code -->
								<p class="description" id="moove_gdpr_lss_footer_<?php echo esc_attr( $type ); ?>_<?php echo esc_attr( $wpml_lang ); ?>-description"><?php esc_html_e( 'For example, you can use it for Google Tag Manager script or any other 3rd party code snippets.', 'gdpr-cookie-compliance' ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
			</table>
		</div>
		<!--  .gdpr-tab-code-section -->

			<?php
	endif;
	}


	/**
	 * Shortcode Help Section - Premium Hooks
	 */
	public function gdpr_tab_cbm_ph() {
		$view_controller = new Moove_GDPR_Addon_View();
		$content         = $view_controller->load( 'moove.shortcode.tab_cbm_ph', array() );
		apply_filters( 'gdpr_addon_keephtml', $content, true );
	}

	/**
	 * Shortcode Help Section - Premium Shortcodes
	 */
	public function gdpr_tab_cbm_ps() {
		$view_controller = new Moove_GDPR_Addon_View();
		$content         = $view_controller->load( 'moove.shortcode.tab_cbm_ps', array() );
		apply_filters( 'gdpr_addon_keephtml', $content, true );
	}

	/**
	 * Iframe blocker content
	 *
	 * @param string $content Content.
	 */
	public function gdpr_iframe_blocker_filter( $content ) {
		$initial_content = $content;
		if ( class_exists( 'Moove_GDPR_Content' ) && apply_filters( 'gdpr_ifb_use_dom_filter', true ) ) :
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$modal_options        = get_option( $option_name );
			$iframe_blocked 			= false;
			if ( isset( $modal_options['moove_gdpr_modal_enable_ifb'] ) && intval( $modal_options['moove_gdpr_modal_enable_ifb'] ) === 1 && $content ) :
				try {
					$dom = new DOMDocument();
					$dom->encoding = 'utf-8';
					if ( function_exists('libxml_use_internal_errors') ) :
						libxml_use_internal_errors( true );
					endif;
					$dom->loadHTML( '<div class="gdpr-ifbc-wrap">' . mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8') . '</div>' , LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED );
					foreach ( $dom->getElementsByTagName( 'iframe' ) as $iframe ) :					
						if ( ! $iframe->getAttribute( 'data-gdpr-iframesrc' ) ) :
							$src     = $iframe->getAttribute( 'src' );
							$new_src = admin_url( 'admin-ajax.php?action=gdpr_iframe_blocker&src=' . $src );
							$new_src = apply_filters( 'gdpr_iframe_new_src_filter', $new_src, $src );

							$ifbc_excl_value 				= isset( $modal_options['gdpr-ifb-exclusion'] ) && $modal_options['gdpr-ifb-exclusion'] ? $modal_options['gdpr-ifb-exclusion'] : json_encode( array() );
							$ifbc_excl_value 				= apply_filters( 'gdpr_iframe_blocker_exclude', $ifbc_excl_value );
							$ifbc_excl_value 				= json_decode( $ifbc_excl_value, true );

							if ( is_array( $ifbc_excl_value ) ) :
								foreach ( $ifbc_excl_value as $ifbc_excluded ) :
									if ( strpos( $src, $ifbc_excluded ) !== false ) :
										$new_src = $src;
									endif;
								endforeach;
							endif;

							if ( $new_src !== $src ) :
								$iframe->setAttribute( 'src', $new_src );
								$iframe->setAttribute( 'data-gdpr-iframesrc', $src );
								$iframe_blocked = true;
							endif;
						endif;
					endforeach;
					if ( $iframe_blocked ) :
						$dom->normalizeDocument();
						$content = $dom->saveHTML( $dom->documentElement );
					endif;
				} catch (Exception $e) {
					// Error in LIBXML
				}
			endif;
		endif;
		return apply_filters('gdpr_ifb_blocked_content_filter', $content, $initial_content );
	}

	/**
	 * Iframe blocker filtered content
	 *
	 * @param string $content Content.
	 */
	public static function gdpr_block_content_iframes( $content ) {
		$content = apply_filters( 'gdpr_iframe_blocker_filter', $content );
		return $content;
	}

	/**
	 * Cookie settings shortcode view
	 *
	 * @param array $atts Attributes.
	 */
	public function gdpr_register_cookie_settings_shortcode( $atts ) {
		if ( class_exists( 'Moove_GDPR_Content' ) ) :
			$view_controller      = new Moove_GDPR_Addon_View();
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$modal_options        = get_option( $option_name );
			$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();

			$settings_label = isset( $modal_options[ 'moove_gdpr_modal_save_button_label' . $wpml_lang ] ) && $modal_options[ 'moove_gdpr_modal_save_button_label' . $wpml_lang ] ? $modal_options[ 'moove_gdpr_modal_save_button_label' . $wpml_lang ] : __( 'Save Settings', 'gdpr-cookie-compliance' );

			$data['gdpr_options']          	= $modal_options;
			$data['wpml_lang']             	= $wpml_lang;
			$data['title']                 	= isset( $atts['title'] ) ? sanitize_text_field( $atts['title'] ) : false;
			$data['content']               	= isset( $atts['content'] ) ? sanitize_text_field( $atts['content'] ) : false;
			$data['save_button_label']     	= isset( $atts['save_button_label'] ) ? sanitize_text_field( $atts['save_button_label'] ) : $settings_label;
			$data['settings_button_label'] 	= isset( $atts['settings_button_label'] ) ? sanitize_text_field( $atts['settings_button_label'] ) : false;

			$data['show_settings_button'] 	= isset( $modal_options['moove_gdpr_settings_button_enable'] ) && intval( $modal_options['moove_gdpr_settings_button_enable'] ) === 1;
			return $view_controller->load( 'moove.shortcode.cookie-settings', $data );
		else :
			return '';
		endif;
	}

	/**
	 * Hide cookie banner on selected pages
	 *
	 * @param string $content Content.
	 */
	public function gdpr_infobar_base_module_selected_posts( $content ) {
		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$gdpr_options         = get_option( $option_name );
		if ( isset( $gdpr_options['moove_gdpr_dcb'] ) && is_array( $gdpr_options['moove_gdpr_dcb'] ) ) :
			$server_host      = ( isset( $_SERVER['HTTPS'] ) && sanitize_text_field( wp_unslash( $_SERVER['HTTPS'] ) ) === 'on' ? 'https' : 'http' );
			$server_http_host = ( isset( $_SERVER['HTTP_HOST'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : false;
			$server_req_uri   = ( isset( $_SERVER['REQUEST_URI'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : false;
			$actual_link      = $server_host . '://' . $server_http_host . $server_req_uri;
			$post_id          = url_to_postid( $actual_link );
			$content          = $post_id && in_array( $post_id, $gdpr_options['moove_gdpr_dcb'], false ) ? '' : $content;
		endif;
		return $content;
	}

	/**
	 * Register Front-end / Back-end scripts
	 *
	 * @return void
	 */
	public function gdpr_addon_register_scripts() {
		if ( is_admin() ) :
			add_action( 'load-toplevel_page_moove-gdpr', array( &$this, 'gdpr_addon_admin_scripts' ) );
		else :
			add_action( 'wp_enqueue_scripts', array( &$this, 'gdpr_addon_frontend_scripts' ), 999 );
		endif;
	}
	/**
	 * Display information in the Analytics Tab
	 * Available options
	 *
	 * 1. Infobar Showed
	 * do_action('gdpr_cookie_analytics_section','infobar-showed',array('infobar_showed'=>$infobar_showed))
	 *
	 * 2. Total Sessions
	 * do_action('gdpr_cookie_analytics_section','total-sessions',array('total_sessions'=>$gdpr_analytics->found_posts));
	 *
	 * 3. Total Sessions with Cookies
	 * do_action('gdpr_cookie_analytics_section','total-sessions-cookies',array('total_sessions_coockies'=>$gdpr_analytics->found_posts,'infobar_showed'=>$infobar_showed));
	 *
	 * 4. Modal Opened
	 * do_action('gdpr_cookie_analytics_section','modal-opened',array('modal_opened'=>$modal_opened));
	 *
	 * 5. Clicked to accept all button
	 * do_action('gdpr_cookie_analytics_section','clicked-accept-all',array('accept_all'=>$clicked_to_accept));
	 *
	 * 6. Cookies Accepted
	 * do_action('gdpr_cookie_analytics_section','cookies-accepted',array('gdpr_options'=>$gdpr_options,'wpml_lang'=>$wpml_lang,'strict_c'=>$strict_c,'advanced_c'=>$advanced_c,'third_party_c'=>$third_party_c));
	 *
	 * 7. Tab Navigation
	 * do_action('gdpr_cookie_analytics_section','tab-navigation',array('gdpr_options'=>$gdpr_options,'wpml_lang'=>$wpml_lang,'tabs_inside_modal'=>$tabs_inside_modal));
	 *
	 * @param string $tab_name Tab name.
	 * @param array  $data Data.
	 * @return void
	 */
	public function gdpr_cookie_analytics_section_loader( $tab_name = false, $data = false ) {
		if ( $tab_name && $data ) :
			$view_controller = new Moove_GDPR_Addon_View();
			$content         = $view_controller->load( 'moove.admin.settings.analytics.' . $tab_name, $data );
			apply_filters( 'gdpr_addon_keephtml', $content, true );
		endif;
	}

	/**
	 * Extension for JavaScript localization data
	 *
	 * @param array $loc_data Localization array.
	 */
	public function gdpr_extend_loc_data( $loc_data ) {
		$timestamp         = strtotime( date( 'YmdHis' ) );
		$unique_visitor_id = md5( $timestamp . uniqid( '', true ) );

		$gdpr_default_content = new Moove_GDPR_Content();
		$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
		$gdpr_options         = get_option( $option_name );

		if ( isset( $gdpr_options['moove_gdpr_cc_geo_status'] ) ) :
			// New support - checkbox layout
			$geo_status = intval( $gdpr_options['moove_gdpr_cc_geo_status'] ) === 1;
			$geo_setup 	= isset( $gdpr_options['moove_gdpr_cc_geo_setup'] ) ? json_decode( $gdpr_options['moove_gdpr_cc_geo_setup'], true ) : array();
		else :
			// Legacy support - radio layout
			$geo_setup 	= isset( $gdpr_options['moove_gdpr_geolocation_eu'] ) ? $gdpr_options['moove_gdpr_geolocation_eu'] : '';
			$geo_setup 	= is_array( $geo_setup ) ? $geo_setup : array( intval( $geo_setup ) => 'true' );
			$geo_status = isset( $geo_setup[0] ) ? false : true;
		endif;

		if ( $geo_status ) :

			$geoip_controller = new Moove_GDPR_Addon_GeoIP();
			$ip               = $geoip_controller->get_visitor_ip();
			$sanitized_ip     = md5( $ip );
			$display_banner   = 'true';
			$results 					= false;
			$existing 				= $geoip_controller->get_log_entry( $sanitized_ip );
	
			if ( $existing ) :
				$results = json_decode( $existing, true );
			endif;

			if ( ! $results ) :
				$response_from_api = $geoip_controller->get_details_by_ip( $ip );
				if ( isset( $response_from_api['success'] ) && $response_from_api['success'] && isset( $response_from_api['data'] ) ) :
					unset( $response_from_api['data']['geoplugin_request'] );
					unset( $response_from_api['data']['geoplugin_credit'] );
					$geoip_controller->create_log_entry(
						array(
							'ip_address'		=> $sanitized_ip,
							'option_value'	=> json_encode( $response_from_api['data'] )
						)
					);
					$results = $response_from_api['data'];
				endif;
			endif;

			$show_cookie_banner = false;
			if ( is_array( $geo_setup ) ) :
				$loc_data['geo_debug'] = '';
				foreach ( $geo_setup as $geo_key => $geo_value ) :
					
					switch ( $geo_key ) :
						case 1:
							// EU - Users
							if ( isset( $results['geoplugin_inEU'] ) && intval( $results['geoplugin_inEU'] ) === 1 ) :
								$show_cookie_banner = true;
								$loc_data['geo_debug'] .= '|EU|';
							endif;
							break;
						case 2:
							// Custom Country - Users
							$selected_countries = isset( $gdpr_options['gdpr_geo_countries'] ) ? json_decode( $gdpr_options['gdpr_geo_countries'], true ) : array();
							$selected_countries	= is_array( $selected_countries ) ? $selected_countries : array();
							if ( isset( $results['geoplugin_countryCode'] ) && in_array( $results['geoplugin_countryCode'], $selected_countries ) ) :
								$show_cookie_banner = true;
								$loc_data['geo_debug'] .= '|SELECTED_COUNTRIES|';
							endif;
							break;
						case 3:
							// California US - Users
							if ( isset( $results['geoplugin_regionCode'] ) && strtolower( $results['geoplugin_regionCode'] ) === 'ca' ) :
								$show_cookie_banner = true;
								$loc_data['geo_debug'] .= '|CALIFORNIA|';
							endif;
							break;
						case 4:
							// Canada - Users
							if ( isset( $results['geoplugin_countryName'] ) && strtolower( $results['geoplugin_countryName'] ) === 'canada' ) :
								$show_cookie_banner = true;
								$loc_data['geo_debug'] .= '|CANADA|';
							endif;
							break;
						default:
							$show_cookie_banner = true;
							$loc_data['geo_debug'] .= '|DEFAULT|';
							break;
					endswitch;
				endforeach;
			endif;

			if ( $show_cookie_banner ) :
				$display_banner = 'true';
			else :
				$display_banner = 'false';
				$loc_data['enabled_default'] = array(
					'third_party' => 1,
					'advanced'    => 1,
				);
			endif;

			$loc_data['display_cookie_banner'] = $display_banner;

		endif;

		$consent_version = '1.0';
		if ( isset( $gdpr_options['gdpr_consent_version'] ) && floatval( $gdpr_options['gdpr_consent_version'] ) ) :
			$consent_version = $gdpr_options['gdpr_consent_version'];
		endif;

		$loc_data['gdpr_consent_version'] = floatval( $consent_version );

		$loc_data['gdpr_uvid']     = $unique_visitor_id;
		$loc_data['stats_enabled'] = isset( $gdpr_options['moove_gdpr_premium_analytics_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_premium_analytics_enable'] ) === 1 ? true : false ) : false;

		$loc_data['gdpr_aos_hide'] = 'false';

		$loc_data['consent_log_enabled'] = isset( $gdpr_options['moove_gdpr_consent_log_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_consent_log_enable'] ) === 1 ? true : false ) : false;

		if ( isset( $gdpr_options['moove_gdpr_aos_hide_enable'] ) ) :
			$aos_expval = explode( ',', $gdpr_options['moove_gdpr_aos_hide_enable'] );
			if ( in_array( '1', $aos_expval ) || in_array( '2', $aos_expval ) ) :
				$loc_data['enable_on_scroll'] = 'true';
				$loc_data['gdpr_aos_hide'] 		= $aos_expval;
				if ( in_array('2', $aos_expval ) ) :
					$loc_data['gdpr_aos_hide_seconds'] = isset( $gdpr_options['moove_gdpr_aos_hide_seconds'] ) ? $gdpr_options['moove_gdpr_aos_hide_seconds'] : 30;
				endif;
			endif;			

		else :
			$loc_data['enable_on_scroll'] = 'false';
		endif;


		if ( isset( $gdpr_options['moove_gdpr_modal_enable_ifb'] ) && intval( $gdpr_options['moove_gdpr_modal_enable_ifb'] ) === 1 ) :
			$ifbc_value 					= isset( $gdpr_options['moove_gdpr_ifbc'] ) ? ( $gdpr_options['moove_gdpr_ifbc'] ) : 'strict';
			$third_party_allowed 	= isset( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) === 1;
			$advanced_allowed 		= isset( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) === 1;
			$ifbc_value  					= $ifbc_value === 'thirdparty' && ! $third_party_allowed ? 'strict' : $ifbc_value;
			$ifbc_value  					= $ifbc_value === 'advanced' && ! $advanced_allowed ? 'strict' : $ifbc_value;

			$loc_data['ifbc'] 			= apply_filters( 'gdpr_iframe_blocker_cookie', $ifbc_value );

			$ifbc_excl_value 				= isset( $gdpr_options['gdpr-ifb-exclusion'] ) && $gdpr_options['gdpr-ifb-exclusion'] ? $gdpr_options['gdpr-ifb-exclusion'] : json_encode( array() );
			$loc_data['ifbc_excl'] 	= apply_filters( 'gdpr_iframe_blocker_exclude', $ifbc_excl_value );
		endif;
	return $loc_data;
	}

	/**
	 * Style Extension
	 *
	 * @param string $styles Styles.
	 * @param string $primary Primary colour hash.
	 * @param string $secondary Secondary colour hash.
	 */
	public function gdpr_cc_addon_styles( $styles, $primary, $secondary ) {
		$styles .= '#moove_gdpr_cookie_info_bar.gdpr-full-screen-infobar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content .moove-gdpr-cookie-notice p a {color: ' . $primary . ' !important}';
		return $styles;
	}

	/**
	 * GDPR Settings fields for export
	 */
	public function get_gdpr_setting_field_keys() {
		$setting_fields = array(
			'moove_gdpr_brand_colour',
			'moove_gdpr_cdn_url',
			'moove_gdpr_brand_secondary_colour',
			'moove_gdpr_company_logo',
			'moove_gdpr_logo_position',
			'moove_gdpr_plugin_layout',
			'moove_gdpr_plugin_font_family',
			'moove_gdpr_modal_powered_by_disable',
			'moove_gdpr_modal_enable_scroll',
			'moove_gdpr_infobar_position',
			'moove_gdpr_colour_scheme',
			'moove_gdpr_floating_button_enable',
			'moove_gdpr_floating_button_position',
			'moove_gdpr_floating_button_background_colour',
			'moove_gdpr_floating_button_hover_background_colour',
			'moove_gdpr_floating_button_font_colour',
			'moove_gdpr_strictly_necessary_cookies_functionality',
			'moove_gdpr_third_party_cookies_enable',
			'moove_gdpr_third_party_cookies_enable_first_visit',
			'moove_gdpr_advanced_cookies_enable',
			'moove_gdpr_advanced_cookies_enable_first_visit',
			'moove_gdpr_cookie_policy_enable',
			'moove_gdpr_geolocation_eu',
			'moove_gdpr_premium_analytics_enable',
			'moove_gdpr_dcb',
			'moove_gdpr_modal_enable_ifb',
			'moove_gdpr_aos_hide_enable',
			'moove_gdpr_cd_enable',
			'moove_gdpr_ifbc',
			'moove_gdpr_button_style',
			'moove_gdpr_floating_mobile',
			'gdpr_cookie_declarations_strictly',
			'gdpr_cookie_declarations_thirdparty',
			'gdpr_cookie_declarations_advanced',
			'moove_gdpr_consent_expiration',
			'moove_gdpr_consent_version',
			'moove_gdpr_cc_geo_setup',
			'gdpr_geo_countries'
		);
		return apply_filters( 'gdpr_cc_multisite_setting_fields', $setting_fields );
	}

	/**
	 * Hidden donate section in the premium add-on
	 *
	 * @param string $box_content Box content.
	 */
	public function gdpr_cookie_compliance_donate_section( $box_content ) {
		return '';
	}

	/**
	 * Hidden premium section in the premium add-on
	 *
	 * @param string $box_content Box content.
	 */
	public function gdpr_cookie_compliance_premium_section( $box_content ) {
		return '';
	}

	/**
	 * Filter for FAQ section link in the sidebar widgets
	 *
	 * @param string $link Link.
	 */
	public function gdpr_cookie_compliance_faq_section_link( $link ) {
		return $link;
	}

	/**
	 * Filter for SUPPORT section link in the sidebar widgets
	 *
	 * @param string $link Link.
	 */
	public function gdpr_cookie_compliance_forum_section_link( $link ) {
		return $link;
	}

	/**
	 * Export settings in CSV format
	 */
	public function gdpr_download_export_csv() {
		if ( isset( $_POST ) && isset( $_POST['moove_gdpr_nonce_export'] ) ) :
			wp_verify_nonce( 'ga_nonce', 'gdpr_addon_nonce' );
			$nonce = isset( $_POST['moove_gdpr_nonce_export'] ) ? sanitize_text_field( wp_unslash( $_POST['moove_gdpr_nonce_export'] ) ) : false;
			if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_nonce_field_export' ) ) :
				die( 'Security check' );
			else :
				if ( is_array( $_POST ) ) :
					if ( isset( $_POST['gdpr-export-settings'] ) && 'true' === $_POST['gdpr-export-settings'] ) :
						if ( class_exists( 'Moove_GDPR_Content' ) ) :
							$gdpr_default_content = new Moove_GDPR_Content();
							$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
							$modal_options        = get_option( $option_name );
							$json                 = json_encode( $modal_options, true );
							if ( is_array( $modal_options ) && json_decode( $json, true ) ) :
								$date              = date( 'd/m/Y' );
								$activity_filename = sanitize_title( get_bloginfo( 'name' ) ) . '-' . $date . '-GDPR-settings.txt';

								/** Modify header to be downloadable csv file. */
								header( 'Content-Type: text/plain' );
								header( 'Content-Disposition: attachement; filename="' . $activity_filename . '";' );
								/** Send file to browser for download. */
								apply_filters( 'gdpr_addon_keephtml', $json, true );
								exit;
							endif;
						endif;
					endif;
				endif;
			endif;
		endif;
	}

	/**
	 * Allowing tabs in premium add-on and load the relevant content for tabs
	 *
	 * @param string $tab_data Tab data.
	 * @param string $active_tab Active tab.
	 */
	public function gdpr_settings_tab_content( $tab_data, $active_tab ) {
		$available_tabs = array( 'export-import', 'full-screen-mode', 'multisite-settings', 'stats', 'accept-on-scroll', 'geo-location', 'cookie-banner-manager', 'iframe-blocker', 'cookie-declaration', 'consent-log', 'renew-consent' );
		if ( in_array( $active_tab, $available_tabs, false ) ) :
			$view_cnt = new Moove_GDPR_Addon_View();
			$data     = '';
			$tab_data = $view_cnt->load( 'moove.admin.settings.' . $active_tab, $data );
		endif;
		return $tab_data;
	}

	/**
	 * Premium navigation extension
	 *
	 * @param string $active_tab Active tab.
	 */
	public function gdpr_settings_tab_nav_extensions( $active_tab ) {
		?>
		<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=export-import" class="gdpr-cc-addon nav-tab <?php echo 'export-import' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Export/Import Settings', 'gdpr-cookie-compliance-addon' ); ?>
		</a>

		<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=multisite-settings" class="gdpr-cc-addon nav-tab <?php echo 'multisite-settings' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Multisite Settings', 'gdpr-cookie-compliance-addon' ); ?>
		</a>
		
		<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=accept-on-scroll" class="gdpr-cc-addon nav-tab <?php echo 'accept-on-scroll' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Accept on Scroll / Hide timer', 'gdpr-cookie-compliance-addon' ); ?>
		</a>

		<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=full-screen-mode" class="gdpr-cc-addon nav-tab <?php echo 'full-screen-mode' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Full-screen / Cookiewall', 'gdpr-cookie-compliance-addon' ); ?>
		</a>

		<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=stats" class="gdpr-cc-addon nav-tab <?php echo 'stats' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Analytics', 'gdpr-cookie-compliance-addon' ); ?>
		</a>

		<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=geo-location" class="gdpr-cc-addon nav-tab <?php echo 'geo-location' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Geo Location', 'gdpr-cookie-compliance-addon' ); ?>
		</a>

		<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=cookie-banner-manager" class="gdpr-cc-addon nav-tab <?php echo 'cookie-banner-manager' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Hide Cookie Banner', 'gdpr-cookie-compliance-addon' ); ?>
		</a>

		<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=iframe-blocker" class="gdpr-cc-addon nav-tab <?php echo 'iframe-blocker' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Iframe Blocker', 'gdpr-cookie-compliance-addon' ); ?>
		</a>

		<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=cookie-declaration" class="gdpr-cc-addon nav-tab <?php echo 'cookie-declaration' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Cookie Declaration', 'gdpr-cookie-compliance-addon' ); ?>
		</a>

		<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=consent-log" class="gdpr-cc-addon nav-tab <?php echo 'consent-log' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Consent Log', 'gdpr-cookie-compliance-addon' ); ?>
		</a>

		<a href="<?php echo admin_url( 'admin.php' ); ?>?page=moove-gdpr&amp;tab=renew-consent" class="gdpr-cc-addon nav-tab <?php echo 'renew-consent' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Renew Consent', 'gdpr-cookie-compliance-addon' ); ?>
		</a>

		<style>
			#moove_form_checker_wrap .nav-tab-wrapper a[href*="export-import"].nav-tab.gdpr-cc-addon.gdpr-cc-disabled,
			#moove_form_checker_wrap .nav-tab-wrapper a[href*="multisite-settings"].nav-tab.gdpr-cc-addon.gdpr-cc-disabled,
			#moove_form_checker_wrap .nav-tab-wrapper a[href*="accept-on-scroll"].nav-tab.gdpr-cc-addon.gdpr-cc-disabled,
			#moove_form_checker_wrap .nav-tab-wrapper a[href*="full-screen-mode"].nav-tab.gdpr-cc-addon.gdpr-cc-disabled,
			#moove_form_checker_wrap .nav-tab-wrapper a[href*="stats"].nav-tab.gdpr-cc-addon.gdpr-cc-disabled,
			#moove_form_checker_wrap .nav-tab-wrapper a[href*="geo-location"].nav-tab.gdpr-cc-addon.gdpr-cc-disabled,
			#moove_form_checker_wrap .nav-tab-wrapper a[href*="cookie-banner-manager"].nav-tab.gdpr-cc-addon.gdpr-cc-disabled,
			#moove_form_checker_wrap .nav-tab-wrapper a[href*="iframe-blocker"].nav-tab.gdpr-cc-addon.gdpr-cc-disabled,
			#moove_form_checker_wrap .nav-tab-wrapper a[href*="cookie-declaration"].nav-tab.gdpr-cc-addon.gdpr-cc-disabled,
			#moove_form_checker_wrap .nav-tab-wrapper a[href*="consent-log"].nav-tab.gdpr-cc-addon.gdpr-cc-disabled,
			#moove_form_checker_wrap .nav-tab-wrapper a[href*="renew-consent"].nav-tab.gdpr-cc-addon.gdpr-cc-disabled {
				display: none;
			}
		</style>
		<?php
	}

	/**
	 * Registe FRONT-END Javascripts and Styles
	 *
	 * @return void
	 */
	public function gdpr_addon_frontend_scripts() {
		wp_enqueue_script( 'gdpr_cc_addon_frontend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/js/gdpr_cc_addon.js', array( 'moove_gdpr_frontend' ), GDPR_ADDON_VERSION, true );
		wp_enqueue_style( 'gdpr_cc_addon_frontend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/css/gdpr_cc_addon.css', '', GDPR_ADDON_VERSION );

	}
	/**
	 * Registe BACK-END Javascripts and Styles
	 *
	 * @return void
	 */
	public function gdpr_addon_admin_scripts() {
		wp_enqueue_script( 'gdpr_cc_addon_admin', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/js/gdpr_cc_addon_admin.js', array( 'jquery' ), GDPR_ADDON_VERSION, true );
		wp_enqueue_style( 'gdpr_cc_addon_admin', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/css/gdpr_cc_addon_admin.css', '', GDPR_ADDON_VERSION );
	}

	/**
	 * Manage MultiSite settings globally if it's enabled
	 */
	public function gdpr_cookie_filter_settings() {
		if ( class_exists( 'Moove_GDPR_Content' ) ) :
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$gdpr_options         = get_option( $option_name );
			$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
			if ( isset( $gdpr_options['moove_gdpr_full_screen_enable'] ) && intval( $gdpr_options['moove_gdpr_full_screen_enable'] ) === 1 ) :
				if ( isset( $gdpr_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) ) :
					$gdpr_options['moove_gdpr_strictly_necessary_cookies_functionality'] = intval( $gdpr_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) === 1 ? '2' : $gdpr_options['moove_gdpr_strictly_necessary_cookies_functionality'];
				else :
					$gdpr_options['moove_gdpr_strictly_necessary_cookies_functionality'] = '2';
				endif;
				$gdpr_options['moove_gdpr_infobar_visibility'] = 'visible';

			endif;
			update_option( $option_name, $gdpr_options );

			if ( is_multisite() ) :
				$gdpr_options = get_option( $option_name );

				if ( isset( $gdpr_options['moove_gdpr_manage_settings_globally'] ) && intval( $gdpr_options['moove_gdpr_manage_settings_globally'] ) === 1 ) :
					$args  = array(
						'fields' => 'ids',
					);
					$sites = get_sites( $args );
					if ( $sites && is_array( $sites ) ) :
						$current_blog_settings = get_option( $option_name );
						$setting_actions       = new Moove_GDPR_Addon_Actions();
						$setting_fields        = $setting_actions->get_gdpr_setting_field_keys();
						if ( $setting_fields && is_array( $setting_fields ) ) :
							foreach ( $sites as $blog_id ) :
								switch_to_blog( $blog_id );
								$gdpr_options = get_option( $option_name );
								foreach ( $setting_fields as $field_key ) :
									if ( isset( $current_blog_settings[ $field_key ] ) ) :
										$gdpr_options[ $field_key ] = $current_blog_settings[ $field_key ];
									endif;
								endforeach;
								update_option( $option_name, $gdpr_options );
								restore_current_blog();
							endforeach;
						endif;
				endif;
			endif;
		endif;
	endif;
	}

	/**
	 * Removing buttons from full screen info bar content
	 *
	 * @param string $content Content.
	 */
	public function gdpr_info_bar_notice_content( $content ) {
		if ( class_exists( 'Moove_GDPR_Content' ) ) :
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$gdpr_options         = get_option( $option_name );
		endif;
		return $content;
	}

	/**
	 * Info bar filters and button extension if full screen is supported
	 */
	public function gdpr_info_bar_button_extensions() {
		if ( class_exists( 'Moove_GDPR_Content' ) ) :
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$gdpr_options         = get_option( $option_name );
			$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
			if ( isset( $gdpr_options['moove_gdpr_full_screen_enable'] ) && intval( $gdpr_options['moove_gdpr_full_screen_enable'] ) === 1 ) :
				$_content       = '<p>' . __( 'We are using cookies to give you the best experience on our website.', 'gdpr-cookie-compliance-addon' ) . '</p>' .
				'<p>' . __( 'You can find out more about which cookies we are using or switch them off in [setting]settings[/setting].', 'gdpr-cookie-compliance-addon' ) . '</p>';
				$content        = isset( $gdpr_options[ 'moove_gdpr_info_bar_content' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_info_bar_content' . $wpml_lang ] ? $gdpr_options[ 'moove_gdpr_info_bar_content' . $wpml_lang ] : $_content;
				$content        = str_replace( '[setting]', '<setting>', $content );
				$content        = str_replace( '[/setting]', '</setting>', $content );
				$content        = preg_match_all( '/<setting>(.*?)<\/setting>/s', $content, $matches );
				$settings_label = isset( $matches[1][0] ) && $matches[1][0] ? $matches[1][0] : __( 'settings', 'gdpr-cookie-compliance-addon' );
				$settings_button = '<button data-href="#moove_gdpr_cookie_modal" class="mgbutton change-settings-button">' . esc_attr( ucfirst( $settings_label ) ) . '</button>';
				if ( ! isset( $gdpr_options['moove_gdpr_settings_button_enable'] ) ) :
					echo apply_filters( 'gdpr_fs_settings_button', $settings_button );
				endif;
			endif;
		endif;
	}

	/**
	 * Full-screen info bar support
	 *
	 * @param array $classes Classes.
	 */
	public function gdpr_info_bar_class_extension( $classes ) {
		if ( class_exists( 'Moove_GDPR_Content' ) ) :
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$gdpr_options         = get_option( $option_name );
			if ( isset( $gdpr_options['moove_gdpr_full_screen_enable'] ) && intval( $gdpr_options['moove_gdpr_full_screen_enable'] ) === 1 ) :
				$classes[] = 'gdpr-full-screen-infobar';
		endif;
	endif;
		return $classes;
	}

	/**
	 * Register AJAX actions for the plugin
	 */
	public function gdpr_addon_register_ajax_actions() {
		add_action( 'wp_ajax_moove_gdpr_premium_save_analytics', array( 'Moove_GDPR_Addon_Analytics', 'moove_gdpr_premium_save_analytics' ) );
		add_action( 'wp_ajax_nopriv_moove_gdpr_premium_save_analytics', array( 'Moove_GDPR_Addon_Analytics', 'moove_gdpr_premium_save_analytics' ) );

		add_action( 'wp_ajax_gdpr_iframe_blocker', array( &$this, 'moove_gdpr_iframe_blocked' ) );
		add_action( 'wp_ajax_nopriv_gdpr_iframe_blocker', array( &$this, 'moove_gdpr_iframe_blocked' ) );

		add_action( 'wp_ajax_save_consent_log', array( &$this, 'moove_gdpr_save_consent_log' ) );
		add_action( 'wp_ajax_nopriv_save_consent_log', array( &$this, 'moove_gdpr_save_consent_log' ) );

		add_action( 'wp_ajax_gdpr_delete_cc_single_log_entry', array( &$this, 'gdpr_delete_cc_single_log_entry' ) );
	}

	public static function gdpr_delete_cc_single_log_entry() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_key( wp_unslash( $_POST['nonce'] ) ) : false;
		if ( wp_verify_nonce( $nonce, 'cc_single_delete_nonce' ) ) :
			$log_entry_id = isset( $_POST['entry_id'] ) && intval( $_POST['entry_id'] ) ? intval( $_POST['entry_id'] ) : false;
			$consent_cnt 	= new Moove_GDPR_Consent_Log();
			$result 			= $consent_cnt->delete_log_entry( $log_entry_id );
			echo json_encode( array( 'success' => true, 'nonce' => $nonce, 'log_entry_id' => $log_entry_id, 'result' => $result ) );
			die();
		endif;
		echo json_encode( array( 'success' => false ) );
		die();
	}

	/**
	 * Iframe blocker content
	 */
	public function moove_gdpr_iframe_blocked() {
		wp_verify_nonce( 'ga_nonce', 'gdpr_addon_nonce' );
		$url = isset( $_GET['src'] ) ? urldecode( sanitize_text_field( wp_unslash( $_GET['src'] ) ) ) : '';
		if ( $url ) :
			?>
			<!DOCTYPE html>
			<html>
			<head>
				<style>
					html * {
						box-sizing: border-box;
					}
					html, body {
						background-color: #f1f1f1;
						height: 100%;
						font-family: 'Nunito';
						margin: 0;
					}
					.gdpr-blocked-iframe {
						padding: 30px;
						height: 100%;		
						min-height: 400px;		  		
					}
					.gdpr-blocked-iframe .gdpr_cookie_settings_shortcode_content {
						height: 100%;
						width: 100%;
						margin: 0;
						display: -webkit-box; 
						display: -moz-box;
						display: -ms-flexbox;
						display: -webkit-flex; 
						display: flex;
						-webkit-box-ordinal-group: 1;
						-moz-box-ordinal-group: 1;
						-ms-flex-order: 1; 
						-webkit-order: 1; 
						order: 1;
						-webkit-box-align: center;
						-moz-box-align: center;
						-ms-flex-align: center;
						-webkit-align-items: center;
						align-items: center;
						-webkit-box-pack: center;
						-ms-flex-pack: center;
						justify-content: center;
						padding-top: 80px;
						position: relative;
						background-color: #fff
					}
					.gdpr-flex-cnt {
						text-align: center;
						
						position: relative;
					}
					.gdpr-blocked-iframe svg {
						max-width: 50px;
						height: auto;
					}
					.gdpr-blocked-iframe h2 {
						text-transform: uppercase;
						font-size: 20px;
					}
					.gdpr-blocked-iframe p {
						margin-bottom: 20px;
					}
					.ifb_button_cnt {
						padding-top: 10px;
					}
					.gdpr-block-heading {
						position: absolute;
						top: 0;
						left: 0;
						height: 80px;
						right: 0;
						background-size: 60px 60px;
						background-position: center;
						background-color: #002244;
						background-repeat: no-repeat;
					}
				</style>
				<link rel="stylesheet" href="<?php echo esc_html( moove_gdpr_get_plugin_directory_url() ) . 'dist/styles/gdpr-main.css'; ?>" type="text/css" media="all">
				<link rel="stylesheet" href="<?php echo esc_html( plugins_url( basename( dirname( __FILE__ ) ) ) ) . '/assets/css/gdpr_cc_addon.css'; ?>" type="text/css" media="all">
				<style>
					<?php apply_filters( 'gdpr_addon_keephtml', gdpr_get_module( 'branding-styles' ), true ); ?>
				</style>
			</head>
			<body>
				<div class="gdpr-blocked-iframe">
					<div class="gdpr_cookie_settings_shortcode_content">
						<span class="gdpr-block-heading" style="background-image: url(<?php echo esc_html( plugins_url( basename( dirname( __FILE__ ) ) ) ) . '/assets/images/gdpr_lock_sign.png'; ?>)"></span>
						<div class="gdpr-flex-cnt">			  								  			
							<?php
							$gdpr_default_content = new Moove_GDPR_Content();
							$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
							$modal_options        = get_option( $option_name );
							$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
							$content              = isset( $modal_options[ 'moove_gdpr_ifb_content' . $wpml_lang ] ) && $modal_options[ 'moove_gdpr_ifb_content' . $wpml_lang ] ? $modal_options[ 'moove_gdpr_ifb_content' . $wpml_lang ] : $_content;
							$content              = str_replace( '[setting]', '<a href="#" id="gdpr_open_settings" class="gdpr-shr-button button-green" >', $content );
							$content              = str_replace( '[/setting]', '</a>', $content );

							$content = str_replace( '[accept]', '<a href="#" id="gdpr_accept_cookies" class="gdpr-shr-button button-green" >', $content );
							$content = str_replace( '[/accept]', '</a>', $content );
							$content = apply_filters( 'gdpr_iframe_blocker_content', $content );
							apply_filters( 'gdpr_addon_keephtml', wpautop( $content ), true );
							?>

						</div>
						<!--  .gdpr-flex-cnt -->
					</div>
					<!--  .gdpr_cookie_settings_shortcode_content -->
				</div>
				<!--  .gdpr-blocked-iframe -->
				<script>
					var setting_button = document.getElementById("gdpr_open_settings");
					if ( setting_button ) {
						setting_button.addEventListener("click", function(event){
							event.preventDefault();
							if ( typeof window.parent.document.querySelectorAll('[data-href="#moove_gdpr_cookie_modal"]')[0] !== 'undefined' ) {
								window.parent.document.querySelectorAll('[data-href="#moove_gdpr_cookie_modal"]')[0].click();
							}
						});
					}

					var accept_button = document.getElementById("gdpr_accept_cookies");
					if ( accept_button ) {
						accept_button.addEventListener("click", function(event){
							event.preventDefault();
							if ( typeof window.parent.document.querySelectorAll('.moove-gdpr-infobar-allow-all')[0] !== 'undefined' ) {
								window.parent.document.querySelectorAll('.moove-gdpr-infobar-allow-all')[0].click();
							}
						});
					}
				</script>
			</body>
			</html>
			<?php
		endif;
		die();
	}

	public function moove_gdpr_save_consent_log() {
		$value 		= isset( $_POST['extras'] ) ? sanitize_text_field( wp_unslash( $_POST['extras'] ) ) : false;
		$cookies 	= $value ? json_decode( $value, true ) : array();
		$dbdata 	= array();
		if ( $cookies && is_array( $cookies ) ) :
			$strict 		= isset( $cookies['strict'] ) && intval( $cookies['strict'] ) ? '1' : '0';
			$thirdparty = isset( $cookies['thirdparty'] ) && intval( $cookies['thirdparty'] ) ? '1' : '0' ;
			$advanced 	= isset( $cookies['advanced'] ) && intval( $cookies['advanced'] ) ? '1' : '0' ;
			if ( isset( $cookies['strict'] ) && isset( $cookies['thirdparty'] ) && isset( $cookies['thirdparty'] ) ) :
				// save cookie entry
				$geoip_controller 	= new Moove_GDPR_Addon_GeoIP();
				$ip               	= $geoip_controller->get_visitor_ip( false );

				$gdpr_default_content = new Moove_GDPR_Content();
				$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
				$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
				$gdpr_options         = get_option( $option_name );

				$_cookies_stored 						= array();
				$_cookies_stored['strict']	= $strict;

				if ( isset( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_third_party_cookies_enable'] ) === 1 ) :
					$_cookies_stored['thirdparty']	= $thirdparty;
				endif;
				if ( isset( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $gdpr_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ) :
					$_cookies_stored['advanced']		= $advanced;
				endif;


				$dbdata 						= array(
					'ip_address'			=> $ip,
					'option_value'		=> json_encode( $_cookies_stored ),
					'log_date'				=> strtotime('now'),
					'user_id'					=> get_current_user_id(),
					'extras'					=> ''
				);

				$consent_controller = new Moove_GDPR_Consent_Log();
				$results 						= $consent_controller->create_log_entry( $dbdata );

			endif;

		endif;
		die();
	}
}
$Moove_GDPR_Addon_Actions = new Moove_GDPR_Addon_Actions();

