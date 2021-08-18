<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php if ( isset( $data['total_sessions_cookies'] ) && isset( $data['total_accepted'] ) ) :
	$current    = $data['total_sessions_cookies'] - $data['total_accepted'];
	$percentage = 0 !== $current && 0 !== $data['total_sessions_cookies'] ? ( $data['total_accepted'] * 100 ) / $data['total_sessions_cookies'] : 0;
	?>
	<div class="stat-cnt">
		<div class="table_name">
			<h4><?php esc_html_e( 'Percentage of sessions with cookies accepted', 'gdpr-cookie-compliance-addon' ); ?>:</h4>	                 
		</div>
		
		<div class="table_content">
			<p><?php echo 0 === $percentage ? esc_attr( $percentage ) : sprintf( '%0.2f', esc_attr( $percentage ) ); ?>%</p>
		</div>
	</div>
	<!--  .stat-cnt -->
<?php endif; ?>
