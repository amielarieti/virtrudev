<li class="alm-layout alm-4-col <?php alm_is_4col_last($alm_current); ?> alm-cta">
   <a href="<?php the_permalink(); ?>">
	<?php if ( has_post_thumbnail() ) { ?>
		<?php the_post_thumbnail('alm-cta'); ?>
	<?php }?>
	<div class="details">
		<h3><?php the_title(); ?></h3>
		<p class="entry-meta">
		    <?php the_time("F d, Y"); ?>
		</p>
		<?php alm_get_excerpt(30); ?> 
	</div>
   </a>
</li>