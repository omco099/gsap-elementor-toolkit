<?php
/**
 * Elementor animation controls.
 *
 * @package GSAP_Elementor_Toolkit\Elementor
 */

namespace GSAP_Elementor_Toolkit\Elementor;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use GSAP_Elementor_Toolkit\Animations\Preset_Library;

/**
 * Registers GSAP controls for Elementor elements.
 *
 * The user first picks an Animation Type (entrance, scroll, mouse, loop, text);
 * only that type's controls are shown. Every control is `frontend_available`
 * so the editor preview can read it live.
 *
 * Each type is implemented in assets/js/effects/{type}.js.
 */
class Animation_Controls {

	/**
	 * Section ID.
	 */
	public const SECTION_ID = 'gsap_animation';

	/**
	 * Enable control ID.
	 */
	public const ENABLE_CONTROL = 'gsap_enable';

	/**
	 * Animation type control ID.
	 */
	public const TYPE_CONTROL = 'gsap_type';

	/**
	 * Preset library.
	 *
	 * @var Preset_Library
	 */
	private Preset_Library $presets;

	/**
	 * Constructor.
	 *
	 * @param Preset_Library|null $presets Preset library.
	 */
	public function __construct( ?Preset_Library $presets = null ) {
		$this->presets = $presets ?? new Preset_Library();
	}

