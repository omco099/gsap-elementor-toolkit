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
 * Every control is `frontend_available`, so Elementor prints its value in the
 * element's data-settings and the JS handler reads it with getElementSettings()
 * — this works the same on the live site and inside the editor preview.
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

		$enabled = array( self::ENABLE_CONTROL => 'yes' );

		$element->add_control(
			self::ENABLE_CONTROL,
			array(
				'label'              => esc_html__( 'Enable Animation', 'gsap-elementor-toolkit' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'return_value'       => 'yes',
				// Adds class "gsap-et-yes" to the wrapper: used by frontend.css to hide before animating.
				'prefix_class'       => 'gsap-et-',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'gsap_animation',
			array(
				'label'              => esc_html__( 'Animation', 'gsap-elementor-toolkit' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'fade-up',
				'options'            => $this->presets->options(),
				'condition'          => $enabled,
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'gsap_trigger',
			array(
				'label'              => esc_html__( 'Start When', 'gsap-elementor-toolkit' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'scroll',
				'options'            => array(
					'scroll' => esc_html__( 'Element scrolls into view', 'gsap-elementor-toolkit' ),
					'load'   => esc_html__( 'Page loads', 'gsap-elementor-toolkit' ),
				),
				'condition'          => $enabled,
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'gsap_duration',
			array(
				'label'              => esc_html__( 'Duration (s)', 'gsap-elementor-toolkit' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0.8,
				'min'                => 0.1,
				'max'                => 10,
				'step'               => 0.1,
				'condition'          => $enabled,
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'gsap_delay',
			array(
				'label'              => esc_html__( 'Delay (s)', 'gsap-elementor-toolkit' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0,
				'min'                => 0,
				'max'                => 10,
				'step'               => 0.1,
				'condition'          => $enabled,
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'gsap_distance',
			array(
				'label'              => esc_html__( 'Distance (px)', 'gsap-elementor-toolkit' ),
				'description'        => esc_html__( 'How far the element travels in Fade Up / Down / Left / Right.', 'gsap-elementor-toolkit' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 60,
				'min'                => 0,
				'max'                => 1000,
				'step'               => 5,
				'condition'          => array(
					self::ENABLE_CONTROL => 'yes',
					'gsap_animation'     => array( 'fade-up', 'fade-down', 'fade-left', 'fade-right' ),
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'gsap_ease',
			array(
				'label'              => esc_html__( 'Easing', 'gsap-elementor-toolkit' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'power2.out',
				'options'            => $this->ease_options(),
				'condition'          => $enabled,
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'gsap_start',
			array(
				'label'              => esc_html__( 'Start Position', 'gsap-elementor-toolkit' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'top 85%',
				'options'            => array(
					'top bottom' => esc_html__( 'As soon as it appears', 'gsap-elementor-toolkit' ),
					'top 85%'    => esc_html__( 'Slightly inside the screen', 'gsap-elementor-toolkit' ),
					'top 70%'    => esc_html__( 'A third into the screen', 'gsap-elementor-toolkit' ),
					'top center' => esc_html__( 'Middle of the screen', 'gsap-elementor-toolkit' ),
				),
				'condition'          => array(
					self::ENABLE_CONTROL => 'yes',
					'gsap_trigger'       => 'scroll',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'gsap_once',
			array(
				'label'              => esc_html__( 'Play Only Once', 'gsap-elementor-toolkit' ),
				'description'        => esc_html__( 'Off = animate again every time the element re-enters the screen.', 'gsap-elementor-toolkit' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'return_value'       => 'yes',
				'condition'          => array(
					self::ENABLE_CONTROL => 'yes',
					'gsap_trigger'       => 'scroll',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'gsap_disable_on',
			array(
				'label'              => esc_html__( 'Disable On', 'gsap-elementor-toolkit' ),
				'type'               => Controls_Manager::SELECT2,
				'multiple'           => true,
				'label_block'        => true,
				'default'            => array(),
				'options'            => array(
					'mobile' => esc_html__( 'Mobile', 'gsap-elementor-toolkit' ),
					'tablet' => esc_html__( 'Tablet', 'gsap-elementor-toolkit' ),
				),
				'condition'          => $enabled,
				'frontend_available' => true,
			)
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
