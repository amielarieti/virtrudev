<?php
echo '<div class="dir-empty" style="overflow: hidden;">';
   echo '<p style="margin: 0; line-height: 30px;">';
  	echo __('Your Ajax Load More cache is currently empty!', 'ajax-load-more-cache');
      if($alm_cache_array){
         echo '<a class="button button-primary" style="float: right;" href="admin.php?page=ajax-load-more-cache&action=build">'. __('Generate Cache</a>', 'ajax-load-more-cache');		
      }
   echo '</p>';
echo '</div>';
echo '<style>hr.cache-break, .toggle-all, .alm-cache-search-wrap{display:none !important;}</style>';