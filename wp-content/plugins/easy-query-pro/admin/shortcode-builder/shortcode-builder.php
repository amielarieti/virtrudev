<span class="toggle-all"><span class="inner-wrap"><em class="collapse"><?php _e('Collapse All', 'easy-query'); ?></em><em class="expand"><?php _e('Expand All', 'easy-query'); ?></em></span></span>

<?php
   $alm_options = get_option( 'ewpq_settings' );
   if(!isset($alm_options['_ewpq_disable_dynamic'])) // Check if '_ewpq_disable_dynamic is set within settings
	   $alm_options['_ewpq_disable_dynamic'] = '0';

	$disable_dynamic_content = $alm_options['_ewpq_disable_dynamic'];
?>


<!-- Container Type -->
<div class="row checkbox container_type" id="eq-options">
   <h3 class="heading"><?php _e('Options', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <h4><?php _e('Type', 'easy-query'); ?></h4>
		 	<p><?php _e('Select the container type that will wrap your Easy Query templates.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <ul>
                <li class="full">
                 <input class="alm_element" type="radio" name="container_type" value="ul" id="ul" checked="checked">
                 <label for="ul"><?php _e('&lt;ul&gt;<span>&lt;!-- posts --&gt;</span>&lt;/ul&gt;', 'easy-query'); ?></label>
                </li>
                <li class="full">
                 <input class="alm_element" type="radio" name="container_type" value="ol" id="ol">
                 <label for="ol"><?php _e('&lt;ol&gt;<span>&lt;!-- posts --&gt;</span>&lt;/ol&gt;', 'easy-query'); ?></label>
                </li>
                <li class="full">
                 <input class="alm_element" type="radio" name="container_type" value="div" id="div">
                 <label for="div"><?php _e('&lt;div&gt;<span>&lt;!-- posts --&gt;</span>&lt;/div&gt;', 'easy-query'); ?></label>
                </li>
            </ul>
         </div>
      </div>

      <div class="clear"></div>
      <hr/>
      <div class="section-title">
         <h4><?php _e('Classes', 'easy-query'); ?></h4>
		 	<p><?php _e('Target your content by adding custom classes to the container.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input class="alm_element" name="classes" type="text" id="classes" value="" placeholder="blog-listing listing content etc.">
         </div>
      </div>
   </div>
</div>
<!-- End Container Type -->

<!-- Paging -->
<div class="row checkbox container_type" id="eq-pagng">
   <h3 class="heading"><?php _e('Paging', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <h4><?php _e('Enable', 'easy-query'); ?></h4>
         <p><?php _e('Allow Easy Query to page content.<br/><em>e.g. Page: <a href="javascript:void(0);">1</a> <a href="javascript:void(0);">2</a> <a href="javascript:void(0);">3</a></em>', 'easy-query'); ?></p>
		</div>
      <div class="wrap paging">
         <div class="inner">
            <ul>
                <li>
                 <input class="alm_element" type="radio" name="enable_paging" value="true" id="enable_paging_true" checked="checked">
                 <label for="enable_paging_true"><?php _e('True', 'easy-query'); ?></label>
                </li>
                <li>
                 <input class="alm_element" type="radio" name="enable_paging" value="false" id="enable_paging_false">
                 <label for="enable_paging_false"><?php _e('False', 'easy-query'); ?></label>
                </li>
            </ul>
         </div>
      </div>

      <div id="paging-style-wrap">
         <div class="clear"></div>
         <hr/>
         <div class="section-title">
            <h4><?php _e('Style', 'easy-query'); ?></h4>
            <p><?php _e('Choose a pagination style', 'easy-query'); ?>.</p>
   		</div>
         <div class="wrap paging-style">
            <div class="inner">
               <select id="paging-style-select" class="alm_element">
	               <option value="default" selected="selected"><?php _e('Default', 'easy-query'); ?></option>
	               <option value="null"><?php _e('Plain Text (No styling)', 'easy-query'); ?></option>
	            </select>
	         </div>
         </div>
         <div id="paging-nested-style-wrap">
            <div class="clear"></div>
            <hr/>
            <div class="section-title">
               <h4><?php _e('Color', 'easy-query'); ?></h4>
               <p><?php _e('Choose color for your pagination', 'easy-query'); ?>.</p>
      		</div>
            <div class="wrap paging-style">
               <div class="inner">
                  <select id="paging-color-select" class="alm_element">
   	               <option value="grey" class="grey" selected="selected"><?php _e('Grey (Default)', 'easy-query'); ?></option>
   	               <option value="red" class="red"><?php _e('Red', 'easy-query'); ?></option>
   	               <option value="blue" class="blue"><?php _e('Blue', 'easy-query'); ?></option>
   	               <option value="green" class="green"><?php _e('Green', 'easy-query'); ?></option>
   	               <option value="dark-grey" class="dark-grey"><?php _e('Dark Grey', 'easy-query'); ?></option>
   	               <option value="purple" class="purple"><?php _e('Purple', 'easy-query'); ?></option>
   	            </select>
   	         </div>
            </div>
            <div class="clear"></div>
            <hr/>
            <div class="section-title">
               <h4><?php _e('Next/Prev Arrows', 'easy-query'); ?></h4>
               <p><?php _e('Show the next and previous page arrows in the pagination', 'easy-query'); ?>.</p>
      		</div>
            <div class="wrap paging-arrows">
               <div class="inner">
                  <input class="alm_element" type="checkbox" name="chk-show-arrows" id="chk-show-arrows" value="true" checked="checked">
                  <label for="chk-show-arrows"><?php _e('Show Arrows', 'easy-query'); ?></label>
   	         </div>
            </div>
         </div>
      </div>

   </div>
</div>
<!-- End Paging -->

<!-- Template -->
<div class="row input template" id="eq-template">
   <h3 class="heading"><?php _e('Template', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Select the Easy Query <a href="options-general.php?page=easy-query&tab=templates" target="_parent">template</a> you would like to use.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
	         <select id="template-select" class="alm_element">
	            <option value="default" selected="selected">Default</option>
	            <?php do_action('ewpq_get_template_list'); ?>
	         </select>
	      </div>
      </div>
   </div>
</div>
<!-- End Posts Per Page -->

<!-- Posts Per Page -->
<div class="row input posts_per_page" id="alm-post-page">
   <h3 class="heading"><?php _e('Posts Per Page', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Select the number of posts to load with each request.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input type="number" class="alm_element numbers-only" name="display_posts-select" id="display_posts-select" step="1" min="-1" value="6">
         </div>
      </div>
   </div>
</div>
<!-- End Posts Per Page -->


<!-- Post Types -->
<?php
// List registered post_types
$pt_args = array(
   'public'   => true
);
$types = get_post_types($pt_args);
if($types){
	echo '<div class="row checkboxes post_types" id="alm-post-types">';
	echo '<h3 class="heading">'.__('Post Types', 'easy-query'). '</h3>';
	echo '<div class="expand-wrap">';
	echo '<div class="section-title">';
	echo '<p>'.__('Select Post Types to query.', 'easy-query'). '</p>';
	echo '</div>';
	echo '<div class="wrap"><div class="inner"><ul>';
    foreach( $types as $type ){
     $typeobj = get_post_type_object( $type );
     $name = $typeobj->name;
     if( $name != 'revision' && $name != 'attachment' && $name != 'nav_menu_item' && $name != 'acf'){
         echo '<li><input class="alm_element" type="checkbox" name="chk-'.$typeobj->name.'" id="chk-'.$typeobj->name.'" data-type="'.$typeobj->name.'"><label for="chk-'.$typeobj->name.'">'.$typeobj->labels->singular_name.'</label></li>';
		}
    }
    echo '</ul></div></div>';
    echo '</div>';
    echo '</div>';
}
?>
<!-- End Post Types -->

<!-- Categories -->
<?php
if($disable_dynamic_content){
   $cats = 'null';
}else{
   $cats = get_categories();
}
if($cats){ ?>
<div class="row checkboxes categories" id="alm-categories">
   <h3 class="heading"><?php _e('Category', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <h4>Include</h4>
         <p><?php _e('A comma separated list of categories to include by id. (8, 15, 22 etc...)', 'easy-query'); ?><br/>
         &raquo; <a href="admin.php?page=easy-query-examples#example-category">
         <?php _e('view example', 'easy-query'); ?></a></p>
      </div>
      <div class="wrap">
         <div class="inner">
            <?php
            if(!$disable_dynamic_content){
               echo '<select class="alm_element multiple" name="category-select" id="category-select" multiple="multiple">';
               foreach( $cats as $cat ){
                  echo '<option name="chk-'.$cat->slug.'" id="chk-'.$cat->slug.'" value="'.$cat->term_id.'">'.$cat->name.'</option>';
               }
               echo '</select>';
            }else{
               echo '<input type="text" class="alm_element numbers-only" name="category-select" id="category-select" placeholder="8, 15, 22 etc...">';
            }
            ?>
            <ul style="padding: 20px 0 0;">
                <li>
                 <input class="alm_element" type="radio" name="category-select-type" value="category__in" id="category__in" checked="checked">
                 <label for="category__in">category__in</label>
                </li>
                <li>
                 <input class="alm_element" type="radio" name="category-select-type" value="category__and" id="category__and">
                 <label for="category__and">category__and</label>
                </li>
            </ul>
         </div>
      </div>

      <div class="clear"></div>
      <hr/>

      <div class="section-title">
         <h4><?php _e('Exclude', 'easy-query'); ?></h4>
         <p><?php _e('A comma separated list of categories to exclude by ID. (3, 12, 35 etc..)', 'easy-query'); ?></p>
      </div>
      <div class="wrap">
         <div class="inner">
            <?php
            if(!$disable_dynamic_content){
               echo '<select class="alm_element multiple" name="category-exclude-select" id="category-exclude-select" multiple="multiple">';
               foreach( $cats as $cat ){
                  echo '<option name="chk-'.$cat->term_id.'" id="chk-'.$cat->term_id.'" value="'.$cat->term_id.'">'.$cat->name.'</option>';
               }
               echo '</select>';
            }else{
               echo '<input type="text" class="alm_element numbers-only" name="category-exclude-select" id="category-exclude-select" placeholder="10, 12, 19 etc...">';
            }
            ?>
         </div>
         <div class="clear"></div>
      </div>
   </div>
</div>
<!-- End Categories -->


<!-- Tags -->
<?php }

 // Tags
if($disable_dynamic_content){
   $tags = 'null';
}else{
   $tags = get_tags();
}
if($tags){ ?>
<div class="row checkboxes tags" id="alm-tags">
	<h3 class="heading"><?php _e('Tag', 'easy-query'); ?></h3>
	<div class="expand-wrap">
		<div class="section-title">
		<h4><?php _e('Include', 'easy-query'); ?></h4>
		<p><?php _e('A comma separated list of tags to include by id. (199, 231, 80 etc...)', 'easy-query'); ?><br/>&raquo; <a href="admin.php?page=easy-query-examples#example-tag">view example</a></p>
		</div>
		<div class="wrap">
		   <div class="inner">
           <?php
      	  if(!$disable_dynamic_content){
      	     echo '<select class="alm_element multiple" name="tag-select" id="tag-select" multiple="multiple">';
          	  foreach( $tags as $tag ){
                  echo '<option name="chk-'.$tag->slug.'" id="chk-'.$tag->slug.'" value="'.$tag->term_id.'">'.$tag->name.'</option>';
         	  }
         	  echo '</select>';
      	  }else{
         	  echo '<input type="text" class="alm_element numbers-only" name="tag-select" id="tag-select" placeholder="199, 231, 80 etc...">';
      	  }
      	   ?>

            <ul style="padding: 20px 0 0;">
                <li>
                 <input class="alm_element" type="radio" name="tag-select-type" value="tag__in" id="tag__in" checked="checked">
                 <label for="tag__in">tag__in</label>
                </li>
                <li>
                 <input class="alm_element" type="radio" name="tag-select-type" value="tag__and" id="tag__and">
                 <label for="tag__and">tag__and</label>
                </li>
            </ul>
         </div>
	  </div>
	  <div class="clear"></div>
      <hr/>

      <div class="section-title">
         <h4><?php _e('Exclude', 'easy-query'); ?></h4>
         <p><?php _e('A comma separated list of tags to exclude by ID. (30, 12, 99 etc..)', 'easy-query'); ?></p>
      </div>
      <div class="wrap">
         <div class="inner">
            <?php
            if(!$disable_dynamic_content){
               echo '<select class="alm_element multiple" name="tag-exclude-select" id="tag-exclude-select" multiple="multiple">';
               foreach( $tags as $tag ){
                  echo '<option name="chk-'.$tag->term_id.'" id="chk-'.$tag->term_id.'" value="'.$tag->term_id.'">'.$tag->name.'</option>';
               }
               echo '</select>';
            }else{
               echo '<input type="text" class="alm_element numbers-only" name="tag-exclude-select" id="tag-exclude-select" placeholder="10, 12, 19 etc...">';
            }
            ?>
         </div>
         <div class="clear"></div>
      </div>
  </div>
</div>
<?php } ?>
<!-- End Tags -->

<?php
// Taxonomies
$tax_args = array(
	'public'   => true,
	'_builtin' => false
);
$tax_output = 'objects'; // or objects
$taxonomies = get_taxonomies( $tax_args, $tax_output );
if ( $taxonomies ) { ?>
<div class="row taxonomy" id="alm-taxonomy">
	<h3 class="heading"><?php _e('Taxonomy', 'easy-query'); ?></h3>
	<div class="expand-wrap">
		<div class="section-title full">
         <p><?php _e('Select a taxonomy then select the terms and an operator.', 'easy-query'); ?></p>
      </div>
		<div class="wrap full">
			<div class="tax-query-wrap">
			   <?php include( EQ_PATH . 'admin/shortcode-builder/includes/tax-query-options.php'); ?>
			</div>

			<div class="query-relation" id="tax-query-relation">
            <div class="wrap-30">
               <label for="tax-relation" class="full"><?php _e('Relation:', 'ajax-load-more'); ?></label>
               <select class="alm_element tax-relation" name="tax-relation">
                  <option value="AND" selected="selected">AND</option>
                  <option value="OR">OR</option>
               </select>
            </div>
         </div>

         <div id="taxonomy-target"></div>

		   <div class="controls">
            <button id="add-tax-query" class="button button-primary"><?php _e('Add Another', 'easy-query'); ?></button>
         </div>
	    </div>
    </div>
</div>
<?php }?>
<!-- End Taxonomies -->


<!-- Date -->
<div class="row input date" id="alm-date">
   <h3 class="heading"><?php _e('Date', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Enter a year, month(number) and day to query by date archive.<br/>&raquo; <a href="admin.php?page=easy-query-examples#example-date">view example</a>', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <div class="wrap-30">
               <?php $today = getdate(); ?>
               <label for="input-year" class="full"><?php _e('Year:', 'easy-query'); ?></label>
               <input name="input-year" class="alm_element sm numbers-only" type="text" id="input-year" maxlength="4" placeholder="<?php echo $today['year']; ?>">
            </div>
            <div class="wrap-30">
               <label for="input-month" class="full"><?php _e('Month:', 'easy-query'); ?></label>
               <input name="input-month" class="alm_element sm numbers-only" type="text" id="input-month" maxlength="2" placeholder="<?php echo $today['mon']; ?>">
            </div>
            <div class="wrap-30">
               <label for="input-day" class="full"><?php _e('Day:', 'easy-query'); ?></label>
               <input name="input-day" class="alm_element sm numbers-only" type="text" id="input-day" maxlength="2" placeholder="<?php echo $today['mday']; ?>">
            </div>
         </div>
      </div>
   </div>
</div>


<?php // Custom Fields ?>
<div class="row input meta-key" id="alm-meta-key">
   <h3 class="heading"><?php _e('Custom Fields (Meta_Query)', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title full">
         <p><?php _e('Query for <a href="http://codex.wordpress.org/Class_Reference/WP_Meta_Query" target="_blank">custom field</a> by entering a custom field key, value and operator.', 'easy-query'); ?></p>
      </div>
      <div class="wrap full">

         <div class="meta-query-wrap">
            <?php include( EQ_PATH . 'admin/shortcode-builder/includes/meta-query.php'); ?>
            <div class="clear"></div>
         </div>

         <div class="query-relation" id="meta-query-relation">
            <div class="wrap-30">
               <label for="meta-relation" class="full"><?php _e('Relation:', 'easy-query'); ?></label>
               <select class="alm_element meta-relation" name="meta-relation">
                  <option value="AND" selected="selected">AND</option>
                  <option value="OR">OR</option>
               </select>
            </div>
         </div>

         <div id="meta-query-extended"></div>

         <div class="controls">
            <button class="button button-primary" id="add-meta-query"><?php _e('Add Another', 'easy-query'); ?></button>
         </div>

      </div>
   </div>
</div>
<!-- End Meta Query -->


<?php // List Authors
if($disable_dynamic_content){
   $authors = 'null';
}else{
   $authors = get_users();
}
if($authors){
	echo '<div class="row checkboxes authors" id="alm-authors">';
	echo '<h3 class="heading">' . __('Author', 'easy-query') . '</h3>';
	echo '<div class="expand-wrap">';
	echo '<div class="section-title">';
	echo '<p>' . __('Select an Author to query(by ID).', 'easy-query') . '<br/>&raquo; <a href="admin.php?page=easy-query-examples#example-author">view example</a></p>';
	echo '</div>';
	echo '<div class="wrap"><div class="inner">';
	if(!$disable_dynamic_content){
	   echo '<select class="alm_element" name="author-select" id="author-select">';
		echo '<option value="" selected="selected">-- ' . __('Select Author', 'easy-query') . ' --</option>';
	   foreach( $authors as $author ){
         echo '<option name="chk-'.$author->user_login.'" id="chk-'.$author->user_login.'" value="'.$author->ID.'">'.$author->display_name.'</option>';
	    }
	   echo '</select>';
   }else{
	  echo '<input type="text" class="alm_element numbers-only" name="author-select" id="author-select" placeholder="1">';
   }
   echo '</div></div>';
   echo '</div>';
   echo '</div>';
 }
?>

<!-- Custom Arguments -->
<div class="row input custom-arguments" id="alm-custom-args">
   <h3 class="heading"><?php _e('Custom Arguments', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('A semicolon separated list of custom value:pair arguments.<br/><br/>e.g. tag_slug__and:design,development; event_display:upcoming. Default', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input name="custom-args" class="alm_element" type="text" id="custom-args" value="" placeholder="<?php _e('event_display:upcoming', 'easy-query'); ?>">
         </div>
      </div>
   </div>
</div>

<!-- Search term -->
<div class="row input search-term" id="alm-search">
   <h3 class="heading"><?php _e('Search Term', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Enter a search term to query.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input name="search-term" class="alm_element" type="text" id="search-term" value="" placeholder="<?php _e('Enter search term', 'easy-query'); ?>">
         </div>
      </div>
   </div>
</div>

<!-- Post Parameters -->
<div class="row input exclude" id="alm-exclude-posts">
   <h3 class="heading"><?php _e('Post Parameters', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <h4>Include</h4>
		 	<p><?php _e('A comma separated list of post ID\'s to include in query.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input class="alm_element numbers-only" name="include-posts" type="text" id="include-posts" value="" placeholder="66, 201, 421, 489">
         </div>
      </div>

      <div class="clear"></div>
      <hr/>
      <div class="section-title">
         <h4>Exclude</h4>
		 	<p><?php _e('A comma separated list of post ID\'s to exclude from query.', 'easy-query'); ?><br/>&raquo; <a href="admin.php?page=easy-query-examples#example-exclude">view example</a></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input class="alm_element numbers-only" name="exclude-posts" type="text" id="exclude-posts" value="" placeholder="199, 216, 345, 565">
         </div>
      </div>
   </div>
</div>

<!-- Post Status -->
<div class="row input post-status" id="alm-post-status">
   <h3 class="heading"><?php _e('Post Status', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Select status of the post.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <select class="alm_element" name="post-status" id="post-status">
                <option value="publish" selected="selected">Published</option>
                <option value="future">Future</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending</option>
                <option value="private">Private</option>
                <option value="trash">Trash</option>
            </select>
         </div>
      </div>
   </div>
</div>

<!-- Ordering -->
<div class="row ordering" id="alm-order">
   <h3 class="heading"><?php _e('Ordering', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Sort posts by Order and Orderby parameters.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner half">
            <label class="full">Order:</label>
            <select class="alm_element" name="post-order" id="post-order">
                <option value="DESC" selected="selected">DESC (default)</option>
                <option value="ASC">ASC</option>
            </select>
         </div>
         <div class="inner half">
            <label class="full">Order By:</label>
            <select class="alm_element" name="post-orderby" id="post-orderby">
                <option value="date" selected="selected">Date (default)</option>
                <option value="title">Title</option>
                <option value="name">Name (slug)</option>
                <option value="menu_order">Menu Order</option>
                <option value="rand">Random</option>
                <option value="author">Author</option>
                <option value="ID">ID</option>
                <option value="comment_count">Comment Count</option>
            </select>
         </div>
      </div>
   </div>
</div>

<!-- Offset -->
<div class="row input offset" id="alm-offset">
   <h3 class="heading"><?php _e('Offset', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Offset the initial WordPress query by <em>\'n\'</em> number of posts', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input type="number" class="alm_element numbers-only" name="offset-select" id="offset-select" value="0" step="1" min="0">
         </div>
      </div>
   </div>
</div>

<!-- Archive -->
<div class="row input archives" id="eq-archives">
   <h3 class="heading"><?php _e('Archives', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Easy Query will automatically create an archive query while viewing site archives.', 'easy-query'); ?><br/><br/>
			 <?php _e('Taxonomy, category, tag, date (year, month, day), post type and author archives are currently supported.', 'easy-query'); ?>
			</p>
			<p> <?php _e('<b>Note</b>: Do not select Query Parameters other than Posts Per Page and/or Post Type when using the Archives integration. Easy Query will automatically create the perfect shortcode for you based on the current archive page.', 'easy-query'); ?></p>
		</div>
      <div class="wrap">
         <div class="inner">
            <ul>
                <li>
                 <input class="alm_element" type="radio" name="enable_archives" value="true" id="enable_archives_true">
                 <label for="enable_archives_true"><?php _e('True', 'easy-query'); ?></label>
                </li>
                <li>
                 <input class="alm_element" type="radio" name="enable_archives" value="false" id="enable_archives_false" checked="checked">
                 <label for="enable_archives_false"><?php _e('False', 'easy-query'); ?></label>
                </li>
            </ul>
         </div>
      </div>
   </div>
</div>

<div class="clear"></div>
