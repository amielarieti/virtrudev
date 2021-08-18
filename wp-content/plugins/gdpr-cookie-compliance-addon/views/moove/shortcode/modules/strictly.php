<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly

	$view_controller      = new GDPR_Modules_View();
	$modal_options        = $data['gdpr_options'];
	$wpml_lang            = $data['wpml_lang'];
	$gdpr_default_content = new Moove_GDPR_Content();
	$strictly             = isset( $modal_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) && intval( $modal_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) ? intval( $modal_options['moove_gdpr_strictly_necessary_cookies_functionality'] ) : 1;
	$tab_title            = isset( $modal_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ) && $modal_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] ? $modal_options[ 'moove_gdpr_strictly_necessary_cookies_tab_title' . $wpml_lang ] : __( 'Strictly Necessary Cookies', 'gdpr-cookie-compliance' );
	$show                 = $strictly !== 3;
	$is_checked           = $strictly !== 1 ? 'disabled checked="checked" ' : '';
?>
<?php if ( $show ) : ?>
  <div class="gdpr-shortcode-module" data-target="strict-necessary-cookies">
	<div class="gdpr-shr-checkbox-cnt">           
	  <label class="gdpr-shr-switch">
		<input type="checkbox" <?php echo esc_attr( $is_checked ); ?> data-name="moove_gdpr_strict_cookies">
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
