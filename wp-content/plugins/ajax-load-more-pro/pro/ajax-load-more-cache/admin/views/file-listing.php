<?php
// Cache deleted msg
if(isset($result) && $cache_deleted) {
   echo '<div class="cache-cleared"><i class="fa fa-check-square-o"></i> ';
      echo $result;
      echo '<span class="remove"><a href="admin.php?page=ajax-load-more-cache">' . __('Got it', 'ajax-load-more-cache') . '</a></span>';
   echo '</div>';
}
?>

<h3><?php _e('Cache Dashboard', 'ajax-load-more-cache'); ?></h3>
<p><?php _e('All pages and files in your Ajax Load More cache are listed below - the listing is grouped by the <strong>Cache ID</strong> assigned when your <a href="admin.php?page=ajax-load-more-shortcode-builder">Shortcode</a> was created.', 'ajax-load-more-cache'); ?></small></p>
<p><a href="admin.php?page=ajax-load-more"><strong><?php _e('Cache Settings', 'ajax-load-more-cache'); ?></strong></a> &nbsp;|&nbsp;  <a href="https://connekthq.com/plugins/ajax-load-more/docs/add-ons/cache/" target="_blank"><strong><?php _e('View Documentation', 'ajax-load-more-cache'); ?></strong></a></p>

<div class="spacer"></div>
<div class="alm-cache-search-wrap" style="margin-top: 3px;">
	<input type="text" name="alm-cache-search" id="alm-cache-search" value="" placeholder="<?php _e('Search cache by ID or URL ', 'ajax-load-more-cache'); ?>">
	<i class="fa fa-search"></i>
</div>

<hr class="cache-break"/>

<div class="alm-cache-listing no-shadow">
   <div class="row no-brd">
	   <?php if(!$cache_deleted) { ?>
	   <span class="toggle-all" tabindex="0">
	   	<span class="inner-wrap">
	   		<em class="collapse"><?php _e('Collapse All', 'ajax-load-more-cache'); ?></em>
	   		<em class="expand"><?php _e('Expand All', 'ajax-load-more-cache'); ?></em>
	   	</span>
	   </span>
	   <?php } ?>

      <?php
      // Loop Cache Directories
      $directoy_total = 0;
      $staticDirectories = array();

      if(is_dir($path)){ // confirm directory exists

         // Loop the directories and store values in array for sorting
         foreach (new DirectoryIterator($path) as $file) {
            if ($file->isDot()) continue;

            if ($file->isDir()){
            	$staticDirectories[] = $file->getFilename();
            }
         }

         asort($staticDirectories); // Sort the directory array

         foreach($staticDirectories as $directory){ // Loop thru our sorted directories and store files in array for sorting

            $directoy_total++;
				$filepath = $path . $directory;

            echo '<div class="alm-dir-listing">';

            	echo '<h3 class="heading dir dir-title" tabindex="0" title="'. $path . $directory .'">';
	            	echo $directory;
	            	echo ' <a href="javascript:void(0);" class="delete" data-id="'. $directory .'" data-path="'. $path . $directory . '" title="'.__('Delete this cache', 'ajax-load-more-cache').'">';
	            		echo __('Delete', 'ajax-load-more-cache');
	            	echo '</a>';
            	echo '</h3>';

					echo '<div class="expand-wrap">';

	               include(ALM_CACHE_ADMIN_PATH .'admin/views/includes/listing.php');

	               // Sub Directories
		            $subDirectories = array();
		            foreach (new DirectoryIterator($sub_path) as $file) {
			            if ($file->isDot()) continue;

			            if ($file->isDir()){
			            	$subDirectories[] = $file->getFilename();
			            }

			         }
			         if($subDirectories){
				         asort($subDirectories);
				         foreach($subDirectories as $subdirectory){
					         echo '<div class="alm-dir-listing--nested">';
						         echo '<div class="alm-dir-listing">';
						         	include(ALM_CACHE_ADMIN_PATH .'admin/views/includes/sub-listing.php');
						         echo '</div>';
					         echo '</div>';
				         }
			         }

						echo '</div>';
            echo '</div>';
         }
      }


      // Empty
      if($directoy_total == 0){
	      include(ALM_CACHE_ADMIN_PATH .'admin/views/includes/empty.php');
      }

      ?>
   </div>
</div>
