<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly

	$view_controller      = new GDPR_Modules_View();
	$modal_options        = $data['gdpr_options'];
	$wpml_lang            = $data['wpml_lang'];
	$gdpr_default_content = new Moove_GDPR_Content();

	$tab_title = isset( $modal_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ) && $modal_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] ? $modal_options[ 'moove_gdpr_advanced_cookies_tab_title' . $wpml_lang ] : __( 'Additional Cookies', 'gdpr-cookie-compliance' );
	$show      = isset( $modal_options['moove_gdpr_advanced_cookies_enable'] ) && intval( $modal_options['moove_gdpr_advanced_cookies_enable'] ) === 1 ? true : false;
	$strictly  = isset( $modal_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) && intval( $modal_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) ? intval( $modal_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) : 1;
?>
<?php if ( $show ) : ?>
  <div class="gdpr-shortcode-module" data-target="advanced-cookies">
	<div class="gdpr-shr-checkbox-cnt ">           
	  <label class="gdpr-shr-switch">
		<input type="checkbox" data-name="moove_gdpr_advanced_cookies" >
		<span class="gdpr-shr-slider gdpr-shr-round"></span>
	  </label>
	</div>
	<!--  .gdpr-shr-checkbox-cnt -->
	
	<div class="gdpr-shr-title-section">
	  <p><?php echo esc_attr( $tab_title ); ?></p>
	</div>
	<!--  .gdpr-shr-title-section -->        
	  
  </div>
  <!-- .gdpr-shortcode-module -->
<?php endif; ?>
