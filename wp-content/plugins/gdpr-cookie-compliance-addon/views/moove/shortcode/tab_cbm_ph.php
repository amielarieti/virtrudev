<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>
<p><strong>Note: Some of them require some development work on your end to be extended or modified properly! These are just examples.</strong></p>

<div class="gdpr-faq-toggle gdpr-faq-open">
	<div class="gdpr-faq-accordion-header">
		<h3>Filter to disable the iframes in your custom sections</h3>
	</div>
	<div class="gdpr-faq-accordion-content">
		<?php ob_start(); ?>
		$content = apply_filters( 'gdpr_iframe_blocker_filter', $content );
		<?php $code = trim( ob_get_clean() ); ?>
		<textarea id="<?php echo esc_attr( uniqid( strtotime( 'now' ) ) ); ?>"><?php apply_filters( 'gdpr_addon_keephtml', $code, true ); ?></textarea>
		<div class="gdpr-code">			
		</div>
		<!--  .gdpr-code -->
	</div>
	<!--  .gdpr-faq-accordion-content -->
</div>
<!--  .gdpr-faq-toggle -->

<div class="gdpr-faq-toggle">
	<div class="gdpr-faq-accordion-header">
		<h3>Filter to update / replace the settings button in full screen mode</h3>
	</div>
	<div class="gdpr-faq-accordion-content">
		<p><strong>Note:</strong> If you remove the settings button, and replace the link to internal page (like cookie policy) you should <strong><a href="<?php echo esc_url( admin_url( 'admin.php?page=moove-gdpr&tab=cookie-banner-manager' ) ); ?>" class="gdpr_admin_link">hide the cookie banner</a></strong> on the selected page.</p>
		<hr />
		<?php ob_start(); ?>
		add_action( 'gdpr_fs_settings_button', 'gdpr_fs_settings_button' );
		function gdpr_fs_settings_button( $button ) {
			$link 	= '/privacy-policy/';
			$label 	= 'Find Out More';
			return "<a href='$link' class='mgbutton mright mgrey'>$label</a>";
		}
		<?php $code = trim( ob_get_clean() ); ?>
		<textarea id="<?php echo esc_attr( uniqid( strtotime( 'now' ) ) ); ?>"><?php apply_filters( 'gdpr_addon_keephtml', $code, true ); ?></textarea>
		<div class="gdpr-code">			
		</div>
		<!--  .gdpr-code -->
	</div>
	<!--  .gdpr-faq-accordion-content -->
</div>
<!--  .gdpr-faq-toggle -->


<div class="gdpr-faq-toggle">
	<div class="gdpr-faq-accordion-header">
		<h3>Consent log default interval</h3>
	</div>
	<div class="gdpr-faq-accordion-content">
		<p><strong>Default value:</strong> 1 week ago <br>For more information about interval definitions click <a href="https://www.php.net/manual/en/function.strtotime.php" target="_blank">here</a></p>
		<hr />
		<?php ob_start(); ?>
		add_action( 'gdpr_consent_log_start_interval', 'gdpr_cl_interval_one_month' );
		function gdpr_cl_interval_one_month( $interval ) {
			$interval = '1 month ago';
			return $interval;
		}
		<?php $code = trim( ob_get_clean() ); ?>
		<textarea id="<?php echo esc_attr( uniqid( strtotime( 'now' ) ) ); ?>"><?php apply_filters( 'gdpr_addon_keephtml', $code, true ); ?></textarea>
		<div class="gdpr-code">			
		</div>
		<!--  .gdpr-code -->
	</div>
	<!--  .gdpr-faq-accordion-content -->
</div>
<!--  .gdpr-faq-toggle -->



