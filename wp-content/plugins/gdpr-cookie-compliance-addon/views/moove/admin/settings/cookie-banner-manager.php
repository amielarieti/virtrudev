<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php
	$cbm_type = isset( $_GET['cbm-type'] ) ? sanitize_text_field( wp_unslash( $_GET['cbm-type'] ) ) : false;
	$cbm_type = $cbm_type && ( $cbm_type === 'post_type' || $cbm_type === 'users' ) ? esc_attr( $cbm_type ) : 'post_type';

	$view_cnt = new Moove_GDPR_Addon_View();
	$content 	= $view_cnt->load( 'moove.admin.settings.cookie-banner-manager-' . $cbm_type, array() );
	apply_filters( 'gdpr_addon_keephtml', $content, true );
?>