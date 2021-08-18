<li class="alm-layout alm-2-col <?php alm_is_odd($alm_current); ?> alm-card">
	<a href="<?php the_permalink(); ?>" class="card-container">
      <div class="card-flip">
         <div class="card-front">
            <h3>
               <?php the_title(); ?>
               <span><?php the_time("F d, Y"); ?></span>
            </h3>            
            <?php if ( has_post_thumbnail() ) { 
               the_post_thumbnail('alm-gallery');		
            }?>
         </div>
         <div class="card-back">
            <div class="text-wrap">
               <?php alm_get_excerpt(25, '<span class="more">Continue Reading</span>'); ?> 
            </div>
         </div>
         <?php if ( has_post_thumbnail() ) { 
            the_post_thumbnail('alm-gallery', array('class' => 'img-mask'));		
         }?>
      </div>
	</a>
</li>