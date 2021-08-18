<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<?php
	
	$gdpr_default_content = new Moove_GDPR_Content();
	$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
	$gdpr_options         = get_option( $option_name );
	$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
	$gdpr_options         = is_array( $gdpr_options ) ? $gdpr_options : array();
	if ( isset( $_POST ) && isset( $_POST['moove_gdpr_nonce'] ) ) :
		$nonce = sanitize_key( $_POST['moove_gdpr_nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_nonce_field' ) ) :
			die( 'Security check' );
		else :
			if ( isset( $_POST['moove_gdpr_dcb'] ) && is_array( $_POST['moove_gdpr_dcb'] ) ) :
				$value = array_map( 'sanitize_text_field', wp_unslash( $_POST['moove_gdpr_dcb'] ) );
			else :
				$value = array();
			endif;
			$gdpr_options['moove_gdpr_dcb'] = $value;
			update_option( $option_name, $gdpr_options );
			$gdpr_options = get_option( $option_name );

			do_action( 'gdpr_cookie_filter_settings' );
			?>
				<script>
					jQuery('#moove-gdpr-setting-error-settings_updated').show();
				</script>
			<?php
		endif;
	endif;
?>
<form action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=cookie-banner-manager&cbm-type=post_type" method="post" id="moove_gdpr_tab_fsm_settings">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<h2><?php esc_html_e( 'Hide Cookie Banner', 'gdpr-cookie-compliance-addon' ); ?></h2>
	<hr />
	<?php
		$args     = array(
			'public' => true,
		);
		$output   = 'all';
		$operator = 'and';

		$post_types = get_post_types( $args, $output, $operator );
		if ( isset( $post_types['attachment'] ) ) :
			unset( $post_types['attachment'] );
		endif;
		if ( isset( $post_types['revision'] ) ) :
			unset( $post_types['revision'] );
		endif;
		if ( isset( $post_types['nav_menu_item'] ) ) :
			unset( $post_types['nav_menu_item'] );
		endif;



		if ( isset( $post_types['page'] ) ) :
			$_pages = $post_types['page'];
			unset( $post_types['page'] );
			$post_types = array( 'page' => $_pages ) + $post_types;
		endif;

		$table_content     = '';
		$table_nav_content = '';
		$count             = 0;
		if ( $post_types && is_array( $post_types ) ) :
			foreach ( $post_types as $_post_type => $post_type_details ) :
				$count++;
				ob_start();
				?>
				<table class="form-table gdpr-disable-cookie-banner-form <?php echo 1 === $count ? 'form-active' : ''; ?>" id="gdpr_cbm_<?php echo esc_attr( $_post_type ); ?>">
					<tbody>
						<tr >
							<th colspan="2">
								<h3><?php echo esc_attr( $post_type_details->label ); ?></h3>
							</th>
						</tr>    
						<!-- IDE JON A NAVIGATION -->
						<?php
						$args         = array(
							'post_type'      => $_post_type,
							'posts_per_page' => -1,
							'orderby'        => 'title',
							'order'          => 'ASC',
							'post_status'    => 'publish',
						);
						$post_objects = new WP_Query( $args );

						if ( $post_objects->have_posts() ) :
							$nav_active         = 1 === $count ? 'active' : '';
							$table_nav_content .= "<li><a href='#gdpr_cbm_$_post_type' class='$nav_active'>$post_type_details->label</a></li>";
							while ( $post_objects->have_posts() ) :
								$post_objects->the_post();
								?>
								<tr>
									<td class="page-title-row">
										<label for="moove_gdpr_dcb_<?php echo get_the_ID(); ?>"><?php the_title(); ?></label>

									</td>
									<td>
										<!-- GDPR Rounded switch -->
										<label class="gdpr-checkbox-toggle gdpr-checkbox-inverted">

											<input type="checkbox" name="moove_gdpr_dcb[]" <?php echo isset( $gdpr_options['moove_gdpr_dcb'] ) && is_array( $gdpr_options['moove_gdpr_dcb'] ) ? ( in_array( (string) get_the_ID(), $gdpr_options['moove_gdpr_dcb'], true ) ? 'checked' : '' ) : ''; ?> id="moove_gdpr_dcb_<?php echo get_the_ID(); ?>" value="<?php echo get_the_ID(); ?>" >
											<span class="gdpr-checkbox-slider" data-enable="<?php esc_attr_e( 'Visible', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Hidden', 'gdpr-cookie-compliance' ); ?>"></span>
										</label>
									</td>
								</tr>
								<?php
							endwhile;
							wp_reset_postdata();
						endif;
						?>
					</tbody>
				</table> 
				<?php
				$table_content .= ob_get_clean();
			endforeach;
		endif;
		?>
		<ul class="gdpr-disable-posts-nav moove-clearfix">
			<li></li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=cookie-banner-manager&cbm-type=post_type" class="active" style="padding-left: 0;">Posts / Pages</a>
			</li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=cookie-banner-manager&cbm-type=users">Users</a>
			</li>
		</ul>

		<?php if ( $table_nav_content ) : ?>
			<ul class="gdpr-disable-posts-nav type_post_type moove-clearfix">
				<li>
					<strong><?php esc_html_e( 'Select Post Type', 'gdpr-cookie-compliance-addon' ); ?>:</strong>
				</li>
			<?php apply_filters( 'gdpr_addon_keephtml', $table_nav_content, true ); ?>
			</ul>
			<!--  .gdpr-disable-posts-nav -->
		<?php endif; ?>
		<?php apply_filters( 'gdpr_addon_keephtml', $table_content, true ); ?>
	<br />
	<hr />
	<br />
	<button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'gdpr-cookie-compliance-addon' ); ?></button>
	<?php do_action( 'gdpr_cc_banner_buttons_settings' ); ?>
</form>
