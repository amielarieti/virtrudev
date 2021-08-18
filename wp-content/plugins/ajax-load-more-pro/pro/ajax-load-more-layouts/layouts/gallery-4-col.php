<li class="alm-layout alm-4-col alm-gallery">
	<a href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) { ?>
		<div class="alm-gallery-img-wrap">
			<?php the_post_thumbnail('alm-gallery'); ?>
		</div>
		<?php }?>
		<div class="overlay-details">
   		<div class="vertical-align">
      		<p class="entry-date"><?php the_time("F d, Y"); ?></p>
			   <h3><?php the_title(); ?></h3>
            <?php alm_get_excerpt(16); ?> 
   		</div>
		</div>
	</a>
</li>