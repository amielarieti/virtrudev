<?php
/**
 * Elementor Plugin.
 *
 * @since 1.0.0
 * @package ajax-load-more-elementor
 */

namespace ALMElementorPosts;

/**
 * Main Plugin class
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 * @author @dcooney
	 */
	private static $instance = null;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 * @author @dcooney
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @author @dcooney
	 */
	public function widget_scripts() {}

	/**
	 * Load widgets files
	 *
	 * @since 1.0.0
	 * @author @dcooney
	 */
	private function include_widgets_files() {
		require_once __DIR__ . '/widget.php';
	}

	/**
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 * @author @dcooney
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files.
		$this->include_widgets_files();

		// Register Widgets.
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ALMElementorPosts() );
	}

	/**
	 * Register plugin action hooks and filters
	 *
	 * @since 1.0.0
	 * @author @dcooney
	 */
	public function __construct() {

		// Register widget scripts.
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets.
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
	}
}

// Instantiate Plugin Class.
Plugin::instance();
