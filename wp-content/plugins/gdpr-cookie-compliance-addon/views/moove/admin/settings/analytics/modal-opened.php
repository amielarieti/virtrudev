<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php if ( isset( $data['modal_opened'] ) ) : ?>
	<div class="stat-cnt">
		<div class="table_name">
			<h4><?php esc_html_e( 'Cookie Settings Screen Opened', 'gdpr-cookie-compliance-addon' ); ?>:</h4>       
		</div>
		<div class="table_content">
			<p><?php echo esc_attr( $data['modal_opened'] ); ?></p>
		</div>
		<p class="description"><?php esc_html_e( 'Number of sessions where the Cookie Settings screen was viewed by a user. Triggers: User clicking on the Floating Button, Settings link clicked from the Cookie Banner, or user clicking on a custom link (#gdpr_cookie_modal).', 'gdpr-cookie-compliance-addon' ); ?></p>  
	</div>
	<!--  .stat-cnt -->
<?php endif; ?>
