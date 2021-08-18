<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly

	$gdpr_options 				= isset( $data['content'] ) && isset( $data['content']->options ) ? $data['content']->options : array();
	$type 								= isset( $data['type'] ) && $data['type'] ? $data['type'] : false;
	$gdpr_default_content = new Moove_GDPR_Content();
	$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();

	if ( $type  && is_array( $gdpr_options ) && isset( $gdpr_options['gdpr_cookie_declarations_' . $type ] ) ) :
		if ( isset( $gdpr_options['moove_gdpr_cd_enable'] ) && intval( $gdpr_options['moove_gdpr_cd_enable'] ) === 1 ) :
			$cookies_declared = json_decode( $gdpr_options['gdpr_cookie_declarations_' . $type ], true );

			if ( $wpml_lang ) :
				$_cookies_declared 	= isset( $gdpr_options['gdpr_cookie_declarations_' . $type . $wpml_lang] ) ? json_decode( $gdpr_options['gdpr_cookie_declarations_' . $type . $wpml_lang ], true ) : false;
				$cookies_declared 	= $_cookies_declared ? $_cookies_declared : $cookies_declared;
			endif;

			if ( is_array( $cookies_declared ) && ! empty( $cookies_declared ) ) : ?>
				<span class="gdpr-cd-details-toggle" data-close="<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_hide_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_hide_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_hide_label' . $wpml_lang ] ) : esc_attr__( 'Hide details', 'gdpr-cookie-compliance' ); ?>">
					<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_show_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_show_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_show_label' . $wpml_lang ] ) : esc_attr__( 'Show details', 'gdpr-cookie-compliance' ); ?>
					<span></span>
				</span>
				<div class="gdpr-cd-box gdpr-table-responsive-cnt" style="display: none;">
					<table class="gdpr-cd-table gdpr-table-responsive">
						<thead>
			        <tr>
		            <th scope="col"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) : esc_attr__( 'Name', 'gdpr-cookie-compliance' ); ?></th>
		            <th scope="col"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) : esc_attr__( 'Provider', 'gdpr-cookie-compliance' ); ?></th>
		            <th scope="col"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) : esc_attr__( 'Purpose', 'gdpr-cookie-compliance' ); ?></th>
		            <th scope="col"><?php echo isset( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) : esc_attr__( 'Expiration', 'gdpr-cookie-compliance' ); ?></th>
			        </tr>
			    	</thead>
						<tbody>
						<?php foreach ( $cookies_declared as $index => $cookie_data ) : 
							if ( isset( $cookie_data['name'] ) && $cookie_data['name'] ) : ?>
								<tr>
			            <td aria-label="<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_name_label' . $wpml_lang ] ) : esc_attr__( 'Name', 'gdpr-cookie-compliance' ); ?>">
			            	<?php echo $cookie_data['name']; ?>			            		
			            </td>

			            <td aria-label="<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_provider_label' . $wpml_lang ] ) : esc_attr__( 'Provider', 'gdpr-cookie-compliance' ); ?>">
			            	<?php echo isset( $cookie_data['domain'] ) && $cookie_data['domain'] ? $cookie_data['domain'] : ''; ?>				
			            </td>

			            <td aria-label="<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_purpose_label' . $wpml_lang ] ) : esc_attr__( 'Purpose', 'gdpr-cookie-compliance' ); ?>">
			            	<?php echo isset( $cookie_data['desc'] ) && $cookie_data['desc'] ? $cookie_data['desc'] : ''; ?>			            		
			            </td>
			            <td aria-label="<?php echo isset( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_cd_expiration_label' . $wpml_lang ] ) : esc_attr__( 'Expiration', 'gdpr-cookie-compliance' ); ?>">
			            	<?php echo isset( $cookie_data['exp'] ) && $cookie_data['exp'] ? $cookie_data['exp'] : ''; ?>			            		
			            </td>
				        </tr>
							<?php endif; 
						endforeach; ?>
						</tbody>
					</table>
				</div>
				<!--  .gdpr-cd-box -->
			<?php endif;
		endif;
	endif;
?>