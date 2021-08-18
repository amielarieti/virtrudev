<?php
/*
Plugin Name: Easy Query Pro
Plugin URI: https://connekthq.com/plugins/easy-query/
Description: A query builder plugin for WordPress.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: https://connekthq.com
Version: 2.3.1.1
License: GPL
Copyright: Darren Cooney & Connekt Media
*/


define( 'EQ_VERSION', '2.3.1.1' );
define( 'EQ_RELEASE', 'March 21, 2021' );



/*
*  ewpq_install
*  Create table for storing repeater
*
*  @since 1.0.0
*/

register_activation_hook( __FILE__, 'eq_install' );
add_action( 'wpmu_new_blog', 'eq_install' );
function eq_install($network_wide) {
	global $wpdb;
	add_option( "easy_query_version", EQ_VERSION ); // Add to WP Option tbl

   if ( is_multisite() && $network_wide ) {

      // Get all blogs in the network and activate plugin on each one
      $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
      foreach ( $blog_ids as $blog_id ) {
         switch_to_blog( $blog_id );
         eq_create_table();
         restore_current_blog();
      }
   } else {
      eq_create_table();
   }
}

function eq_create_table(){
	global $wpdb;
	$table_name = $wpdb->prefix . "easy_query";
	$blog_id = $wpdb->blogid;

	$template = '<li <?php if (!has_post_thumbnail()) { ?> class="no-img"<?php } ?>><?php if ( has_post_thumbnail() ) { the_post_thumbnail("eq-thumbnail");}?><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p class="entry-meta"><?php the_time("F d, Y"); ?></p><?php the_excerpt(); ?></li>';


	/*
    * Multisite
    * If multisite blog and it's not id = 1, create new folder and default template
    *
    */
   if($blog_id > 1){
	   $dir = EQ_PATH. 'core/templates_'. $blog_id;
   	if( !is_dir($dir) ){
         mkdir($dir);
         $tmp = fopen($dir.'/default.php', 'w');
			$w = fwrite($tmp, $template);
			fclose($myfile);
   	}
	}

	/*
    * Create table if it doesn't already exist.
    */
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name text NOT NULL,
			type longtext NOT NULL,
			alias longtext NOT NULL,
			template longtext NOT NULL,
			pluginVersion text NOT NULL,
			UNIQUE KEY id (id)
		);";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		//Insert the default data in created table
      $wpdb->insert($table_name, array(
         'name' => 'default',
         'type' => 'default',
         'alias' => '',
         'template' => $template,
         'pluginVersion' => EQ_VERSION,
      ));
	}

}



