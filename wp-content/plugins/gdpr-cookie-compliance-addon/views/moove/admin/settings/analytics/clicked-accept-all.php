<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php if ( isset( $data['accept_all'] ) ) : ?>
	<div class="stat-cnt">
		<div class="table_name">
			<h4><?php esc_html_e( 'Clicked "Accept all" button', 'gdpr-cookie-compliance-addon' ); ?>:</h4>       
		</div>
		<div class="table_content">
			<p><?php echo esc_attr( $data['accept_all'] ); ?></p>
		</div>
		<p class="description"><?php esc_html_e( 'This statistic shows the number of users who clicked on the "Accept" button on the Cookie Banner or who clicked on the “Enable all" button inside the Cookie Settings Screen.', 'gdpr-cookie-compliance-addon' ); ?></p>  
	</div>
	<!--  .stat-cnt -->
<?php endif; ?>
