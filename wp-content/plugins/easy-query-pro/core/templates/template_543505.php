<?php if(ICL_LANGUAGE_CODE=='fr'): 
    $more='En savoir plus';
else : 
    $more='Learn More';
endif;?>

<div class="featured-resource">
<?php $img_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
 <div class="info">
  <h3 class="title"><a href="<?php the_permalink(); ?>" target="_blank" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
  <p class="description"><?php the_excerpt(); ?></p>
  <a class="btn" target="_blank" href="<?php the_permalink(); ?>"><?php echo $more; ?></a>

 </div>
 <div class="thumb"><img src="<?php echo $img_url; ?>" alt="" /></div>
</div>