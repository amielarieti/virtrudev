<li class="eq-layout eq-col-3 <?php eq_is_first($eq_count); ?> eq-cta">
	<?php if ( has_post_thumbnail() ) { ?>
   <a href="<?php the_permalink(); ?>">
      <?php the_post_thumbnail('eq-cta'); ?>
   </a>
	<?php }?>
	<div class="details">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="entry-meta">
		    <?php the_time(apply_filters('eq_date_format', 'F d, Y')); ?>
		</p>
		<?php eq_excerpt(apply_filters('eq_excerpt_length', 20)); ?> 
	</div>
</li>