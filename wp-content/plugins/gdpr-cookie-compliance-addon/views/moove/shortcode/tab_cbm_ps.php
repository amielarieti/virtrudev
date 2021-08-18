<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<h4><?php esc_html_e( 'Use the following shortcodes to allow users to turn cookies on and off', 'gdpr-cookie-compliance-addon' ); ?></h4>
<div class="gdpr-faq-toggle gdpr-faq-open">
	<div class="gdpr-faq-accordion-header">
		<h3>Simple shortcode - to turn cookies on / off</h3>
	</div>
	<div class="gdpr-faq-accordion-content">
		<h4>Simple shortcode just for switchers and save button:</h4>
		<?php ob_start(); ?>
		[gdpr_cookie_settings_content]
		<?php $code = trim( ob_get_clean() ); ?>
		<textarea id="<?php echo esc_attr( uniqid( strtotime( 'now' ) ) ); ?>"><?php apply_filters( 'gdpr_addon_keephtml', $code, true ); ?></textarea>
		<div class="gdpr-code">
			
		</div>
		<!--  .gdpr-code -->
		<p><strong>Front-end preview:</strong></p>
		<img src="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/images/example-simple.png?cache=<?php echo esc_attr( strtotime( 'now' ) ); ?>" alt="Simple shortcode" style="max-width: 100%; height: auto;" />

	</div>
	<!--  .gdpr-faq-accordion-content -->
</div>
<!--  .gdpr-faq-toggle -->

<div class="gdpr-faq-toggle">
	<div class="gdpr-faq-accordion-header">
		<h3>Advanced shortcode with additional options - to turn cookies on / off</h3>
	</div>
	<div class="gdpr-faq-accordion-content">
		<?php ob_start(); ?>
		[gdpr_cookie_settings_content title='Cookie Settings' content='You can adjust your cookie preferences below.' save_button_label='Save' settings_button_label='More information']
		<?php $code = trim( ob_get_clean() ); ?>
		<textarea id="<?php echo esc_attr( uniqid( strtotime( 'now' ) ) ); ?>"><?php apply_filters( 'gdpr_addon_keephtml', $code, true ); ?></textarea>
		<div class="gdpr-code">
			
		</div>
		<!--  .gdpr-code -->
		<p><strong>Front-end extended preview:</strong></p>
		<img src="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/images/example-extended.png?cache=<?php echo esc_attr( strtotime( 'now' ) ); ?>" alt="Advanced shortcode with additional options" style="max-width: 100%; height: auto;" />
	</div>
	<!--  .gdpr-faq-accordion-content -->
</div>
<!--  .gdpr-faq-toggle -->


<div class="gdpr-faq-toggle">
	<div class="gdpr-faq-accordion-header">
		<h3>Advanced shortcode to block iframes on custom sections</h3>
	</div>
	<div class="gdpr-faq-accordion-content" >
		<p>You can use the following shortcode to block the iframe outside of default WYSIWYG editors:</p>
		<code>
			[gdpr_iframe_blocker]
				Put iframe or URL here
			[/gdpr_iframe_blocker]
		</code>
		<br />
	</div>
	<!--  .gdpr-faq-accordion-content -->
</div>
<!--  .gdpr-faq-toggle -->
