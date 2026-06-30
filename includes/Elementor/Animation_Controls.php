<?php
/**
 * Elementor controls.
 *
 * @package GSAP_Elementor_Toolkit\Elementor
 */

namespace GSAP_Elementor_Toolkit\Elementor;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

/**
 * Registers GSAP controls.
 */
class Animation_Controls {

	/**
	 * Register controls.
	 *
	 * @param Element_Base $element Elementor widget.
	 */
	public function register_controls( Element_Base $element ): void {

		$element->start_controls_section(
			'gsap_animation',
			array(
				'label' => esc_html__( 'GSAP Animation', 'gsap-elementor-toolkit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'gsap_enable',
			array(
				'label'        => esc_html__( 'Enable Animation', 'gsap-elementor-toolkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$element->end_controls_section();
	}
}
