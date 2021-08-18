<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php if ( isset( $data['infobar_showed'] ) ) : ?>
	<div class="stat-cnt">
	    <div class="table_name">
	        <h4><?php _e('Cookie Banner Showed','gdpr-cookie-compliance-addon'); ?>:</h4>
	    </div>

	    <div class="table_content">
	        <p><?php echo $data['infobar_showed']; ?></p>
	    </div>
	    <p class="description"><p class="description">* <?php _e('Only the first visit from the session will increase this number (if the infobar is visible, if the cookies was accepted before, only the accepted cookies will be increased)','gdpr-cookie-compliance-addon'); ?>. </p> 
	</div>
	<!--  .stat-cnt -->
<?php endif; ?>