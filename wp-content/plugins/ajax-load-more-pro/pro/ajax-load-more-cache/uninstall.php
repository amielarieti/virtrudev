<?php
/**
 * Uninstall Ajax Load More: Cache
 *
 * Deletes all the plugin data i.e.
 * 	1. Cache Directory.
 * @since 1.6
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;


$upload_dir = wp_upload_dir();
$path = apply_filters('alm_cache_path', $upload_dir['basedir'].'/alm-cache/');
almDeleteCacheFiles($path);


/**
 * Loop directories and remove files.
 */
function almDeleteCacheFiles($path){   
   // Loop all cached directories
   foreach (new DirectoryIterator($path) as $directory) {      
      if ($directory->isDot())  continue;   
      if ($directory->isDir()){
         $path_to_directory = $path . $directory;
         alm_cache_rmdir($path_to_directory);
      }
   }     
  
   rmdir($path); // Delete main cache directory 
}


/**
 * Delete all files and parent directory.
 */

function alm_cache_rmdir($path_to_directory) {
	if (current_user_can( apply_filters('alm_custom_user_role', 'edit_theme_options') )){
		$file = $path_to_directory;
		if (is_dir($file)) {
			foreach (glob($file."/*.*") as $filename) {
				if (is_file($filename)) {
					unlink($filename);
				}
			}
			rmdir($file);
		}
	}
}