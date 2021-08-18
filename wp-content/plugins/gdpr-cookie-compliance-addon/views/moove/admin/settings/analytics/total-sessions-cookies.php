<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php if ( isset( $data['total_sessions_cookies'] ) && isset( $data['total_accepted'] ) ) : ?>
	<div class="stat-cnt">
		<div class="table_name">
			<h4><?php esc_html_e( 'Sessions with cookies accepted', 'gdpr-cookie-compliance-addon' ); ?>:</h4>	                 
		</div>
		
		<div class="table_content">
			<p><?php echo esc_attr( $data['total_accepted'] ); ?></p>
		</div>

		<p class="description"><?php esc_html_e( 'This is the number of sessions from users who accepted at least one of the cookies (either the Strictly Necessary, or more).', 'gdpr-cookie-compliance-addon' ); ?></p>  
	</div>
	<!--  .stat-cnt -->
<?php endif; ?>
