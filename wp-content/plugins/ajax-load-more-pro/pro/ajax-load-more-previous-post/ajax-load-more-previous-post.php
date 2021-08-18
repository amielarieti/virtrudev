<?php
/*
Plugin Name: Ajax Load More: Single Post
Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/single-post/
Description: Ajax Load More add-on for infinite scrolling single posts
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: https://connekthq.com
Version: 1.5.2
License: GPL
Copyright: Darren Cooney & Connekt Media
*/

// @codingStandardsIgnoreStart

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ALM_PREV_POST_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_PREV_POST_URL', plugins_url( '', __FILE__) );
define( 'ALM_PREV_POST_VERSION', '1.5.2' );
define( 'ALM_PREV_POST_RELEASE', 'February 11, 2021' );

/**
 * Activation hook.
 *
 * @since 1.0
 */
register_activation_hook( __FILE__, 'alm_single_post_install' );
function alm_single_post_install() {
   // If Ajax Load More is activated.
   if(!is_plugin_active('ajax-load-more/ajax-load-more.php')){
   	die('You must install and activate <a href="https://wordpress.org/plugins/ajax-load-more/">Ajax Load More</a> before installing the ALM Single Post Add-on.');
	}
}

if( !class_exists('ALM_SINGLEPOST') ):

   class ALM_SINGLEPOST {

		/**
		 * Construct class function.
		 */
   	public function __construct() {
   		add_action( 'alm_prev_post_installed', array( &$this, 'alm_prev_post_installed') );
   		add_action( 'alm_single_post_installed', array( &$this, 'alm_single_post_installed') );
      	add_action( 'wp_ajax_alm_get_single', array(&$this, 'alm_query_single_post') );
   		add_action( 'wp_ajax_nopriv_alm_get_single', array(&$this, 'alm_query_single_post') );
   	   add_filter( 'alm_single_post_inc', array( &$this, 'alm_single_post_inc' ), 10, 5 );
   	   add_filter( 'alm_single_post_args', array( &$this, 'alm_single_post_args' ), 10, 2 );
   		add_filter( 'alm_single_post_shortcode', array( &$this, 'alm_single_post_shortcode'), 10, 10 );
   		add_action( 'alm_prev_post_settings', array( &$this, 'alm_prev_post_settings') );
   		add_action( 'wp_enqueue_scripts', array( &$this, 'alm_single_post_enqueue_scripts' ) );
   		add_action( 'posts_where', array( &$this, 'alm_single_query_where' ), 10, 2);
   		load_plugin_textdomain( 'ajax-load-more-single-post', false, dirname(plugin_basename( __FILE__ )) . '/lang/'); //load text domain
   	}

      /**
   	 * Set WP Query params using `posts_where` clause
   	 * Force is_single() and is_singular() to be true in the ajax call.
   	 *
		 * @author ConnektMedia
   	 * @since 1.3.3
   	 */
		public function alm_single_query_where($where, $query) {

			$alm_single_query = $query->get('alm_query');

			if ( $alm_single_query && $alm_single_query === 'single_posts') {

				global $wp_query;
				$wp_query->is_single = true;
				$wp_query->is_feed = true;
				$wp_query->is_singular = true;
				$wp_query->in_the_loop = true;

				// Remove errors
				error_reporting(0);
			}
			return $where;
		}

      /**
   	 * Enqueue Single Post scripts.
   	 *
   	 * @since 1.0
   	 */
   	public function alm_single_post_enqueue_scripts() {
      	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
   		wp_register_script( 'alm-single-posts', plugins_url( '/dist/js/alm-single-posts' . $suffix . '.js', __FILE__ ), array('ajax-load-more'),  ALM_PREV_POST_VERSION, true );
   	}

   	/**
		 * Get the post id and return the next post ID via Ajax.
		 *
		 * @author ConnektMedia
		 * @since 1.0
		 * @return JSON
		 */
   	public function alm_query_single_post() {

         $init            = ( isset( $_GET['init'] ) ) ? $_GET['init'] : false;
         $id              = ( isset ($_GET['id'] ) ) ? $_GET['id'] : '';
         $exclude_post_id = ( isset( $_GET['initial_id'] ) ) ? $_GET['initial_id'] : '';
         $tax             = ( isset( $_GET['taxonomy'] ) ) ? $_GET['taxonomy'] : '';
         $exclude_terms   = ( isset( $_GET['excluded_terms'] ) ) ? $_GET['excluded_terms'] : '';
     		$postType        = ( isset( $_GET['post_type'] ) ) ? $_GET['post_type'] : 'post';
			$order           = ( isset( $_GET['order'] ) && !empty( $_GET['order'] ) ) ? $_GET['order'] : 'previous';

			// Order - If order is `latest` and first run and ordered by latest, set posts to load in order by date.
			$order = ($init === 'false' && $order === 'latest') ? 'previous' : $order;

			$array = array(
				'init'            => $init,
				'id'              => $id,
				'exclude_post_id' => $exclude_post_id,
				'tax'             => $tax,
				'exclude_terms'   => $exclude_terms,
				'postType'        => $postType,
				'order'           => $order
			);

			$data = ALM_SINGLEPOST::alm_get_single_posts_data ( $array );
			wp_send_json($data);

		}

		/**
		 * Get single post data.
		 *
		 * @author ConnektMedia
		 * @param array $array The array containing query data.
		 * @return array
		 */
		public static function alm_get_single_posts_data( $array ){

			if ( $array && $array['id'] ) {

	         switch ( $array['order'] ) {

		         // Get the latest (newest) post
		         case 'latest':
			         $data = self::alm_get_latest_post($array['exclude_post_id'], $array['postType'], $array['tax'], $array['exclude_terms']);
			         return $data;
		         break;

		         // Get next post ordered by date
		         case 'next':
			         $data = self::alm_get_next_post($array['id'], $array['tax'], $array['exclude_terms'], $array['exclude_post_id']);
		            return $data;
		         break;

		         // Get previous post ordered by date
		         case 'previous':
			         $data = self::alm_get_previous_post($array['id'], $array['tax'], $array['exclude_terms'], $array['exclude_post_id']);
		            return $data;
		         break;

		         // Get post ID array (use as default for ease)
		         default :
		         	$data = self::alm_get_post_in_array($array['id'], $array['order']);
			         return $data;
		         break;
	         }
	      }
      }

      /**
		 * Get the next post in the array.
		 *
		 * @author ConnektMedia
		 * @return JSON
		 * @since 1.0
		 * @updated 1.3
		 */
      public static function alm_get_post_in_array( $id, $array ){

	      global $post;

	      // Store the existing post object for later so we don't lose it
			$oldGlobal = $post;

			$previous_post = '';

			$array = explode(',', str_replace(' ', '', $array)); // Remove whitespace and convert to array

			if ( in_array( $id, $array ) ) {
   			// ID found in array
				$length = count($array);
				$index = array_search($id, $array);
				if($index < $length - 1){ // Last element
					$previous_post = get_post($array[$index+1]);
				}
			} else {
   			// Get first element in array
				$previous_post = get_post($array[0]);
			}

	      // Reset global $post object
			$post = $oldGlobal;

         // Build the $data object
			$data = self::alm_build_data_object($id, $previous_post);

         return $data;
	   }

      /**
		 * Get the previous post by date using `previous_post` method
		 *
		 * @author ConnektMedia
		 * @return JSON
		 * @since 1.3
		 */
      public static function alm_get_previous_post($id, $tax, $exclude_terms, $exclude_post_id){

	      global $post;

         // Store the existing post object for later so we don't lose it
			$oldGlobal = $post;

			// Get post object
			$post = get_post($id);

			// Get Previous Post
			$previous_post = (!empty($tax)) ? get_previous_post(true, $exclude_terms, $tax) : get_previous_post(false, $exclude_terms);

			// If Previous Post === Original post
			if($previous_post && $previous_post->ID == $exclude_post_id){
				$post = get_post($previous_post->ID);
				$previous_post = (!empty($tax)) ? get_previous_post(true, $exclude_terms, $tax) : get_previous_post(false, $exclude_terms);
         }

			// Reset global $post object
			$post = $oldGlobal;

			// Build the $data object
			$data = self::alm_build_data_object($id, $previous_post);

         return $data;
      }

      /**
		 * Get the next post by date using `next_post` method.
		 *
		 * @author ConnektMedia
		 * @return JSON
		 * @since 1.3
		 */
      public static function alm_get_next_post($id, $tax, $exclude_terms, $exclude_post_id){

	      global $post;

         // Store the existing post object for later so we don't lose it
			$oldGlobal = $post;

			// Get post object
			$post = get_post($id);

			// Get Previous Post
			$next_post = (!empty($tax)) ? get_next_post(true, $exclude_terms, $tax) : get_next_post(false, $exclude_terms);

			// If Previous Post === Original post
			if($next_post && $next_post->ID == $exclude_post_id){
				$post = get_post($previous_post->ID);
				$next_post = (!empty($tax)) ? get_next_post(true, $exclude_terms, $tax) : get_next_post(false, $exclude_terms);
         }

			// Reset global $post object
			$post = $oldGlobal;

			// Build the $data object
			$data = self::alm_build_data_object($id, $next_post);

         return $data;
      }

      /**
		 * Get the latest (newest) post and return the data.
		 *
		 * @author ConnektMedia
		 * @since 1.3
		 * @return JSON
		 */
      public static function alm_get_latest_post($id, $postType, $tax, $exclude_terms){

	      global $post;

         // Store the existing post object for later so we don't lose it
			$oldGlobal = $post;

			// Get post object
	      $previous_post = get_post( self::alm_query_latest_post_id($id, $postType, $tax, $exclude_terms) );

			// Reset global $post object
			$post = $oldGlobal;

			// Build the $data object
			$data = self::alm_build_data_object($id, $previous_post);

	      return $data;
      }

      /**
		 *  Run a get_posts function to get the most recent post ID.
		 *
		 *  @return string (ID)
		 *  @since 1.3
		 */
      public static function alm_query_latest_post_id($id, $postType, $tax, $exclude_terms){

	      // Get latest post not including the current
	      $args = array(
		      'post_type' => $postType,
		      'posts_per_page' => 1,
		      'post__not_in' => array($id),
		      'orderby' => 'date',
		      'order' => 'DESC',
		      'fields' => 'ids',
		      'suppress_filters' => false
	      );

	      // If $in_same_term, loop all tax terms and query based on the terms
	      if($tax){
		      $terms = get_the_terms( $id, $tax );
		      if($terms){
			      $found_terms = [];
			      foreach ( $terms as $term ) {
			      	$found_terms[] = $term->slug;
				   }
			      $args['tax_query'][] = array(
				      'taxonomy' 	=> $tax,
				      'field' 		=> 'slug',
				      'terms' 		=> $found_terms,
				      'operator' 	=> 'IN'
			      );
		      }
	      }

	      // Exclude certain terms
	      if($exclude_terms){
   	      $exclude_terms = explode(',', $exclude_terms);
   	      foreach ( $exclude_terms as $id ) {
               $term_data = get_term( $id );
               if($term_data){
                  $args['tax_query'][] = array(
   				      'taxonomy' 	=> $term_data->taxonomy,
   				      'field' 		=> 'term_id',
   				      'terms' 		=> $id,
   				      'operator' 	=> 'NOT IN'
   			      );
			      }
            }
	      }

	      // Get the posts
	      $posts = get_posts($args);

	      if($posts){
		      foreach($posts as $post_id){
				   return $post_id;
		      }
	      } else {
		      return null;
	      }
      }

      /**
   	 *  Build the data object based on the $previous_post object.
   	 *
   	 * @since 1.3
   	 */
      public static function alm_build_data_object($id, $previous_post){
	      $data = array();
	      if($previous_post){
				$data['has_previous_post'] = true;
				$data['prev_id'] = $previous_post->ID;
				$data['prev_slug'] = $previous_post->post_name;
				$data['prev_permalink'] = get_permalink($previous_post->ID);

				$title = '';

				// Yoast SEO Title
				if(function_exists('wpseo_replace_vars')){
					$title = self::alm_convert_yoast_title($previous_post);
				}
				if(empty($title)){
					$title = strip_tags(html_entity_decode(get_the_title($previous_post->ID)));
				}

				$data['prev_title'] = $title;

	      } else {
		      $data['has_previous_post'] = false;

	      }

			$data['current_id'] = $id;
			$data['permalink'] = get_permalink($id);
			$data['title'] = strip_tags(get_the_title($id));

			return $data;
      }

      /**
   	 *  Get the Yoast page title.
   	 *
   	 *  @param $post  Object
   	 *  @since 1.4.4
   	 */
		public static function alm_convert_yoast_title($post) {
			$yoast_title = get_post_meta( $post->ID, '_yoast_wpseo_title', true );
			if(empty($yoast_title)) {
				$wpseo_titles = get_option( 'wpseo_titles', [] );
				$yoast_title  = isset( $wpseo_titles[ 'title-' . $post->post_type ] ) ? $wpseo_titles[ 'title-' . $post->post_type ] : get_the_title($post->ID);
			}
			return wpseo_replace_vars( $yoast_title, $post );
		}

		/**
   	 *  Set the `single_post` query args.
   	 *
   	 *  @param int $id
   	 *  @param array $post_type
   	 *  @return $args
   	 *  @since 1.0
   	 */
   	function alm_single_post_args($id, $post_type){
      	$args = array(
         	'post__in' => array( $id ),
            'post_type' => $post_type,
   			'posts_per_page' => 1
         );
         return $args;
   	}

   	/**
   	 *  Get the content for the first single post include.
   	 *
   	 *  @return ob_get_contents()
   	 *  @updated 1.3
   	 *  @since 1.0
   	 */
   	function alm_single_post_inc($repeater, $repeater_type, $theme_repeater, $id, $post_type){
         ob_start();
	   	if($theme_repeater != 'null' && has_filter('alm_get_theme_repeater')){
		   	// Theme Repeater
				do_action('alm_get_theme_repeater', $theme_repeater, 1, 1, 1, 1, '');
			}else{
				// Standard Repeaters
            include(alm_get_current_repeater($repeater, $repeater_type));
			}
			$return = ob_get_contents();
			ob_end_clean();
			return $return;
	   }

      /**
   	 *  Build Next Post shortcode params and send back to core ALM.
   	 *
   	 *  @since 1.0
   	 */
   	function alm_single_post_shortcode( $id, $order, $tax, $excluded, $progress_bar, $options, $target, $query_order, $query_args, $preview ){

   		$return = ' data-single-post="true"';
			$return .= ' data-single-post-id="' . $id . '"';

			// Custom Query.
			if( ! empty( $order ) && $order === 'query' ) {
		   	$return .= ' data-single-post-query="' . $query_order . '"';
		   	$return .= ' data-single-post-order="' . self::alm_single_post_custom_query( $id, $query_order, $query_args ) . '"';
			}

			// Post Order.
		   if( ! empty( $order ) && $order !== 'query' ){
				$return .= ' data-single-post-order="' . $order . '"';
			}

			// Taxonomy.
		   if(!empty($tax)){
				$return .= ' data-single-post-taxonomy="' . $tax . '"';
			}

			// Excluded Terms.
		   if(!empty($excluded)){
				$return .= ' data-single-post-excluded-terms="' . $excluded . '"';
			}

			// Target.
		   if(!empty($target)){
		   	$return .= ' data-single-post-target="' . $target . '"';
			}

			// Preview.
		   if(!empty($preview) && $preview !== 'false'){
				// Convert to array.
				$preview_arr = explode( ':', $preview );
				$preview_label = isset( $preview_arr[0] ) ? $preview_arr[0] : apply_filters( 'alm_single_post_preview_button_label', 'Continue Reading' );
				$preview_height = isset( $preview_arr[1] ) ? intval( $preview_arr[1] ) : apply_filters( 'alm_single_post_preview_height', 700 );
				$preview_element = isset( $preview_arr[2] ) ?  $preview_arr[2] : apply_filters( 'alm_single_post_preview_element', 'default' );
		   	$return .= ' data-single-post-preview="' . $preview_label . ':' . $preview_height .':' . $preview_element .'"';
			}



		   // Set scrolltop
		   $single_post_scrolltop = '30';

		   // Update settings
   		$single_post_scrolltop = (isset($options['_alm_prev_post_scrolltop'])) ? $options['_alm_prev_post_scrolltop'] : $single_post_scrolltop;

		   // Enabled Scrolling
			$single_post_enable_scroll = (isset($options['_alm_prev_post_scroll'])) ? $options['_alm_prev_post_scroll'] : 'false';
   		if(!isset($single_post_enable_scroll)){
   			$single_post_enable_scroll = 'false';
         }else{
      		if($single_post_enable_scroll == '1'){
      		   $single_post_enable_scroll = 'true';
            }else{
      		   $single_post_enable_scroll = 'false';
      		}
   		}

   		$single_post_controls = '1';
   		if(isset($options['_alm_prev_post_browser_controls'])){
   			$single_post_controls = $options['_alm_prev_post_browser_controls'];
   		}

   		// Page Title
   		$single_post_title_template = '';
   		if(isset($options['_alm_prev_post_title'])){
	   		$single_post_title_template = $options['_alm_prev_post_title'];
   		}

		   // GA send Pageview
   		if(!isset($options['_alm_prev_post_ga'])){
   			$single_post_send_pageview = 'true';
         }else{
            $single_post_send_pageview = $options['_alm_prev_post_ga'];
      		if($single_post_send_pageview == '1'){
      		   $single_post_send_pageview = 'true';
            }else{
      		   $single_post_send_pageview = 'false';
      		}
   		}

			$return .= ' data-single-post-title-template="'.$single_post_title_template.'"';
			$return .= ' data-single-post-site-title="'.get_bloginfo('name').'"';
			$return .= ' data-single-post-site-tagline="'.get_bloginfo('description').'"';
			$return .= ' data-single-post-scroll="'.$single_post_enable_scroll.'"';
			$return .= ' data-single-post-scrolltop="'.$single_post_scrolltop.'"';
			$return .= ' data-single-post-controls="'.$single_post_controls.'"';
			$return .= ' data-single-post-progress-bar="'.$progress_bar.'"';
		   $return .= ' data-single-post-pageview="'.$single_post_send_pageview.'"';

		   return $return;
		}

		/**
		 * A custom taxonomy query.
		 *
		 * @param string $id Current Post ID
		 * @param string $post_type The post type to query
		 * @param string $query The query to build
		 */
		public static function alm_single_post_custom_query($post_id, $query_order = 'previous', $query_args = null){

			// Exit if this is an ajax request.
			if( isset( $_GET ) && isset( $_GET[ 'alm_page' ] ) ) {
				return false;
			}

			if( empty( $post_id ) || empty( $query_args ) ){
				return false;
			}

			$args = ALM_QUERY_ARGS::alm_build_queryargs($query_args, true);
			$args['fields']         = 'ids';
			$args['orderby']        = 'post__in';
			$args['post__not_in']   = array( $post_id );
			$args['posts_per_page'] = apply_filters('alm_single_post_posts_per_page_' . $args['alm_id'], '40');

			// Custom Query Ordering, used with custom query ordering
			if( 'previous' === $query_order ){
				//$date = get_the_date('Y-m-d', $post_id);
				$args['date_query'] = array(
					array(
						'before' => get_the_date('F d Y g:i a', $post_id)
					),
					'inclusive' => true
				);
			}

			/*
			 * Query Hook.
			 *
			 * @return $args;
			 */
			$args = apply_filters('alm_single_post_query_args_'. $args['alm_id'], $args, $post_id);

			$alm_custom_query = new WP_Query($args);
			if($alm_custom_query->have_posts()){
				return implode( ',', array_reverse( $alm_custom_query->posts ) );
			} else {
				return '';
			}
			wp_reset_query();
		}

   	/**
		 * An empty function to determine if Previous Post is active.
		 *
		 * @author ConnektMedia
		 * @since 1.0
   	 */
   	function alm_prev_post_installed() {
   	   //Empty return
   	}

   	/**
   	 * An empty function to determine if Single Posts is active.
		 *
		 * @author ConnektMedia
		 * @since 1.0
   	 */
   	function alm_single_post_installed() {
   	   // Empty.
   	}

   	/**
		 * Create the Previous Post settings panel.
		 *
		 * @author ConnektMedia
		 * @since 1.2
   	 */
   	function alm_prev_post_settings() {

      	register_setting(
      		'alm_prev_post_license',
      		'alm_prev_post_license_key',
      		'alm_prev_post_sanitize_license'
      	);

   	   add_settings_section(
	   		'alm_prev_post_settings',
	   		'Single Post Settings',
	   		'alm_prev_post_callback',
	   		'ajax-load-more'
	   	);

	   	add_settings_field(
	   		'_alm_prev_post_title',
	   		__('Page Title Template', 'ajax-load-more-single-post' ),
	   		'alm_prev_post_title_callback',
	   		'ajax-load-more',
	   		'alm_prev_post_settings'
	   	);

	   	add_settings_field(
	   		'_alm_prev_post_ga',
	   		__('Google Analytics', 'ajax-load-more-single-post' ),
	   		'alm_prev_post_ga_callback',
	   		'ajax-load-more',
	   		'alm_prev_post_settings'
	   	);

	   	add_settings_field(
	   		'_alm_prev_post_scroll',
	   		__('Scroll to Post', 'ajax-load-more-single-post' ),
	   		'alm_prev_post_scroll_callback',
	   		'ajax-load-more',
	   		'alm_prev_post_settings'
	   	);

	   	add_settings_field(
	   		'_alm_prev_post_scrolltop',
	   		__('Scroll Top', 'ajax-load-more-single-post' ),
	   		'alm_prev_post_scrolltop_callback',
	   		'ajax-load-more',
	   		'alm_prev_post_settings'
	   	);
	   	add_settings_field(
	   		'_alm_prev_post_browser_controls',
	   		__('Back/Fwd Buttons', 'ajax-load-more-single-post' ),
	   		'_alm_prev_post_browser_controls_callback',
	   		'ajax-load-more',
	   		'alm_prev_post_settings'
	   	);
   	}

   }

	/**
    * Sanitize our license activation
	 *
	 * @author ConnektMedia
	 * @since 1.0.0
    */
   function alm_prev_post_sanitize_license( $new ) {
   	$old = get_option( 'alm_prev_post_license_key' );
   	if( $old && $old != $new ) {
   		delete_option( 'alm_prev_post_license_status' ); // new license has been entered, so must reactivate
   	}
   	return $new;
   }

   /* Next Post Settings (Displayed in ALM Core) */

	/**
	 * Next Post Setting Heading
	 *
	 * @author ConnektMedia
	 * @since 1.0
	 */
	function alm_prev_post_callback() {
	   $html = '<p>' . __('Customize your installation of the <a href="http://connekthq.com/plugins/ajax-load-more/add-ons/single-post/">Single Post</a> add-on.', 'ajax-load-more-single-post') . '</p>';

	   echo $html;
	}

	/*
	*  alm_prev_post_ga_callback
	*  Send pageviews to Google Analytics
	*
	*  @since 1.0
	*/
	function alm_prev_post_ga_callback() {
		$options = get_option( 'alm_settings' );
		if(!isset($options['_alm_prev_post_ga'])){
		   $options['_alm_prev_post_ga'] = '1';
		}

		$html = '<input type="hidden" name="alm_settings[_alm_prev_post_ga]" value="0" /><input type="checkbox" id="_alm_prev_post_ga" name="alm_settings[_alm_prev_post_ga]" value="1"'. (($options['_alm_prev_post_ga']) ? ' checked="checked"' : '') .' />';
		$html .= '<label for="_alm_prev_post_ga">'.__('Send pageviews to Google Analytics.', 'ajax-load-more-single-post').'<br/><span>Each time a post is loaded it will count as a pageview. You must have a reference to your Google Analytics tracking code on the page.</span></label>';

		echo $html;
	}



	/**
	* Update the page title
	*
	* @since 1.0
	*/
	function alm_prev_post_title_callback() {
		$options = get_option( 'alm_settings' );
		if(!isset($options['_alm_prev_post_title'])){
		   $options['_alm_prev_post_title'] = '';
		}

		$html = '<label for="_alm_prev_post_title">';
		$html .= __('The page title template is used to update the browser title each time a new post is loaded.', 'ajax-load-more-single-post');
		$html .= '<br/><span>'.__('If empty the page title will <u>NOT</u> be updated', 'ajax-load-more-single-post').'</span></label><br/>';
		$html .= '<input type="text" class="full" id="_alm_prev_post_title" name="alm_settings[_alm_prev_post_title]" value="'.$options['_alm_prev_post_title'].'" placeholder="{post-title} - {site-title}" /> ';
		$html .= '<div class="template-tags"><h4>'.__('Template Tags', 'ajax-load-more-single-post').'</h4>';
		$html .= '<ul>';
		$html .= '<li><pre>{post-title}</pre> '.__('Title of Post', 'ajax-load-more-single-post').'</li>';
		$html .= '<li><pre>{site-title}</pre> '.__('Site Title', 'ajax-load-more-single-post').'</li>';
		$html .= '<li><pre>{tagline}</pre> '.__('Site Tagline', 'ajax-load-more-single-post').'</li>';
		$html .= '</ul>';

		echo $html;
	}

	/**
	* Allow window scrolling
	*
	* @since 1.0
	*/
	function alm_prev_post_scroll_callback() {
		$options = get_option( 'alm_settings' );

		if(!isset($options['_alm_prev_post_scroll'])){
			$options['_alm_prev_post_scroll'] = '0';
		}

		$html = '<input type="hidden" name="alm_settings[_alm_prev_post_scroll]" value="0" />';
		$html .= '<input type="checkbox" name="alm_settings[_alm_prev_post_scroll]" id="alm_prev_scroll_page" value="1"'. (($options['_alm_prev_post_scroll']) ? ' checked="checked"' : '') .' />';

		$html .= '<label for="alm_prev_scroll_page">';
			$html .= __('Enable Window Scrolling.', 'ajax-load-more-single-post');
			$html .= '<span>'. __('If scrolling is enabled, the users window will scroll to the current page on \'Load More\' action.</span>', 'ajax-load-more-seo').'</span>';
		$html .= '</label>';

		echo $html;
	}

	/**
	 * Set the scrolltop value
	 *
	 * @since 1.0
	 */
	function alm_prev_post_scrolltop_callback() {

	   $options = get_option( 'alm_settings' );
	   if(!isset($options['_alm_prev_post_scrolltop'])){
			$options['_alm_prev_post_scrolltop'] = '30';
		}

		$html = '<label for="alm_settings[_alm_prev_post_scrolltop]">';
			$html .= __('Set the scrolltop position of the window when scrolling to a post.', 'ajax-load-more-single-post');
		$html .= '</label><br/>';
		$html .= '<input type="number" class="sm" id="alm_settings[_alm_prev_post_scrolltop]" name="alm_settings[_alm_prev_post_scrolltop]" step="1" min="0" value="'.$options['_alm_prev_post_scrolltop'].'" placeholder="30" /> ';

		echo $html;
	}

	/**
	 * Disable back/fwd button when URLs updated (uses replaceState vs pushState)
	 *
	 * @since 1.2.2
	 */
	function _alm_prev_post_browser_controls_callback() {

	   $options = get_option( 'alm_settings' );

		if(!isset($options['_alm_prev_post_browser_controls'])){
			$options['_alm_prev_post_browser_controls'] = '1';
		}

		$html = '<input type="hidden" name="alm_settings[_alm_prev_post_browser_controls]" value="0" />';
		$html .='<input type="checkbox" id="_alm_prev_post_browser_controls" name="alm_settings[_alm_prev_post_browser_controls]" value="1"'. (($options['_alm_prev_post_browser_controls']) ? ' checked="checked"' : '') .' />';
		$html .= '<label for="_alm_prev_post_browser_controls">'.__('Enable Back/Fwd Browser Buttons.', 'ajax-load-more-single-post');
			$html .= '<span>'.__('Allow users to navigate Ajax generated content using the back and forward browser buttons.', 'ajax-load-more-single-post').'</span>';
		$html .= '</label>';

		echo $html;
	}

   /**
    * The main function responsible for returning Ajax Load More Single Post.
    *
    * @since 1.0
    */
   function ALM_SINGLEPOST() {
   	global $ALM_SINGLEPOST;
   	if(!isset($ALM_SINGLEPOST)){
   		$ALM_SINGLEPOST = new ALM_SINGLEPOST();
   	}
   	return $ALM_SINGLEPOST;
   }

   ALM_SINGLEPOST(); // initialize

endif; // class_exists check


/**
 * Software Licensing.
 *
 * @author ConnektMedia
 * @since 1.0
 */
function alm_single_post_updater() {
	if ( ! has_action( 'alm_pro_installed' ) && class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		$license_key = trim( get_option( 'alm_prev_post_license_key' ) );
		$edd_updater = new EDD_SL_Plugin_Updater(
			ALM_STORE_URL,
			__FILE__,
			array(
				'version' 	=> ALM_PREV_POST_VERSION,
				'license' 	=> $license_key,
				'item_id'   => ALM_PREV_POST_ITEM_NAME,
				'author' 	=> 'Darren Cooney'
			)
		);
	}
}
add_action( 'admin_init', 'alm_single_post_updater', 0 );
