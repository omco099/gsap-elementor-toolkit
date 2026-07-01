<?php
/**
 * Elementor animation controls.
 *
 * @package GSAP_Elementor_Toolkit\Elementor
 */

namespace GSAP_Elementor_Toolkit\Elementor;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

/**
 * Registers GSAP controls for Elementor widgets.
 */
class Animation_Controls {

	/**
	 * Section ID.
	 */
	private const SECTION_ID = 'gsap_animation';

	/**
	 * Enable control ID.
	 */
	private const ENABLE_CONTROL = 'gsap_enable';

	/**
	 * Register GSAP controls.
	 *
	 * @param Element_Base $element Elementor element.
	 */
	public function register_controls( Element_Base $element ): void {

		$element->start_controls_section(
			self::SECTION_ID,
			array(
				'label' => esc_html__(
					'GSAP Animation',
					'gsap-elementor-toolkit'
				),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			self::ENABLE_CONTROL,
			array(
				'label'        => esc_html__(
					'Enable Animation',
					'gsap-elementor-toolkit'
				),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$element->end_controls_section();
	}
}
