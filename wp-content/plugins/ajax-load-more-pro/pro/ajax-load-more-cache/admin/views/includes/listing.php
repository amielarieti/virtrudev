<?php	
$files = array();
$sub_path = $path . $directory;
$filepath = $directory;

// Get value of _info.txt
$info_text = ALMCache::alm_get_cache_path() .'/'. $directory.'/_info.txt';
echo ALMCache::alm_get_cache_info($info_text, $sub_path, $directory);

// Display cached pages
echo '<div class="cache-page-wrap">';
   echo '<ul>';
      echo '<div class="cache-page-title">'.__('Cached files in this directory', 'ajax-load-more-cache').':</div>';

      foreach (new DirectoryIterator($sub_path) as $sub_file) { // each file
         if ($sub_file->isDot() || $sub_file->getFilename() === '_info.txt')
         	continue;

         if ($sub_file->isFile())
         	$files[] = $sub_file->getFilename();
      }
		if($files){
	      asort($files); // Sort the file array
	      foreach($files as $file){ 
	         include(ALM_CACHE_ADMIN_PATH .'admin/views/includes/file.php');
	      }
	   } else {
	      include(ALM_CACHE_ADMIN_PATH .'admin/views/includes/no-files.php');
      }
   echo '</ul>';
echo '</div>';