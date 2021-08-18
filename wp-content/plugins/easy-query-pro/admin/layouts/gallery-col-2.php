<li class="eq-layout eq-gallery eq-col-2 <?php eq_is_odd($eq_count); ?>">
   <a href="<?php the_permalink(); ?>">
      <?php if ( has_post_thumbnail() ) { ?>
      <div class="eq-gallery-img-wrap">
         <?php the_post_thumbnail('eq-gallery'); ?>
      </div>
      <?php }?>
      <div class="overlay-details">
         <div class="vertical-align">
            <h3><?php the_title(); ?></h3>
            <p class="entry-meta"><?php the_time(apply_filters('eq_date_format', 'F d, Y')); ?></p>
         </div>
      </div>
   </a>
</li>