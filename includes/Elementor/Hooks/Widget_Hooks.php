<?php
/**
 * Elementor widget hooks.
 *
 * @package GSAP_Elementor_Toolkit\Elementor\Hooks
 */

namespace GSAP_Elementor_Toolkit\Elementor\Hooks;

use Elementor\Element_Base;
use GSAP_Elementor_Toolkit\Elementor\Animation_Controls;
use GSAP_Elementor_Toolkit\Elementor\Support\Widget_Filter;

/**
 * Registers Elementor hooks responsible for injecting
 * GSAP controls into supported Elementor widgets.
 */
class Widget_Hooks {

	/**
	 * Widget filter.
	 *
	 * @var Widget_Filter
	 */
	private Widget_Filter $widget_filter;

	/**
	 * Animation controls.
	 *
	 * @var Animation_Controls
	 */
	private Animation_Controls $controls;

	/**
	 * Constructor.
	 *
	 * @param Widget_Filter      $widget_filter Widget filter.
	 * @param Animation_Controls $controls Animation controls.
	 */
	public function __construct(
		Widget_Filter $widget_filter,
		Animation_Controls $controls
	) {
		$this->widget_filter = $widget_filter;
		$this->controls      = $controls;
	}

	/**
	 * Register Elementor hooks.
	 */
	public function register(): void {

		add_action(
			'elementor/element/after_section_end',
			array( $this, 'register_controls' ),
			10,
			3
		);

	}

	/**
	 * Register controls for supported widgets.
	 *
	 * @param Element_Base $element Elementor element.
	 * @param string       $section_id Section identifier.
	 * @param array        $args Section arguments.
	 */
	public function register_controls(
		Element_Base $element,
		string $section_id,
		array $args
	): void {

		// Only inject after the Advanced section.
		if ( 'section_advanced' !== $section_id ) {
			return;
		}

		// Ignore unsupported Elementor elements.
		if ( ! $this->widget_filter->supports( $element ) ) {
			return;
		}

		$this->controls->register_controls( $element );

	}
}
