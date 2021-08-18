<div class="cta">
	<h3><?php _e('Status', 'ajax-load-more-cache'); ?></h3>
	<div class="item">
   	<?php   
      //Test server for write capabilities   
      $path = ALMCache::alm_get_cache_path();   
      if (is_writable( $path )){ ?>
   
         <p class="writeable-title"><i class="fa fa-check"></i><strong><?php _e('Enabled', 'ajax-load-more-cache'); ?></strong></p>
         <p class="desc"><?php _e('Read/Write access is enabled within the cache directory', 'ajax-load-more-cache'); ?>.</p>
   
      <?php } else { ?>
   
         <p class="writeable-title">
            <i class="fa fa-exclamation"></i><strong>
            <?php _e('Access Denied', 'ajax-load-more-cache'); ?>!</strong>
         </p>
         <p class="desc">
            <?php _e('You must enable read and write access for the Ajax Load More cache directory to save cache data', 'ajax-load-more-cache'); ?>.<br/><br/>
            <?php _e('Please contact your hosting provider or site administrator for more information.', 'ajax-load-more-cache'); ?>
         </p>
   
      <?php } ?>
      
      <div class="alm-file-location">
         <input type="text" value="<?php echo $path; ?>" class="alm-file-location" readonly="readonly">
      </div>
	</div>
</div>
