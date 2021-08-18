<div class="layout-drop">
   <div class="btn eq-layouts eq-options">
      <a href="javascript:void(0);" class="eq-drop-menu target"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php _e('Select Layout', 'easy-query'); ?></a>
   	<div class="eq-dropdown">
   	   <div class="alm-drop-inner">
      	   <ul>
	      	   <li class="heading">
		   	   	<?php _e('Layout', 'easy-query'); ?>
						<span><?php _e('Columns', 'easy-query'); ?></span>
		   	   </li>
               <li>
                  Default
                  <a href="#" class="layout" data-type="default-col-3" title="<?php _e('3 Columns', 'easy-query'); ?>s"><span>3</span></a>
                  <a href="#" class="layout" data-type="default-col-2" title="<?php _e('2 Columns', 'easy-query'); ?>s"><span>2</span></a>
                  <a href="#" class="layout" data-type="default" title="<?php _e('1 Column', 'easy-query'); ?>"><span>1</span></a>
               </li>
               <li>
                  Call to Action
                  <a href="#" class="layout" data-type="cta-col-3" title="<?php _e('3 Columns', 'easy-query'); ?>s"><span>3</span></a>
                  <a href="#" class="layout" data-type="cta-col-2" title="<?php _e('2 Columns', 'easy-query'); ?>s"><span>2</span></a>
                  <a href="#" class="layout" data-type="cta" title="<?php _e('1 Column', 'easy-query'); ?>"><span>1</span></a>
               </li>
               <li>
                  Gallery
                  <a href="#" class="layout" data-type="gallery-col-3" title="<?php _e('3 Columns', 'easy-query'); ?>s"><span>3</span></a>
                  <a href="#" class="layout" data-type="gallery-col-2" title="<?php _e('2 Columns', 'easy-query'); ?>s"><span>2</span></a>
                  <a href="#" class="layout" data-type="gallery" title="<?php _e('1 Column', 'easy-query'); ?>"><span>1</span></a>
               </li>
               <li>
                  Photo
                  <a href="#" class="layout" data-type="photo-col-3" title="<?php _e('3 Columns', 'easy-query'); ?>s"><span>3</span></a>
                  <a href="#" class="layout" data-type="photo-col-2" title="<?php _e('2 Columns', 'easy-query'); ?>s"><span>2</span></a>
                  <a href="#" class="layout" data-type="photo" title="<?php _e('1 Column', 'easy-query'); ?>"><span>1</span></a>
               </li> 
               <li class="view-all">
               	<a href="https://connekthq.com/plugins/easy-query/docs/layouts/" target="_blank"><?php _e('View Layouts', 'easy-query'); ?></a>
               </li>
      	   </ul>
   	   </div>
   	</div>
   </div>
</div>  

<?php 
	/*
	 * Custom layouts
	 * Core Filter
	 */
	$custom_layouts = apply_filters('eq_custom_layouts', ''); 
	if($custom_layouts){	
	   foreach($custom_layouts as $layout){
	      //echo '<li><a href="javascript:void(0);" class="layout custom" data-type="'.$layout['layout'].'"><span>'.$layout['name'].'</span></a></li>';
	   }
	}
?>