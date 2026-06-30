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

		$element->add_control(
			'gsap_animation',
			array(
				'label'   => esc_html__( 'Animation Type', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'fade-up'    => esc_html__( 'Fade Up', 'gsap-elementor-toolkit' ),
					'fade-down'  => esc_html__( 'Fade Down', 'gsap-elementor-toolkit' ),
					'fade-left'  => esc_html__( 'Fade Left', 'gsap-elementor-toolkit' ),
					'fade-right' => esc_html__( 'Fade Right', 'gsap-elementor-toolkit' ),
					'zoom-in'    => esc_html__( 'Zoom In', 'gsap-elementor-toolkit' ),
				),
				'default' => 'fade-up',
			)
		);

		$element->add_control(
			'gsap_duration',
			array(
				'label'   => esc_html__( 'Duration', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0.8,
				'min'     => 0,
			)
		);

		$element->add_control(
			'gsap_delay',
			array(
				'label'   => esc_html__( 'Delay', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => 0,
			)
		);

		$element->add_control(
			'gsap_ease',
			array(
				'label'   => esc_html__( 'Ease', 'gsap-elementor-toolkit' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'power2.out',
			)
		);

		$element->end_controls_section();
	}
}
