<?php

function my_eq_date_format(){
   return 20;
}
//add_filter('eq_excerpt_length', 'my_eq_date_format');


/*
*  eq_excerpt()
*  A custom excerpt for Easy Query
*
*  @since 2.0
*/   

// Get custom excerpt
function eq_excerpt($limit) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt)>=$limit) {
		array_pop($excerpt);
		$excerpt = implode(" ", $excerpt).'...';
	} else {
		$excerpt = implode(" ", $excerpt);
	}
	$excerpt = preg_replace('`[[^]]*]`','',$excerpt);		
	 
	if($excerpt) echo '<p class="eq-excerpt">'.$excerpt.'</p>';
}



/*
*  ewpq_get_current_template
*  Get the current repeater template file
*
*  @return $include (file path)
*  @since 1.0.0
*/

function ewpq_get_current_template($template, $type) {
   global $wpdb;
	$blog_id = $wpdb->blogid;
	
	$include = '';
	
	if($blog_id > 1){ // multisite
      $include = EQ_PATH. 'core/templates_'. $blog_id.'/'.$template .'.php';
      if(!file_exists($include)) //confirm file exists        			
	      $include = EQ_PATH. 'core/templates_'. $blog_id.'/default.php';
	}else{
	   $include = EQ_TEMPLATE_PATH. ''.$template .'.php';   
   } 					
	
	if(!file_exists($include)) //Global include fallback     			
	   $include = EQ_TEMPLATE_PATH . 'default.php'; 		
	
	return $include;
}



/*
*  ewpq_get_post_format
*  Query by post format
*  
*  @return $args = array();
*  @since 1.0.0
*  @updated 2.2
*/

function ewpq_get_post_format($post_format){
   $format = "post-format-$post_format";
   //If query is for standard then we need to filter by NOT IN
   if($format == 'post-format-standard'){		   
   	if (($post_formats = get_theme_support('post-formats')) && is_array($post_formats[0]) && count($post_formats[0])) {
         $terms = array();
         foreach ($post_formats[0] as $format) {
            $terms[] = 'post-format-'.$format;
         }
      }		      
      $args = array(
         'taxonomy' => 'post_format',
         'terms' => $terms,
         'field' => 'slug',
         'operator' => 'NOT IN',
      );
   }else{
		$args = array(
		   'taxonomy' => 'post_format',
		   'field' => 'slug',
		   'terms' => array($format),
		);			
	}
	return $args;
}



/*
*  ewpq_get_meta_query
*  Query by custom field values
*  
*  @return $args = array();
*  @since 1.0.0
*/
function ewpq_get_meta_query($meta_key, $meta_value, $meta_compare, $meta_type){
   if(!empty($meta_key)){
      $meta_values = ewpq_parse_meta_value($meta_value, $meta_compare);
      if(!empty($meta_values)){
         $return = array(
            'key' => $meta_key,
            'value' => $meta_values,
            'compare' => $meta_compare,
            'type' => $meta_type
         );
      }else{
         // If $meta_values is empty, don't query for 'value'
         $return = array(
            'key' => $meta_key,
            'compare' => $meta_compare,
            'type' => $meta_type
         );
      }
      return $return;
   }
}



/*
*  ewpq_parse_meta_value
*  Parse the meta value for multiple vals
*
*  @helper function @ewpq_get_meta_query()
*  @return array;
*  @since 2.2
*/
function ewpq_parse_meta_value($meta_value, $meta_compare){
   // See the docs (http://codex.wordpress.org/Class_Reference/WP_Meta_Query)
   if($meta_compare === 'IN' || $meta_compare === 'NOT IN' || $meta_compare === 'BETWEEN' || $meta_compare === 'NOT BETWEEN'){
   	// Remove all whitespace for meta_value because it needs to be an exact match
   	$mv_trimmed = preg_replace('/\s+/', ' ', $meta_value); // Trim whitespace
   	$meta_values = str_replace(', ', ',', $mv_trimmed); // Replace [term, term] with [term,term]
   	$meta_values = explode(",", $meta_values);
   }else{
   	$meta_values = $meta_value;
   }
   return $meta_values;
}



/*
*  eq_get_taxonomy_query
*  Query for custom taxonomy
*
*  @return $args = array();
*  @since 2.2
*/
function eq_get_taxonomy_query($taxonomy, $taxonomy_terms, $taxonomy_operator){
   if(!empty($taxonomy) && !empty($taxonomy_terms)){
      $taxonomy_term_values = eq_parse_tax_terms($taxonomy_terms);
      $return = array(
         'taxonomy' => $taxonomy,
         'field' => 'slug',
         'terms' => $taxonomy_term_values,
         'operator' => $taxonomy_operator
      );
      return $return;
   }
}



/*
*  eq_parse_tax_terms
*  Parse the taxonomy terms for multiple vals
*
*  @helper function @eq_get_taxonomy_query()
*  @return array;
*  @since 2.2
*/
function eq_parse_tax_terms($taxonomy_terms){
	// Remove all whitespace for $taxonomy_terms because it needs to be an exact match
	$taxonomy_terms = preg_replace('/\s+/', ' ', $taxonomy_terms); // Trim whitespace
	$taxonomy_terms = str_replace(', ', ',', $taxonomy_terms); // Replace [term, term] with [term,term]
	$taxonomy_terms = explode(",", $taxonomy_terms);
   return $taxonomy_terms;
}



// Is item odd?
function eq_is_odd($number){
	if ($number % 2 !== 0) {
	  echo "odd";
	}
} 

// Is item first in 3 column?
function eq_is_first($number){
	if ($number % 3 == 1) {
	  echo "first";
	}
} 

// Is last item in 3 column layout
function eq_is_last($number){
	if ($number % 3 == 0) {
	  echo "last";
	}
} 
