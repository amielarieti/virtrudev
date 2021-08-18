<li class="resource-card">
	<?php 
	if (get_field('cover_image')) {
		$image = get_field('cover_image');
	} else {
		$image = get_the_post_thumbnail_url(get_the_ID(),'medium_large');
	} ?>
	<div class="featured-img" style="background-image:url(<?php echo $image; ?>);"></div>
	<?php if(get_post_type()=="post") : ?>
		<?php $label = 'blog'; $type='Blog Article'; ?>
	<?php else : ?>
   		<?php $terms = wp_get_post_terms( get_the_ID(), 'education-type'); ?>
		<?php foreach ($terms as $t) {$label= $t->slug; $type=$t->name;} // should only be 1 ?>
	<?php endif; ?>
		<div class="type-label <?php echo $label; ?>">
        	<a href="<?php the_permalink(); ?>" target="_blank" class="education-type">
            	<?php echo $type; ?>
        	</a>
		</div>
		<div class="content">
   			<h4>
            	<a href="<?php the_permalink(); ?>" target="_blank" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</h4>
	</div>
</li>