if( !class_exists('EasyQuery') ):
	class EasyQuery {

   	function __construct(){

   		define('EQ_PATH', plugin_dir_path(__FILE__));
   		define('EQ_LAYOUT_PATH', plugin_dir_path(__FILE__).'admin/layouts/');

   		define('EQ_PAGING', plugin_dir_path(__FILE__).'core/paging.php');
   		define('EQ_TEMPLATE_PATH', plugin_dir_path(__FILE__).'core/templates/');
   		define('EQ_URL', plugins_url('', __FILE__));
   		define('EQ_ADMIN_URL', plugins_url('admin/', __FILE__));
   		define('EQ_NAME', 'easy_query');
   		define('EQ_SLUG', 'easy-query');
   		define('EQ_TITLE', 'Easy Query Pro');
   		define('EQ_TAGLINE', 'Create complex WordPress queries with the click of a button!');

   		add_action( 'wp_enqueue_scripts', array(&$this, 'eq_enqueue_scripts') ); // scripts
   		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'eq_action_links') ); // dashboard links
   		add_filter( 'widget_text', 'do_shortcode' ); // Allow shortcodes in widget areas
   		add_action( 'after_setup_theme',  array(&$this, 'eq_image_sizes') ); // Add image sizss
   		load_plugin_textdomain( 'easy-query', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' ); // load text domain
   		add_shortcode( 'easy_query', array(&$this, 'ewpq_shortcode') ); // [easy_query]

   		$this->eq_includes();

   	}



   	/*
   	*  eq_includes
   	*  Load these files before the theme loads
   	*
   	*  @since 1.0.0
   	*/

   	function eq_includes(){
   		if( is_admin()){
   			include_once('admin/editor/editor.php');
   			include_once('admin/admin.php');
   			include_once('admin/admin-helpers.php');
   		}

      	include_once( EQ_PATH . 'core/classes/class.enqueue.php'); // Enqueue Scripts
   		include_once( EQ_PATH . 'core/functions.php'); // Core functions
      }



   	/*
   	*  eq_action_links
   	*  Add plugin action links to WP plugin screen
   	*
   	*  @since 1.0.0
   	*/

      function eq_action_links( $links ) {
         $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=easy-query') .'">'. __('Settings', 'easy-query').'</a>';
         $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=easy-query&tab=query-builder') .'">'. __('Query Builder', 'easy-query').'</a>';
         return $links;
      }



   	/*
   	*  eq_enqueue_scripts
   	*  Enqueue our scripts and create our localize variables
   	*
   	*  @since 1.0.0
   	*/

   	function eq_enqueue_scripts(){
   		$options = get_option( 'ewpq_settings' );
   		if(!isset($options['_ewpq_disable_css']) || $options['_ewpq_disable_css'] != '1'){
         	//$file = plugins_url('/core/css/easy-query.css', __FILE__ );
         	$file = plugins_url('/core/css/easy-query.min.css', __FILE__ );
            EQ_ENQUEUE::eq_enqueue_css('easy-query', $file);
   		}
   	}



   	/*
		*  eq_image_sizes
		*  Add default image size
		*
		*  @since 2.0.0
		*/

		public function eq_image_sizes(){
			add_image_size( 'eq-thumbnail', 120, 120, true); // Custom thumbnail size
			add_image_size( 'eq-cta', 800, 450, true); // cta
			add_image_size( 'eq-gallery', 800, 650, true); // gallery
		}



   	/*
   	*  ewpq_shortcode
   	*  The Easy WP Query shortcode
   	*
   	*  @since 1.0.0
   	*/

   	function ewpq_shortcode( $atts, $content = null ) {

   		$a = shortcode_atts(array(
   		   'id' => null,
   		   'archive' => false,
   		   'container' => 'ul',
   		   'classes' => '',
				'posts_per_page' => '6',
				'paging' => 'true',
				'paging_style' => 'default',
				'paging_color' => 'grey',
				'paging_arrows' => 'true',
				'template' => 'default',
				'post_type' => 'post',
				'post_format' => '',
				'category__in' => '',
				'category__and' => '',
				'category__not_in' => '',
				'tag__in' => '',
				'tag__and' => '',
				'tag__not_in' => '',
				'taxonomy' => '',
				'taxonomy_terms' => '',
				'taxonomy_operator' => 'IN',
				'taxonomy_relation' => 'AND',
				'meta_key' => '',
				'meta_value' => '',
				'meta_type' => 'CHAR',
				'meta_compare' => 'IN',
				'meta_relation' => '',
				'year' => '',
				'month' => '',
				'day' => '',
				'author' => '',
				'search' => '',
				'custom_args' => '',
				'post__in' => '',
				'post__not_in' => '',
				'post_status' => 'publish',
				'order' => 'DESC',
				'orderby' => 'date',
				'offset' => '0',
			), $atts);


			// ID
			$id = $a['id'];

			if ( is_null( $id ) ) {

				// Archives.
				$archive = ( 'true' === $a['archive'] ) ? true : false;

				// Container Options.
				$container = $a['container'];
				$classes = $a['classes'];

	   		$posts_per_page = $a['posts_per_page'];
	   		$paging = $a['paging'];
	   		$paging_arrows = $a['paging_arrows'];
	   		if($paging == 'true'){
	   		   $paging_style = ' paging-style-'.$a['paging_style'];
	   		   $paging_color = ' color-'.$a['paging_color'];
	   		   if($paging_arrows !== 'true'){
	      		   $paging_arrows = ' no-arrows';
	   		   }else{
	   		      $paging_arrows = '';
	   		   }
	   		}else{
	      		$paging_style = '';
	      		$paging_color = '';
	      		$paging_arrows = '';
	   		}

				// Repeater
				$template = $a['template'];
	   		$template_type = preg_split('/(?=\d)/', $template, 2); // split $template value at number to determine type
	   		$template_type = $template_type[0]; // default | template_

	   		// Post type & Format
	      	$post_type = explode(",", $a['post_type']);
	      	$post_format = $a['post_format'];

	      	// Cat & Tag
	   		$category__in = trim($a['category__in']);
	   		$category__and = trim($a['category__and']);
	   		$category__not_in = trim($a['category__not_in']);
	   		$tag__in = $a['tag__in'];
	   		$tag__and = $a['tag__and'];
	   		$tag__not_in = $a['tag__not_in'];

	   		// Taxonomy
	   		$taxonomy = $a['taxonomy'];
	   		$taxonomy_terms = $a['taxonomy_terms'];
	   		$taxonomy_operator = $a['taxonomy_operator'];
	   		$taxonomy_relation = $a['taxonomy_relation'];

	   		// Custom Fields
	   		$meta_key = $a['meta_key'];
	   		$meta_value = $a['meta_value'];
	   		$meta_compare = $a['meta_compare'];
	   		$meta_type = $a['meta_type'];
	   		$meta_relation = $a['meta_relation'];
	   		if($meta_relation == '') $meta_relation = 'AND';

	   		// Search
	   		$s = $a['search'];

	   		// Custom Args
	   		$custom_args = $a['custom_args'];

	   		// Date
	   		$year = $a['year'];
	   		$month = $a['month'];
	   		$day = $a['day'];

	   		// Author ID
	   		$author_id = $a['author'];

	   		// Ordering
	   		$order = $a['order'];
	   		$orderby = $a['orderby'];

	   		// Exclude, Offset, Status
	   		$post__in = $a['post__in'];
	   		$post__not_in = $a['post__not_in'];
	   		$offset = $a['offset'];
	   		$post_status = $a['post_status'];

	   		// Lang Support
	   		$lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : ''; // WPML - http://wpml.org
	   		if (function_exists('pll_current_language')) // Polylang - https://wordpress.org/plugins/polylang/
	   		   $lang = pll_current_language();
	         if (function_exists('qtrans_getLanguage')) // qTranslate - https://wordpress.org/plugins/qtranslate/
	   		   $lang = qtrans_getLanguage();


	      	// Fix for static frontpage
	      	// $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				if ( get_query_var('paged') ) {
					$paged = get_query_var('paged');
				} elseif ( get_query_var('page') ) {
					$paged = get_query_var('page');
				} else {
					$paged = 1;
				}

				$page = $paged - 1; // Current page num.

				/**
				 * Archive
				 * Set required archive config options
				 */
				if ( $archive && is_archive() ) {

					if ( is_date() ) {
						$archive_year = get_the_date( 'Y' );
						$archive_month = get_the_date( 'm' );
						$archive_day = get_the_date( 'd' );
					if ( is_year() ) {
						$year = $archive_year;
					}
					if ( is_month() ) {
						$month = $archive_month;
						$year = $archive_year;
					}
					if ( is_day() ) {
						$year = $archive_year;
						$month = $archive_month;
						$day = $archive_day;
					}
					}
					if ( is_author() ) {
						$author = get_the_author_meta( 'ID' );
					}
					if ( is_tax() || is_category() || is_tag() ) {
						$obj = get_queried_object();
						$taxonomy = $obj->taxonomy;
						$taxonomy_terms = $obj->slug;
						$taxonomy_operator = 'IN';
					}
					if ( is_post_type_archive() ) {
						$obj = get_queried_object();
						if ( isset( $obj->name ) ) {
							$post_type = $obj->name;
						}
					}
				}

	      	// WP_Query $args
	   		$args = array(
	   			'post_type'             => $post_type,
	   			'posts_per_page'        => $posts_per_page,
	   			'order'                 => $order,
	   			'orderby'               => $orderby,
	   			'post_status'           => $post_status,
	   			'ignore_sticky_posts'   => true,
	   			'paged'                 => $paged,
	   		);

	   		// Offset
	   		if($offset > 0){
	      		$args['offset'] = $offset + ($posts_per_page*$page);
	   		}

	   		// Post Format
	   		if(!empty($post_format)){
	   		   $args['tax_query'] = array(
	   				'relation' => $taxonomy_relation,
	   		      ewpq_get_post_format($post_format)
	   		   );
	   	   }

	   		// Taxonomy
	   		if(!empty($taxonomy)){

	      		$tax_query_total = count(explode(":", $taxonomy)); // Total $taxonomy objects
	            $taxonomy = explode(":", $taxonomy); // convert to array
	            $taxonomy_terms = explode(":", $taxonomy_terms); // convert to array
	            $taxonomy_operator = explode(":", $taxonomy_operator); // convert to array

	   		   $args['tax_query'] = array(
						'relation' => $taxonomy_relation
					);

					// Loop Taxonomies
					for($tax_i = 0; $tax_i < $tax_query_total; $tax_i++){
						$args['tax_query'][] = eq_get_taxonomy_query($taxonomy[$tax_i], $taxonomy_terms[$tax_i], $taxonomy_operator[$tax_i]);
					}
	   	   }

	   	   // Category
	   		if(!empty($category__in)){
	   		   $include_cats = explode(",",$category__in);
	   			$args['category__in'] = $include_cats;
	   		}
	   		if(!empty($category__and)){
	   		   $include_cats = explode(",",$category__and);
	   			$args['category__and'] = $include_cats;
	   		}

	         // Category Not In
	   		if(!empty($category__not_in)){
	   		   $exclude_cats = explode(",",$category__not_in);
	   			$args['category__not_in'] = $exclude_cats;
	   		}

	         // Tag
	   		if(!empty($tag__in)){
	   		   $include_tags = explode(",",$tag__in);
	   			$args['tag__in'] = $include_tags;
	   		}
	   		if(!empty($tag__and)){
	   		   $include_tags = explode(",",$tag__and);
	   			$args['tag__and'] = $include_tags;
	   		}

	         // Tag Not In
	   		if(!empty($tag__not_in)){
	   		   $exclude_tags = explode(",",$tag__not_in);
	   			$args['tag__not_in'] = $exclude_tags;
	   		}

	   	   // Date (not using date_query as there was issue with year/month archives)
	   		if(!empty($year)){
	      		$args['year'] = $year;
	   	   }
	   	   if(!empty($month)){
	      		$args['monthnum'] = $month;
	   	   }
	   	   if(!empty($day)){
	      		$args['day'] = $day;
	   	   }

	   	   // Meta Query
	   		if(!empty($meta_key) && !empty($meta_value) || !empty($meta_key) && $meta_compare !== "IN"){

	      		// Parse multiple meta query
	            $meta_query_total = count(explode(":", $meta_key)); // Total meta_query objects
	            $meta_keys = explode(":", $meta_key); // convert to array
	            $meta_value = explode(":", $meta_value); // convert to array
	            $meta_compare = explode(":", $meta_compare); // convert to array
	            $meta_type = explode(":", $meta_type); // convert to array

	            // Loop Meta Query
	            $args['meta_query'] = array(
					   'relation' => $meta_relation
	            );
					for($mq_i = 0; $mq_i < $meta_query_total; $mq_i++){
						$args['meta_query'][] = ewpq_get_meta_query($meta_keys[$mq_i], $meta_value[$mq_i], $meta_compare[$mq_i], $meta_type[$mq_i]);
					}
	   	   }

	         // Meta_key
	         if(!empty($meta_key)){ // ordering by meta value
		         if (strpos($orderby, 'meta_value') !== false) { // Only order by meta_key, if $orderby is set to meta_value{_num}
		            $meta_key_single = explode(":", $meta_key);
	               $args['meta_key'] = $meta_key_single[0];
	            }
	         }

	         // Author
	   		if(!empty($author_id)){
	   			$args['author'] = $author_id;
	   		}

	         // Search Term
	   		if(!empty($s)){
	   			$args['s'] = $s;
	   		}

	   		// Custom Args
	   		if(!empty($custom_args)){
	   			$custom_args_array = explode(";",$custom_args); // Split the $custom_args at ','
	   			foreach($custom_args_array as $argument){ // Loop each $argument
	      			$argument = preg_replace('/\s+/', '', $argument); // Remove all whitespace
	   			   $argument = explode(":",$argument);  // Split the $argument at ':'
	   			   $argument_arr = explode(",", $argument[1]);  // explode $argument[1] at ','
	   			   if(sizeof($argument_arr) > 1){
	   			      $args[$argument[0]] = $argument_arr;
	   			   }else{
	   			      $args[$argument[0]] = $argument[1];
	   			   }

	   			}
	   		}

	   		// include posts
	   		if(!empty($post__in)){
	   			$post__in = explode(",",$post__in);
	   			$args['post__in'] = $post__in;
	   		}

	   		// Exclude posts
	   		if(!empty($post__not_in)){
	   			$post__not_in = explode(",",$post__not_in);
	   			$args['post__not_in'] = $post__not_in;
	   		}

	         // Language
	   		if(!empty($lang)){
	   			$args['lang'] = $lang;
	   		}

	   		// WP_Query
	   		$eq_query = new WP_Query( $args );
	         $eq_total_posts = $eq_query->found_posts - $offset;
	         $output = '';

	   		// The Loop
	   		if ($eq_query->have_posts()) :

	   		   $eq_count = $paged * $posts_per_page - $posts_per_page; // Count items
	   		   $output .= '<div class="wp-easy-query'.$paging_style.''.$paging_color.''.$paging_arrows.'" data-total-posts="'. $eq_total_posts .'">';
	   			$output .= '<div class="wp-easy-query-posts">';
	   			$output .= '<' . $container . ' class="'. $classes.'">';
	   			while ($eq_query->have_posts()): $eq_query->the_post();
	   				$eq_count++;
	   	         ob_start();
	      			$file = ewpq_get_current_template($template, $template_type);
	      			include($file);
	      			$output .= ob_get_clean();
	            endwhile;
	            wp_reset_query();
	   			$output .= '</div>';
	   			$output .= '</' . $container . '>';

	      		// Paging
	      		if($paging === 'true'){
	         		ob_start();
	      			include(EQ_PAGING);
	      			$output .= ob_get_clean();
	   			}

	   			$output .= '</div>';

	         endif;

	   		return $output;

	   	} else {

		   	$file = self::eq_get_template_path() . '/query-'. $id .'.php';
		   	if(file_exists($file)){
					ob_start();
	   			include($file);
	   			$output = ob_get_clean();
					return $output;
				}
	   	}
   	}



   	/**
       * eq_get_template_path
       * Get absolute path to directory base
       *
       * Multisite installs directories will be `uploads/sites/{id}/eq_templates`
       *
       * @return $path;
       * @since 2.3
       */
      public static function eq_get_template_path(){
         $upload_dir = wp_upload_dir();
         $path = apply_filters( 'alm_eq_path', $upload_dir['basedir']. '/eq_templates' );
         return $path;
      }



      /**
       * eq_mkdir
       * Create template directory
       *
       * @since 2.3
       */
      public static function eq_mkdir($dir){

	      // Does $dir exist?
	      if( !is_dir($dir) ) {
		      wp_mkdir_p($dir);

	      	// Check again after creating it (permission checker)
		      if( !is_dir($dir) ) {
			      echo __('Error creating template directory', 'easy-query') . ' - '. $dir;
			   }
	      }
      }


   }


   /*
   *  AjaxLoadMore
   *  The main function responsible for returning the one true EasyWPQuery Instance to functions everywhere.
   *
   *  @since 1.0.0
   */

   function EasyQuery(){
   	global $easy_query;
   	if( !isset($easy_query)){
   		$easy_query = new EasyQuery();
   	}
   	return $easy_query;
   }

   // initialize
   EasyQuery();

endif; // class_exists check



/* EDD Software Licensing */
define( 'EASY_QUERY_STORE_URL', 'https://connekthq.com' );
define( 'EASY_QUERY_ITEM_NAME', '5765' );
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include( dirname( __FILE__ ) . '/vendor/EDD_SL_Plugin_Updater.php' );
}
function easy_query_plugin_updater() {
	$license_key = trim( get_option( 'easy_query_license_key' ) ); // retrieve our license key from the DB
	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( EASY_QUERY_STORE_URL, __FILE__, array(
			'version' 	=> EQ_VERSION,
			'license' 	=> $license_key,
			'item_id'   => EASY_QUERY_ITEM_NAME,
			'author' 	=> 'Darren Cooney'
		)
	);
}
add_action( 'admin_init', 'easy_query_plugin_updater', 0 );
/* end: Software Licensing */
