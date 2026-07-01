<?php
/**
 * Elementor widget hooks.
 *
 * @package GSAP_Elementor_Toolkit\Elementor\Hooks
 */

namespace GSAP_Elementor_Toolkit\Elementor\Hooks;

use Elementor\Element_Base;
use Elementor\Widget_Base;
use GSAP_Elementor_Toolkit\Elementor\Animation_Controls;
use GSAP_Elementor_Toolkit\Elementor\Support\Widget_Filter;

class Widget_Hooks {

	private Widget_Filter $widget_filter;

	private Animation_Controls $controls;

	public function __construct(
		Widget_Filter $widget_filter,
		Animation_Controls $controls
	) {
		$this->widget_filter = $widget_filter;
		$this->controls      = $controls;
	}

	public function register(): void {

		add_action(
			'elementor/element/after_section_end',
			array( $this, 'register_controls' ),
			10,
			3
		);

	}

	public function register_controls(
		Element_Base $element,
		string $section_id,
		array $args
	): void {

		/**
		 * Only widgets.
		 */
		if ( ! $element instanceof Widget_Base ) {
			return;
		}

		/**
		 * Only Advanced section.
		 */
		if ( 'section_advanced' !== $section_id ) {
			return;
		}

		/**
		 * Supported widgets only.
		 */
		if ( ! $this->widget_filter->supports( $element ) ) {
			return;
		}

		$this->controls->register_controls( $element );

	}
}
