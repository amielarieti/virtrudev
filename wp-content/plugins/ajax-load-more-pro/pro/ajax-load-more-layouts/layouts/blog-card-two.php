<li class="alm-layout alm-blog-card-2">
	<?php if ( has_post_thumbnail() ) { ?>
   <div class="alm-gallery-img-wrap">
      <?php the_post_thumbnail('alm-cta'); ?>
   </div>
   <?php }?>
	<div class="alm-card-details">
   	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>  	
   	<?php alm_get_excerpt(20); ?> 	
   	<?php echo get_avatar( get_the_author_meta('user_email')); ?>   	
	</div>
	<div class="alm-post-author">		
		<span class="author-name">
			By: <?php the_author_posts_link(); ?> &bull; <?php the_time("M d, Y"); ?>
		</span>			
	</div>
</li>