	/**
	 * Register GSAP controls.
	 *
	 * @param Element_Base $element Elementor element.
	 */
	public function register_controls( Element_Base $element ): void {

		$element->start_controls_section(
			self::SECTION_ID,
			array(
				'label' => esc_html__( 'GSAP Animation', 'gsap-elementor-toolkit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$this->add(
			$element,
			self::ENABLE_CONTROL,
			array(
				'label'        => esc_html__( 'Enable Animation', 'gsap-elementor-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				// Adds class "gsap-et-yes" to the wrapper: hides it until the animation starts.
				'prefix_class' => 'gsap-et-',
			),
			array()
		);

		$this->add(
			$element,
			self::TYPE_CONTROL,
			array(
				'label'   => esc_html__( 'Animation Type', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'entrance',
				'options' => array(
					'entrance' => esc_html__( 'Entrance (appear)', 'gsap-elementor-toolkit' ),
					'scroll'   => esc_html__( 'Scroll effects', 'gsap-elementor-toolkit' ),
					'mouse'    => esc_html__( 'Mouse effects', 'gsap-elementor-toolkit' ),
					'loop'     => esc_html__( 'Loop (always moving)', 'gsap-elementor-toolkit' ),
					'text'     => esc_html__( 'Text animation', 'gsap-elementor-toolkit' ),
				),
			),
			array()
		);

		$this->register_entrance( $element );
		$this->register_text( $element );
		$this->register_scroll( $element );
		$this->register_mouse( $element );
		$this->register_loop( $element );
		$this->register_timing( $element );

		$this->add(
			$element,
			'gsap_disable_on',
			array(
				'label'       => esc_html__( 'Disable On', 'gsap-elementor-toolkit' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'default'     => array(),
				'options'     => array(
					'mobile' => esc_html__( 'Mobile', 'gsap-elementor-toolkit' ),
					'tablet' => esc_html__( 'Tablet', 'gsap-elementor-toolkit' ),
				),
				'separator'   => 'before',
			),
			array()
		);

		/**
		 * Lets the Pro add-on append its own controls inside this section.
		 *
		 * @param Element_Base $element Elementor element.
		 */
		do_action( 'gsap_elementor_toolkit/controls/after', $element );

		$element->end_controls_section();
	}

	/**
	 * Entrance: preset, distance and stagger.
	 *
	 * @param Element_Base $element Elementor element.
	 */
	private function register_entrance( Element_Base $element ): void {
		$type = array( self::TYPE_CONTROL => 'entrance' );

		$this->add(
			$element,
			'gsap_animation',
			array(
				'label'   => esc_html__( 'Animation', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade-up',
				'options' => $this->presets->options(),
			),
			$type
		);

		$this->add(
			$element,
			'gsap_distance',
			array(
				'label'       => esc_html__( 'Distance (px)', 'gsap-elementor-toolkit' ),
				'description' => esc_html__( 'How far the element travels in Fade Up / Down / Left / Right.', 'gsap-elementor-toolkit' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 60,
				'min'         => 0,
				'max'         => 1000,
				'step'        => 5,
			),
			$type + array( 'gsap_animation' => array( 'fade-up', 'fade-down', 'fade-left', 'fade-right' ) )
		);

		$this->add(
			$element,
			'gsap_stagger',
			array(
				'label'        => esc_html__( 'Animate Children One by One', 'gsap-elementor-toolkit' ),
				'description'  => esc_html__( 'For containers: each inner element animates after the previous one.', 'gsap-elementor-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			),
			$type
		);

		$stagger = $type + array( 'gsap_stagger' => 'yes' );

		$this->add(
			$element,
			'gsap_stagger_each',
			array(
				'label'   => esc_html__( 'Time Between Children (s)', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0.15,
				'min'     => 0,
				'max'     => 3,
				'step'    => 0.05,
			),
			$stagger
		);

		$this->add(
			$element,
			'gsap_stagger_from',
			array(
				'label'   => esc_html__( 'Start From', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'start',
				'options' => array(
					'start'  => esc_html__( 'First', 'gsap-elementor-toolkit' ),
					'end'    => esc_html__( 'Last', 'gsap-elementor-toolkit' ),
					'center' => esc_html__( 'Center', 'gsap-elementor-toolkit' ),
					'edges'  => esc_html__( 'Edges', 'gsap-elementor-toolkit' ),
					'random' => esc_html__( 'Random', 'gsap-elementor-toolkit' ),
				),
			),
			$stagger
		);
	}

	/**
	 * Text: split, scramble, typewriter, counter.
	 *
	 * @param Element_Base $element Elementor element.
	 */
	private function register_text( Element_Base $element ): void {
		$type  = array( self::TYPE_CONTROL => 'text' );
		$split = $type + array( 'gsap_text_effect' => array( 'split-words', 'split-chars', 'split-lines' ) );

		$this->add(
			$element,
			'gsap_text_effect',
			array(
				'label'   => esc_html__( 'Text Effect', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'split-words',
				'options' => array(
					'split-words' => esc_html__( 'Word by word', 'gsap-elementor-toolkit' ),
					'split-chars' => esc_html__( 'Letter by letter', 'gsap-elementor-toolkit' ),
					'split-lines' => esc_html__( 'Line by line', 'gsap-elementor-toolkit' ),
					'scramble'    => esc_html__( 'Scramble / decode', 'gsap-elementor-toolkit' ),
					'typewriter'  => esc_html__( 'Typewriter', 'gsap-elementor-toolkit' ),
					'counter'     => esc_html__( 'Number counter', 'gsap-elementor-toolkit' ),
				),
			),
			$type
		);

		$this->add(
			$element,
			'gsap_text_anim',
			array(
				'label'   => esc_html__( 'Look', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade-up',
				'options' => array(
					'fade-up' => esc_html__( 'Fade up', 'gsap-elementor-toolkit' ),
					'mask-up' => esc_html__( 'Rise from mask', 'gsap-elementor-toolkit' ),
					'fade'    => esc_html__( 'Fade', 'gsap-elementor-toolkit' ),
					'blur'    => esc_html__( 'Blur in', 'gsap-elementor-toolkit' ),
					'rotate'  => esc_html__( 'Flip in (3D)', 'gsap-elementor-toolkit' ),
					'scale'   => esc_html__( 'Pop in', 'gsap-elementor-toolkit' ),
				),
			),
			$split
		);

		$this->add(
			$element,
			'gsap_text_stagger',
			array(
				'label'       => esc_html__( 'Time Between Parts (s)', 'gsap-elementor-toolkit' ),
				'description' => esc_html__( 'Arabic text is animated by word, never by letter, to keep letters joined.', 'gsap-elementor-toolkit' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0.08,
				'min'         => 0,
				'max'         => 1,
				'step'        => 0.01,
			),
			$split
		);

		$this->add(
			$element,
			'gsap_text_scrub',
			array(
				'label'        => esc_html__( 'Follow the Scroll', 'gsap-elementor-toolkit' ),
				'description'  => esc_html__( 'The text reveals as the page scrolls, and hides again when scrolling back.', 'gsap-elementor-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			),
			$split
		);

		$this->add(
			$element,
			'gsap_text_chars',
			array(
				'label'   => esc_html__( 'Random Characters', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'upperCase',
				'options' => array(
					'upperCase'         => 'ABC',
					'lowerCase'         => 'abc',
					'upperAndLowerCase' => 'AbC',
					'0123456789'        => '123',
					'!<>-_\\/[]{}—=+*^?#' => '!<>#',
				),
			),
			$type + array( 'gsap_text_effect' => 'scramble' )
		);

		$this->add(
			$element,
			'gsap_text_speed',
			array(
				'label'   => esc_html__( 'Seconds per Letter', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0.05,
				'min'     => 0.01,
				'max'     => 0.5,
				'step'    => 0.01,
			),
			$type + array( 'gsap_text_effect' => 'typewriter' )
		);

		$this->add(
			$element,
			'gsap_text_cursor',
			array(
				'label'        => esc_html__( 'Show Cursor', 'gsap-elementor-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			),
			$type + array( 'gsap_text_effect' => 'typewriter' )
		);

		$this->add(
			$element,
			'gsap_counter_separator',
			array(
				'label'        => esc_html__( 'Thousands Separator', 'gsap-elementor-toolkit' ),
				'description'  => esc_html__( 'Counts up to the first number found in the text, e.g. "1500+".', 'gsap-elementor-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			),
			$type + array( 'gsap_text_effect' => 'counter' )
		);

		$this->add(
			$element,
			'gsap_text_selector',
			array(
				'label'       => esc_html__( 'Text Selector (optional)', 'gsap-elementor-toolkit' ),
				'description' => esc_html__( 'Leave empty to detect the text automatically. Example: h2, .my-title', 'gsap-elementor-toolkit' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => '.elementor-heading-title',
			),
			$type
		);
	}

	/**
	 * Scroll: scrubbed effects, pin, horizontal, progress bar.
	 *
	 * @param Element_Base $element Elementor element.
	 */
	private function register_scroll( Element_Base $element ): void {
		$type = array( self::TYPE_CONTROL => 'scroll' );

		$this->add(
			$element,
			'gsap_scroll_effect',
			array(
				'label'        => esc_html__( 'Scroll Effect', 'gsap-elementor-toolkit' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'parallax',
				'options'      => array(
					'parallax'   => esc_html__( 'Parallax', 'gsap-elementor-toolkit' ),
					'move-y'     => esc_html__( 'Move up / down', 'gsap-elementor-toolkit' ),
					'move-x'     => esc_html__( 'Move sideways', 'gsap-elementor-toolkit' ),
					'rotate'     => esc_html__( 'Rotate', 'gsap-elementor-toolkit' ),
					'scale'      => esc_html__( 'Scale', 'gsap-elementor-toolkit' ),
					'fade'       => esc_html__( 'Fade', 'gsap-elementor-toolkit' ),
					'blur'       => esc_html__( 'Blur', 'gsap-elementor-toolkit' ),
					'pin'        => esc_html__( 'Pin (stick while scrolling)', 'gsap-elementor-toolkit' ),
					'horizontal' => esc_html__( 'Horizontal scroll (container)', 'gsap-elementor-toolkit' ),
					'progress'   => esc_html__( 'Reading progress bar', 'gsap-elementor-toolkit' ),
				),
				// Adds "gsap-et-horizontal" etc. to the wrapper (used by frontend.css).
				'prefix_class' => 'gsap-et-',
			),
			$type
		);

		$this->add(
			$element,
			'gsap_scroll_speed',
			array(
				'label'       => esc_html__( 'Speed', 'gsap-elementor-toolkit' ),
				'description' => esc_html__( 'Negative values move the other way.', 'gsap-elementor-toolkit' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 3,
				'min'         => -10,
				'max'         => 10,
				'step'        => 0.5,
			),
			$type + array( 'gsap_scroll_effect' => 'parallax' )
		);

		$from_to = $type + array( 'gsap_scroll_effect' => array( 'move-y', 'move-x', 'rotate', 'scale', 'fade', 'blur' ) );

		$this->add(
			$element,
			'gsap_scroll_from',
			array(
				'label'       => esc_html__( 'From', 'gsap-elementor-toolkit' ),
				'description' => esc_html__( 'Empty = default. Units: Move px, Rotate deg, Scale 1 = normal, Fade 0–1, Blur px.', 'gsap-elementor-toolkit' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '',
			),
			$from_to
		);

		$this->add(
			$element,
			'gsap_scroll_to',
			array(
				'label'   => esc_html__( 'To', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '',
			),
			$from_to
		);

		$ranged = $type + array( 'gsap_scroll_effect' => array( 'parallax', 'move-y', 'move-x', 'rotate', 'scale', 'fade', 'blur' ) );

		$this->add(
			$element,
			'gsap_scroll_start',
			array(
				'label'   => esc_html__( 'Starts When', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top bottom',
				'options' => array(
					'top bottom' => esc_html__( 'Element enters the screen', 'gsap-elementor-toolkit' ),
					'top 80%'    => esc_html__( 'Element is a bit inside', 'gsap-elementor-toolkit' ),
					'top center' => esc_html__( 'Element top reaches the middle', 'gsap-elementor-toolkit' ),
					'top top'    => esc_html__( 'Element top reaches the top', 'gsap-elementor-toolkit' ),
				),
			),
			$ranged
		);

		$this->add(
			$element,
			'gsap_scroll_end',
			array(
				'label'   => esc_html__( 'Ends When', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom top',
				'options' => array(
					'bottom top'    => esc_html__( 'Element leaves the screen', 'gsap-elementor-toolkit' ),
					'center center' => esc_html__( 'Element center reaches the middle', 'gsap-elementor-toolkit' ),
					'bottom center' => esc_html__( 'Element bottom reaches the middle', 'gsap-elementor-toolkit' ),
					'bottom bottom' => esc_html__( 'Element bottom reaches the bottom', 'gsap-elementor-toolkit' ),
				),
			),
			$ranged
		);

		$this->add(
			$element,
			'gsap_scrub',
			array(
				'label'       => esc_html__( 'Smoothness (s)', 'gsap-elementor-toolkit' ),
				'description' => esc_html__( '0 = follows the scrollbar exactly. Higher = softer catch-up.', 'gsap-elementor-toolkit' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'min'         => 0,
				'max'         => 5,
				'step'        => 0.1,
			),
			$type + array( 'gsap_scroll_effect!' => array( 'pin', 'progress' ) )
		);

		$this->add(
			$element,
			'gsap_pin_duration',
			array(
				'label'       => esc_html__( 'Pin Length (% of screen height)', 'gsap-elementor-toolkit' ),
				'description' => esc_html__( 'Pin does not work if a parent has a transform or overflow set.', 'gsap-elementor-toolkit' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 100,
				'min'         => 10,
				'max'         => 1000,
				'step'        => 10,
			),
			$type + array( 'gsap_scroll_effect' => 'pin' )
		);

		$this->add(
			$element,
			'gsap_scroll_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Use on a container. Give each inner element a fixed width (e.g. 80vw); they will scroll sideways while the container is pinned.', 'gsap-elementor-toolkit' ),
				'content_classes' => 'elementor-descriptor',
			),
			$type + array( 'gsap_scroll_effect' => 'horizontal' )
		);
	}

	/**
	 * Mouse: hover, magnetic, tilt, mouse parallax.
	 *
	 * @param Element_Base $element Elementor element.
	 */
	private function register_mouse( Element_Base $element ): void {
		$type = array( self::TYPE_CONTROL => 'mouse' );

		$this->add(
			$element,
			'gsap_mouse_effect',
			array(
				'label'       => esc_html__( 'Mouse Effect', 'gsap-elementor-toolkit' ),
				'description' => esc_html__( 'Mouse effects are turned off automatically on touch screens.', 'gsap-elementor-toolkit' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'hover-lift',
				'options'     => array(
					'hover-lift'     => esc_html__( 'Hover: lift', 'gsap-elementor-toolkit' ),
					'hover-grow'     => esc_html__( 'Hover: grow', 'gsap-elementor-toolkit' ),
					'hover-shrink'   => esc_html__( 'Hover: shrink', 'gsap-elementor-toolkit' ),
					'hover-rotate'   => esc_html__( 'Hover: rotate', 'gsap-elementor-toolkit' ),
					'magnetic'       => esc_html__( 'Magnetic', 'gsap-elementor-toolkit' ),
					'tilt'           => esc_html__( '3D tilt', 'gsap-elementor-toolkit' ),
					'mouse-parallax' => esc_html__( 'Follow the mouse (parallax)', 'gsap-elementor-toolkit' ),
				),
			),
			$type
		);

		$this->add(
			$element,
			'gsap_mouse_strength',
			array(
				'label'   => esc_html__( 'Strength', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5,
				'min'     => 1,
				'max'     => 10,
				'step'    => 1,
			),
			$type
		);

		$this->add(
			$element,
			'gsap_mouse_invert',
			array(
				'label'        => esc_html__( 'Move Opposite to the Mouse', 'gsap-elementor-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			),
			$type + array( 'gsap_mouse_effect' => 'mouse-parallax' )
		);
	}

	/**
	 * Loop: float, pulse, spin, shake, swing, bounce, heartbeat.
	 *
	 * @param Element_Base $element Elementor element.
	 */
	private function register_loop( Element_Base $element ): void {
		$type = array( self::TYPE_CONTROL => 'loop' );

		$this->add(
			$element,
			'gsap_loop_effect',
			array(
				'label'   => esc_html__( 'Loop Effect', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'float',
				'options' => array(
					'float'     => esc_html__( 'Float', 'gsap-elementor-toolkit' ),
					'pulse'     => esc_html__( 'Pulse', 'gsap-elementor-toolkit' ),
					'spin'      => esc_html__( 'Spin', 'gsap-elementor-toolkit' ),
					'shake'     => esc_html__( 'Shake', 'gsap-elementor-toolkit' ),
					'swing'     => esc_html__( 'Swing', 'gsap-elementor-toolkit' ),
					'bounce'    => esc_html__( 'Bounce', 'gsap-elementor-toolkit' ),
					'heartbeat' => esc_html__( 'Heartbeat', 'gsap-elementor-toolkit' ),
				),
			),
			$type
		);

		$this->add(
			$element,
			'gsap_loop_speed',
			array(
				'label'   => esc_html__( 'Cycle Duration (s)', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 0.2,
				'max'     => 20,
				'step'    => 0.1,
			),
			$type
		);

		$this->add(
			$element,
			'gsap_loop_intensity',
			array(
				'label'   => esc_html__( 'Intensity', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5,
				'min'     => 1,
				'max'     => 10,
				'step'    => 1,
			),
			$type
		);

		$this->add(
			$element,
			'gsap_loop_pause_hover',
			array(
				'label'        => esc_html__( 'Pause on Hover', 'gsap-elementor-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			),
			$type
		);
	}

	/**
	 * Timing and trigger shared by entrance, text and mouse (hover) types.
	 *
	 * @param Element_Base $element Elementor element.
	 */
	private function register_timing( Element_Base $element ): void {
		$timed   = array( self::TYPE_CONTROL => array( 'entrance', 'text', 'mouse' ) );
		$started = array( self::TYPE_CONTROL => array( 'entrance', 'text' ) );

		$this->add(
			$element,
			'gsap_trigger',
			array(
				'label'     => esc_html__( 'Start When', 'gsap-elementor-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'scroll',
				'options'   => array(
					'scroll' => esc_html__( 'Element scrolls into view', 'gsap-elementor-toolkit' ),
					'load'   => esc_html__( 'Page loads', 'gsap-elementor-toolkit' ),
				),
				'separator' => 'before',
			),
			$started
		);

		$this->add(
			$element,
			'gsap_start',
			array(
				'label'   => esc_html__( 'Start Position', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top 85%',
				'options' => array(
					'top bottom' => esc_html__( 'As soon as it appears', 'gsap-elementor-toolkit' ),
					'top 85%'    => esc_html__( 'Slightly inside the screen', 'gsap-elementor-toolkit' ),
					'top 70%'    => esc_html__( 'A third into the screen', 'gsap-elementor-toolkit' ),
					'top center' => esc_html__( 'Middle of the screen', 'gsap-elementor-toolkit' ),
				),
			),
			$started + array( 'gsap_trigger' => 'scroll' )
		);

		$this->add(
			$element,
			'gsap_once',
			array(
				'label'        => esc_html__( 'Play Only Once', 'gsap-elementor-toolkit' ),
				'description'  => esc_html__( 'Off = animate again every time the element re-enters the screen.', 'gsap-elementor-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			),
			$started + array( 'gsap_trigger' => 'scroll' )
		);

		$this->add(
			$element,
			'gsap_duration',
			array(
				'label'   => esc_html__( 'Duration (s)', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0.8,
				'min'     => 0.1,
				'max'     => 10,
				'step'    => 0.1,
			),
			$timed
		);

		$this->add(
			$element,
			'gsap_delay',
			array(
				'label'   => esc_html__( 'Delay (s)', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => 0,
				'max'     => 10,
				'step'    => 0.1,
			),
			$started
		);

		$this->add(
			$element,
			'gsap_ease',
			array(
				'label'   => esc_html__( 'Easing', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'power2.out',
				'options' => $this->ease_options(),
			),
			$timed
		);
	}

	/**
	 * Add a control that is only visible when the animation is enabled
	 * (plus any extra condition) and readable by the editor preview.
	 *
	 * @param Element_Base         $element   Element.
	 * @param string               $id        Control ID.
	 * @param array<string, mixed> $args      Control args.
	 * @param array<string, mixed> $condition Extra condition.
	 */
	private function add( Element_Base $element, string $id, array $args, array $condition ): void {
		if ( self::ENABLE_CONTROL !== $id ) {
			$args['condition'] = array( self::ENABLE_CONTROL => 'yes' ) + $condition;
		}

		if ( Controls_Manager::RAW_HTML !== ( $args['type'] ?? '' ) ) {
			$args['frontend_available'] = true;
		}

		$element->add_control( $id, $args );
	}

	/**
	 * Easing options.
	 *
	 * @return array<string, string>
	 */
	private function ease_options(): array {
		return array(
			'none'                => 'Linear',
			'power1.out'          => 'Soft (power1.out)',
			'power2.out'          => 'Smooth (power2.out)',
			'power3.out'          => 'Strong (power3.out)',
			'power4.out'          => 'Very strong (power4.out)',
			'power2.inOut'        => 'Smooth in-out (power2.inOut)',
			'sine.inOut'          => 'Gentle in-out (sine.inOut)',
			'expo.out'            => 'Expo (expo.out)',
			'circ.out'            => 'Circ (circ.out)',
			'back.out(1.7)'       => 'Back (back.out)',
			'elastic.out(1, 0.5)' => 'Elastic (elastic.out)',
			'bounce.out'          => 'Bounce (bounce.out)',
		);
	}
}
