<?php
/**
 * Easy Query Enqueue Scripts
 *
 * Enqueue scripts.
 *
 * @author   Darren Cooney
 * @since    2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists('EQ_ENQUEUE') ):
   
   class EQ_ENQUEUE {      
      
      
      /**
   	 * eq_enqueue_css
   	 *
   	 * Load Easy Query CSS
   	 *
   	 * @since 2.1
   	 * @return wp_enqueue_style
   	 */
      public static function eq_enqueue_css($name, $file){
         $css        = '';
      	$css_path   = '';
      	$dir        = 'easy-query';	
      	$file_css   = $name.'.css';
      	
         // - Check theme for local ajax-load-more.css, if found, load that file         	
      	if(is_child_theme()){
      		$css = get_stylesheet_directory_uri().'/'. $dir .'/' .$file_css;
      		$css_path = get_stylesheet_directory().'/'. $dir .'/' .$file_css;
      		// if child theme does not have CSS, check the parent theme
      		if(!file_exists($css_path)){
      			$css = get_template_directory_uri().'/'. $dir .'/' .$file_css;
      			$css_path = get_template_directory().'/'. $dir .'/' .$file_css;
      		}
      	}
      	else{
      		$css = get_template_directory_uri().'/'. $dir .'/' .$file_css;
      		$css_path = get_template_directory().'/'. $dir .'/' .$file_css;
      	}
      	
      	if($css_path !== ''){ // If $css_path has been
         	if(file_exists($css_path)){                  	     	
         		$file = $css;       		
         	} 
      	}		
      	      	  		
      	wp_enqueue_style( $name, $file ); // Enqueue $file 
      }
      
   }
   
endif;