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
	$empty_scripts        = false;

if ( isset( $_POST ) && ( isset( $_POST['moove_gdpr_nonce_export'] ) || isset( $_POST['moove_gdpr_nonce_import'] ) ) ) :
	wp_verify_nonce( 'ga_nonce', 'gdpr_addon_nonce' );
	$_type = isset( $_POST['moove_gdpr_nonce_export'] ) ? 'export' : ( isset( $_POST['moove_gdpr_nonce_import'] ) ? 'import' : 'multisite' );
	$nonce = isset( $_POST[ 'moove_gdpr_nonce_' . $_type ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'moove_gdpr_nonce_' . $_type ] ) ) : false;
	if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_nonce_field_' . $_type ) ) :
		die( 'Security check' );
		else :
			if ( is_array( $_POST ) ) :
				if ( isset( $_POST['gdpr-export-settings'] ) && 'true' === $_POST['gdpr-export-settings'] ) :
					if ( class_exists( 'Moove_GDPR_Content' ) ) :
						$gdpr_default_content = new Moove_GDPR_Content();
						$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
						$modal_options        = get_option( $option_name );
						$json                 = json_encode( $modal_options, true );

						array_to_csv_download( json_decode( $json, true ) );

					endif;

				endif;
				if ( isset( $_POST['gdpr-import-settings'] ) && 'true' === $_POST['gdpr-import-settings'] ) :
					$temp_name = isset( $_FILES['gdpr-import-settings-csv']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['gdpr-import-settings-csv']['tmp_name'] ) ) : false;
					$success   = false;
					$message   = esc_html__( 'Please upload the settings file!', 'gdpr-cookie-compliance-addon' );
					if ( $temp_name ) :
						$json_settings   = file( $temp_name );
						$import_settings = isset( $json_settings[0] ) ? json_decode( $json_settings[0], true ) : false;
						$message         = esc_html__( 'Wrong settings file uploaded!', 'gdpr-cookie-compliance-addon' );
						if ( is_array( $import_settings ) ) :
							if ( isset( $import_settings['moove_gdpr_nonce'] ) ) :
								if ( $import_settings && is_array( $import_settings ) && isset( $import_settings['moove_gdpr_nonce'] ) ) :
									$gdpr_default_content = new Moove_GDPR_Content();
									$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
									$modal_options        = update_option( $option_name, $import_settings );
									$success              = true;
									$message              = esc_html__( 'Settings imported successfully!', 'gdpr-cookie-compliance-addon' );
								endif;
							endif;
						endif;
					endif;
					?>
					<div id="moove-gdpr-import-settings_updated" class="<?php echo $success ? 'updated' : 'error'; ?> settings-error notice is-dismissible gdpr-cc-notice" style="display:none;">
						<p><strong><?php echo esc_attr( $message ); ?></strong></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'gdpr-cookie-compliance-addon' ); ?></span>
						</button>
					</div>

					<script>
						jQuery('#moove-gdpr-import-settings_updated').show();
					</script>

					<?php
				endif;
			endif;
		endif;
	endif;
	$gdpr_options = get_option( $option_name );


?>

<form action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=export-import" method="post">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field_export', 'moove_gdpr_nonce_export' ); ?>
	<h2><?php esc_html_e( 'Export Settings', 'gdpr-cookie-compliance-addon' ); ?></h2>
	<hr />
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="moove_gdpr_export_format"><?php esc_html_e( 'Export Settings', 'gdpr-cookie-compliance-addon' ); ?></label>
				</th>
				<td>
					<input type="hidden" name="gdpr-export-settings" value="true" />
					<button type="submit" class="button button-secondary"><?php esc_html_e( 'Download', 'gdpr-cookie-compliance-addon' ); ?></button>         
				</td>
			</tr>
		</tbody>
	</table>
</form>

<form action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=moove-gdpr&amp;tab=export-import" method="post" enctype="multipart/form-data">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field_import', 'moove_gdpr_nonce_import' ); ?>
	<br />
	<hr />
	<h2><?php esc_html_e( 'Import Settings', 'gdpr-cookie-compliance-addon' ); ?></h2>
	<hr />
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="moove_gdpr_export_format"><?php esc_html_e( 'Import settings from file', 'gdpr-cookie-compliance-addon' ); ?></label>
				</th>
				<td>
					<input type="hidden" name="gdpr-import-settings" value="true" />
					<input type="file" name="gdpr-import-settings-csv" /><br><br>
					<button type="submit" class="button button-secondary"><?php esc_html_e( 'Import settings', 'gdpr-cookie-compliance-addon' ); ?></button>                  
				</td>
			</tr>

		</tbody>
	</table>
	<br />
</form>
