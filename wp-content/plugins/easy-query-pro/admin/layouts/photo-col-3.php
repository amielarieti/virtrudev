<li class="eq-layout eq-photo eq-col-3 <?php eq_is_first($eq_count); ?>">
   <a href="<?php the_permalink(); ?>">
      <?php if ( has_post_thumbnail() ) { ?>
      <div class="eq-gallery-img-wrap">
         <?php the_post_thumbnail('eq-gallery'); ?>
      </div>
      <?php }?>
      <div class="photo-detail">
         <h3 title="<?php the_title(); ?>"><?php the_title(); ?></h3>
         <p class="entry-meta"><?php the_time(apply_filters('eq_date_format', 'F d, Y')); ?></p>
      </div>
   </a>
</li>