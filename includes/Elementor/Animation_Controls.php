<?php

namespace GSAP_Elementor_Toolkit\Elementor;

use Elementor\Controls_Manager;

class Animation_Controls {

	public function register_controls( $element ): void {

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
