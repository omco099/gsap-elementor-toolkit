<?php
/**
 * Elementor controls for GSAP animation settings.
 *
 * @package GSAP_Elementor_Toolkit\Elementor
 */

namespace GSAP_Elementor_Toolkit\Elementor;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

/**
 * Registers GSAP animation controls for supported Elementor elements.
 */
class Animation_Controls {
	/**
	 * Section identifier.
	 */
	private const SECTION_ID = 'gsap_animation';

	/**
	 * Control identifier.
	 */
	private const CONTROL_ID = 'gsap_enable';

	/**
	 * Register GSAP animation controls for the given element.
	 *
	 * @param Element_Base $element Elementor element instance.
	 * @param array<mixed> $args Element arguments.
	 */
	public function register_controls( Element_Base $element, array $args = array() ): void {
		$element->start_controls_section(
			self::SECTION_ID,
			array(
				'label' => __( 'GSAP Animation', 'gsap-elementor-toolkit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			self::CONTROL_ID,
			array(
				'label'        => __( 'Enable Animation', 'gsap-elementor-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$element->end_controls_section();
	}
}
