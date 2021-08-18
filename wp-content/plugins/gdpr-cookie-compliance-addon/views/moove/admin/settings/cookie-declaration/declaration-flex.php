<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
	
	$gdpr_options = $data['options'];
?>
<div class="gdpr-help-content-cnt">
	<?php 

		$gdpr_default_content = new Moove_GDPR_Content();
		$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();

		$value = isset( $gdpr_options['gdpr_cookie_declarations_'.$data['id']] ) ? $gdpr_options['gdpr_cookie_declarations_'.$data['id']] : json_encode( array() );
		if ( $wpml_lang ) :
			$value = isset( $gdpr_options['gdpr_cookie_declarations_'.$data['id'].$wpml_lang] ) ? $gdpr_options['gdpr_cookie_declarations_'.$data['id'].$wpml_lang] : $value;
		endif;
		$cookies_declared = json_decode( $value, true );
		$value = $cookies_declared ? $value : json_encode( array() );
		$count = 0;
	?>
	
	<div class="gdpr-help-content-block">
		<div class="gdpr-faq-toggle">
			<div class="gdpr-faq-accordion-header">
				<h3><?php echo esc_attr( $data['title'] ); ?></h3>
				<input type="hidden" class="gdpr-cd-inpval" name="gdpr_cookie_declarations_<?php echo $data['id']; ?>" value='<?php echo htmlentities( $value, ENT_QUOTES ); ?>' />
			</div>
			<div class="gdpr-faq-accordion-content" style="padding: 15px; display: none;">
				<div class="gdpr-cookie-declaration-box">
					<div class="gdpr-cd-flexible">
						<div class="cd-box-layout">
							<div class="gdpr-cd-box">
								<span class="cd-boxno"></span>
								<button type="button" title="Remove" class="cd-remove"><span class="dashicons dashicons-no-alt"></span></button>
								<table class="gdpr-cd-table">
									<tbody>
										<tr>
											<th class="cd-name"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) : esc_attr__( 'Name', 'gdpr-cookie-compliance' ); ?></th>
											<td>
												<input type="text" class="cd-name" value=""/>
											</td>
										</tr>
										<tr>
											<th class="cd-domain"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) : esc_attr__( 'Provider', 'gdpr-cookie-compliance' ); ?></th>
											<td>
												<input type="text" class="cd-domain" value=""/>
											</td>
										</tr>
										<tr>
											<th class="cd-description"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) : esc_attr__( 'Purpose', 'gdpr-cookie-compliance' ); ?></th>
											<td>
												<textarea cols="10" class="cd-description" rows="10"></textarea>
											</td>
										</tr>
										<tr>
											<th class="cd-expiration"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) : esc_attr__( 'Expiration', 'gdpr-cookie-compliance' ); ?></th>
											<td>
												<textarea cols="10" class="cd-expiration" rows="10"></textarea>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--  .gdpr-cd-box -->
							<p class="cdempty">Click the "Add Cookie Info" button below to start your cookie declaration</p>
						</div>
						<!--  .cd-box-layout -->
						<?php 
							$class = is_array( $cookies_declared ) && ! empty( $cookies_declared ) ? '' : 'cdempty';
						?>
						<div class="gdpr-cd-list <?php echo $class; ?>">
							<?php if ( $class === 'cdempty' ) : ?>
								<p class="cdempty">Click the "Add Cookie Info" button below to start your cookie declaration</p>
							<?php else : ?>
								<?php foreach ( $cookies_declared as $index => $cookie_data ) : ?>
									<?php if ( isset( $cookie_data['name'] ) && $cookie_data['name'] ) : $count++; ?>
										<div class="gdpr-cd-box">
											<span class="cd-boxno"><?php echo $count; ?></span>
											<button type="button" title="Remove" class="cd-remove"><span class="dashicons dashicons-no-alt"></span></button>
											<table class="gdpr-cd-table">
												<tbody>
													<tr>
														<th class="cd-name"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) : esc_attr__( 'Name', 'gdpr-cookie-compliance' ); ?></th>
														<td>
															<input type="text" class="cd-name" value="<?php echo $cookie_data['name']; ?>"/>
														</td>
													</tr>
													<tr>
														<th class="cd-domain"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) : esc_attr__( 'Provider', 'gdpr-cookie-compliance' ); ?></th>
														<td>
															<input type="text" class="cd-domain" value="<?php echo isset( $cookie_data['domain'] ) && $cookie_data['domain'] ? $cookie_data['domain'] : ''; ?>"/>
														</td>
													</tr>
													<tr>
														<th class="cd-description"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) : esc_attr__( 'Purpose', 'gdpr-cookie-compliance' ); ?></th>
														<td>
															<textarea cols="10" class="cd-description" rows="10"><?php echo isset( $cookie_data['desc'] ) && $cookie_data['desc'] ? $cookie_data['desc'] : ''; ?></textarea>
														</td>
													</tr>
													<tr>
														<th class="cd-expiration"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) : esc_attr__( 'Expiration', 'gdpr-cookie-compliance' ); ?></th>
														<td>
															<textarea cols="10" class="cd-expiration" rows="10"><?php echo isset( $cookie_data['exp'] ) && $cookie_data['exp'] ? $cookie_data['exp'] : ''; ?></textarea>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<!--  .gdpr-cd-box -->
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>
							
						</div>
						<!--  .gdpr-cd-list -->
						
						<div class="cdbtncnt">
							<button type="button" class="cd-add-new"><span class="dashicons dashicons-plus"></span> <?php esc_html_e('Add Cookie Info', 'gdpr-cookie-compliance-addon'); ?></button>
						</div>
						<!--  .cdbtncnt -->
					</div>
					<!--  .gdpr-cd-flexible -->
				</div>
				<!--  .gdpr-cookie-declaration-box -->
			</div>
			<!--  .gdpr-faq-accordion-content -->
		</div>
		<!--  .gdpr-faq-toggle -->
	</div>
	<!--  .gdpr-help-content-block -->
</div>
<!--  .gdpr-help-content-cnt -->
<script type="text/javascript" src="<?php echo esc_attr( moove_gdpr_addon_get_plugin_dir() ); ?>/assets/js/jquery_ui.min.js"></script>
