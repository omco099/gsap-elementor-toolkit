<?php
/**
 * GSAP Elementor Controls.
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
	 * Register GSAP controls.
	 *
	 * @param Element_Base $element Elementor widget.
	 */
	public function register_controls( Element_Base $element ): void {

		$element->start_controls_section(
			'gsap_animation_section',
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
				'label'     => esc_html__( 'Animation Type', 'gsap-elementor-toolkit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade-up',
				'options'   => array(
					'fade-up'    => esc_html__( 'Fade Up', 'gsap-elementor-toolkit' ),
					'fade-down'  => esc_html__( 'Fade Down', 'gsap-elementor-toolkit' ),
					'fade-left'  => esc_html__( 'Fade Left', 'gsap-elementor-toolkit' ),
					'fade-right' => esc_html__( 'Fade Right', 'gsap-elementor-toolkit' ),
					'zoom-in'    => esc_html__( 'Zoom In', 'gsap-elementor-toolkit' ),
				),
				'condition' => array(
					'gsap_enable' => 'yes',
				),
			)
		);

		$element->add_control(
			'gsap_duration',
			array(
				'label'      => esc_html__( 'Duration', 'gsap-elementor-toolkit' ),
				'type'       => Controls_Manager::NUMBER,
				'default'    => 0.8,
				'min'        => 0,
				'step'       => 0.1,
				'condition'  => array(
					'gsap_enable' => 'yes',
				),
			)
		);

		$element->add_control(
			'gsap_delay',
			array(
				'label'      => esc_html__( 'Delay', 'gsap-elementor-toolkit' ),
				'type'       => Controls_Manager::NUMBER,
				'default'    => 0,
				'min'        => 0,
				'step'       => 0.1,
				'condition'  => array(
					'gsap_enable' => 'yes',
				),
			)
		);

		$element->add_control(
			'gsap_ease',
			array(
				'label'      => esc_html__( 'Ease', 'gsap-elementor-toolkit' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => 'power2.out',
				'condition'  => array(
					'gsap_enable' => 'yes',
				),
			)
		);

		$element->end_controls_section();
	}
}
