<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php if ( isset( $data['total_sessions'] ) ) : ?>
	<div class="stat-cnt">
		<div class="table_name">
			<h4><?php esc_html_e( 'Sessions tracked', 'gdpr-cookie-compliance-addon' ); ?>:</h4>	                 
		</div>

		<div class="table_content">
			<p><?php echo esc_attr( $data['total_sessions'] ); ?></p>
		</div>
		<p class="description"><?php esc_html_e( 'Each session begins when a user visits your site, and terminates once the browser tab is closed. Repeated user visits will increase your session numbers.', 'gdpr-cookie-compliance-addon' ); ?></p>
	</div>
	<!--  .stat-cnt -->
<?php endif; ?>
