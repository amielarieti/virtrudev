<li class="alm-layout alm-3-col <?php alm_is_last($alm_current); ?> alm-blog-card">
	<?php if ( has_post_thumbnail() ) { 
      the_post_thumbnail('alm-thumbnail');		
   }?>
	<h3>
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</h3>  	
	<?php alm_get_excerpt(20); ?> 	
	<p class="alm-meta"><?php the_time("F d, Y"); ?></p>	
	<div class="alm-post-author">
		<?php echo get_avatar( get_the_author_meta('user_email')); ?>
		<span class="author-name">
			By: <?php the_author_posts_link(); ?>
		</span>			
	</div>
</li>