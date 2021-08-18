<?php
/**
 * Elementor Widget.
 *
 * @since 1.0.0
 * @package ajax-load-more-elementor
 */

namespace ALMElementorPosts\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Widget class
 *
 * @since 1.0.0
 */
class ALMElementorPosts extends Widget_Base {

	const LOGO_PATH = ALM_ELEMENTOR_URL . '/module/img/alm-elementor.png';
	const CSS       = 'font-family: Helvetica, Arial, sans-serif; cursor: default; min-height: 120px; padding: 100px 20px 20px; width: 100%; background: #f7f7f7 url(' . self::LOGO_PATH . ') no-repeat center 40%; border: 1px solid #efefef; text-align: center; font-size: 12px; font-weight: 600;"';

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 * @return string Widget name.
	 * @author @dcooney
	 */
	public function get_name() {
		return 'ajax-load-more-elementor-posts';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 * @return string Widget title.
	 * @author @dcooney
	 */
	public function get_title() {
		return __( 'Elementor Posts Widget Connector', 'alm-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 * @return string Widget icon.
	 * @author @dcooney
	 */
	public function get_icon() {
		return 'fa fa-plug';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 * @author @dcooney
	 */
	public function get_categories() {
		return [ 'ajax-load-more' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 * @author @dcooney
	 */
	protected function _register_controls() {

		$options = get_option( 'alm_settings' );
		$options = ( has_filter( 'alm_settings' ) ) ? apply_filters( 'alm_settings', $options ) : $options;
		$style   = ( isset( $options['_alm_btn_color'] ) ) ? ' ' . $options['_alm_btn_color'] : ' default';

		$this->start_controls_section( 'section_content',
			[
				'label' => __( 'Shortcode Builder', 'alm-elementor' ),
			]
		);

		$this->add_control( 'target',
			[
				'label'       => __( 'Target', 'alm-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Enter the ID or Classname of the Elementor Posts widget.', 'alm-elementor' ),
				'placeholder' => '#alm-posts',
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control( 'url',
			[
				'label'       => __( 'Update URL', 'alm-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Update the browser URL as pages are loaded and come into view.', 'alm-elementor' ),
				'default'     => 'yes',
			]
		);

		$this->add_control( 'loading_style',
			[
				'label'       => __( 'Loading Style', 'alm-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					''                        => __('-- Make a Selection --', 'alm-elementor' ),
					'default'                 => __('Button - Default', 'alm-elementor' ),
					'blue'                    => __('Button - Blue', 'alm-elementor' ),
					'green'                   => __('Button - Green', 'alm-elementor' ),
					'purple'                  => __('Button - Purple', 'alm-elementor' ),
					'grey'                    => __('Button - Grey', 'alm-elementor' ),
					'white'                   => __('Button - White', 'alm-elementor' ),
					'light-grey'              => __('Button - Light Grey', 'alm-elementor' ),
					'infinite classic'        => __('Infinite Scroll - Classic', 'alm-elementor' ),
					'infinite skype'          => __('Infinite Scroll - Skype', 'alm-elementor' ),
					'infinite ring'           => __('Infinite Scroll - Ring', 'alm-elementor' ),
					'infinite fading-blocks'  => __('Infinite Scroll - Fading Blocks', 'alm-elementor' ),
					'infinite fading-circles' => __('Infinite Scroll - Fading Circles', 'alm-elementor' ),
					'infinite chasing-arrows' => __('Infinite Scroll - Chasing Arrows', 'alm-elementor' ),
				],
				'description' => __( 'Select an Ajax loading style - choose between a Button or Infinite Scroll.', 'alm-elementor' ),
				'default'     => $style,
				'label_block' => true,
				'separator'   => 'before',
			]
		);

		$this->add_control( 'button_label',
			[
				'label'       => __( 'Button Label', 'alm-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'The text of the Load More button.', 'alm-elementor' ),
				'placeholder' => apply_filters( 'alm_button_label', __( 'Older Posts', 'ajax-load-more' ) ),
				'default'     => apply_filters( 'alm_button_label', __( 'Older Posts', 'ajax-load-more' ) ),
				'separator'   => 'before',
				'label_block' => true,
			]
		);

		$this->add_control( 'button_loading_label',
			[
				'label'       => __( 'Button Loading Label', 'alm-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Update the button label while content is loading.', 'alm-elementor' ),
				'placeholder' => __( 'Loading...', 'alm-elementor' ),
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control( 'scroll',
			[
				'label'       => __( 'Scrolling', 'alm-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Load additional pages as users scroll the page.', 'alm-elementor' ),
				'default'     => 'yes',
				'separator'   => 'before',
			]
		);

		$this->add_control( 'pause_override',
			[
				'label'     => __( 'Scroll Override', 'alm-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' =>
					[
						'scroll' => 'yes',
					],
				'description' => __( 'Allow scrolling to initiate the loading of posts. If false, users will have to click the `Load More` button to begin.', 'alm-elementor' ),
				'default'     => 'yes',
			]
		);

		$this->add_control( 'scroll_distance',
			[
				'label'     => __( 'Scroll Distance', 'alm-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'condition' =>
					[
						'scroll' => 'yes',
					],
				'description' => __( 'The distance (in pixels) from the bottom of the screen to trigger a post load.', 'alm-elementor' ),
				'default'     => '100',
			]
		);



		$this->add_control( 'previous_link_label',
			[
				'label'       => __( 'Previous Posts Link Label', 'alm-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'The text displayed when the previous posts link is rendered. Leave empty to not render component.', 'alm-elementor' ),
				'placeholder' => apply_filters( 'alm_previous_link_label', __( '← Previous Posts', 'ajax-load-more' ) ),
				'default'     => apply_filters( 'alm_previous_link_label', __( '← Previous Posts', 'ajax-load-more' ) ),
				'separator'   => 'before',
				'label_block' => true,
				'condition'   => [
					'url' => 'yes',
				],
			]
		);

		$this->add_control( 'controls',
			[
				'label'       => __( 'Back/Fwd Button', 'alm-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Enable navigation between Ajax loaded content using back and forward browser buttons.', 'alm-elementor' ),
				'default'     => 'yes',
				'condition'   => [
					'url' => 'yes',
				],
			]
		);

		$this->add_control( 'scrolltop',
			[
				'label'       => __( 'Scroll Offset', 'alm-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'Set the offset top position of the window. The scroll offset determines at which point the URL will update while scrolling through Ajax loaded pages.', 'alm-elementor' ),
				'default'     => '50',
				'separator'   => 'after',
				'condition'   => [
					'url' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		if ( has_action( 'alm_cache_installed' ) ) {

			$this->start_controls_section( 'integrations',
				[
					'label' => __( 'Integrations', 'alm-elementor' ),
				]
			);
			$this->add_control( 'cache',
				[
					'label'       => __( 'Cache', 'alm-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'description' => __( 'Enable Ajax Load More Cache on this instance.', 'alm-elementor' ),
					'type'        => Controls_Manager::SELECT,
					'options' =>
						[
							'true'  => __( 'True', 'alm-elementor' ),
							'false' => __( 'false', 'alm-elementor' ),
						],
					'default'     => 'false',
				]
			);
			$this->end_controls_section();

		}
	}

	/**
	 * Render the widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 * @access protected
	 * @author @dcooney
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$target          = $settings['target'];
		$url             = $settings['url'] && 'yes' === $settings['url'] ? 'true' : 'false';
		$controls        = $settings['controls'] && 'yes' === $settings['controls'] ? 'true' : 'false';
		$link_label      = $settings['previous_link_label'];
		$scrolltop       = $settings['scrolltop'] ? $settings['scrolltop'] : '50';
		$loading_style   = $settings['loading_style'];
		$button_label    = $settings['button_label'];
		$button_loading  = $settings['button_loading_label'];
		$scroll          = $settings['scroll'];
		$scroll_distance = $settings['scroll_distance'];
		$pause_override  = $settings['pause_override'];
		$cache           = ( $settings['cache'] && 'true' === $settings['cache'] ) ? true : false;

		$shortcode  = '[ajax_load_more';
		$shortcode .= ' elementor="posts"';
		$shortcode .= ' elementor_target="' . $target . '"';
		$shortcode .= ' elementor_url="' . $url . '"';
		$shortcode .= ' elementor_link_label="' . $link_label . '"';
		$shortcode .= ' elementor_controls="' . $controls . '"';
		$shortcode .= ' elementor_scrolltop="' . $scrolltop . '"';

		$shortcode .= ( $loading_style ) ? ' loading_style="' . $loading_style . '"' : '';

		$shortcode .= ( $button_label ) ? ' button_label="' . $button_label . '"' : '';
		$shortcode .= ( $button_loading ) ? ' button_loading_label="' . $button_loading . '"' : '';

		$shortcode .= ( 'yes' === $scroll ) ? ' scroll="true"' : ' scroll="false"';
		$shortcode .= ( 'yes' === $pause_override && 'yes' === $scroll ) ? ' pause_override="true"' : '';
		$shortcode .= ( 'yes' === $scroll ) ? ' scroll_distance="' . $scroll_distance . '"' : '';

		if ( $cache ) { // Cache.
			$cache_id   = str_replace( '#', '', $target );
			$cache_id   = str_replace( '.', '', $cache_id );
			$shortcode .= ' cache="true" cache_id="' . strtolower( $cache_id ) . '"';
		}

		$shortcode .= ']';

		$shortcode = do_shortcode( shortcode_unautop( $shortcode ) );
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			echo '<div style="' . self::CSS . '">';
			echo esc_html__( 'Elementor Widget Connector for Ajax Load More', 'alm-elementor' );
			echo '<br/><span style="opacity: 0.75; font-weight: 400;">';
			echo esc_html__( 'Preview or launch the live URL to view Ajax Load More content.', 'alm-elementor' );
			echo '</span>';
			echo '</div>';
		} else {
			echo '<div class="elementor-posts-alm-shortcode">' . $shortcode . '</div>';
		}
	}

	/**
	 * Render shortcode widget output in the editor.
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		view.addInlineEditingAttributes( 'target', 'none' );
		#>
		<?php
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
			<div style="<?php echo self::CSS; ?>">
				<?php _e( 'Elementor Widget Connector for Ajax Load More', 'alm-elementor' ); ?>
				<br/>
				<span style="opacity: 0.75; font-weight: 400;">';
				<?php _e('Preview or launch the live URL to view Ajax Load More content.', 'alm-elementor' ); ?>
				</span>
			</div>
		<?php }
	}
}
