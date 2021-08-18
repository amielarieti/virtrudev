<li class="alm-layout alm-default">
	<?php if ( has_post_thumbnail() ) { ?>
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('alm-thumbnail'); ?></a>
	<?php }?>
	<div class="details">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="entry-meta">
		    <?php the_time("F d, Y"); ?>
		</p>
		<?php alm_get_excerpt(26); ?> 
	</div>
</li>