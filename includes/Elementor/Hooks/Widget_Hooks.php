<?php
/**
 * Elementor widget hooks.
 *
 * @package GSAP_Elementor_Toolkit\Elementor\Hooks
 */

namespace GSAP_Elementor_Toolkit\Elementor\Hooks;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use GSAP_Elementor_Toolkit\Elementor\Animation_Controls;
use GSAP_Elementor_Toolkit\Elementor\Support\Widget_Filter;

/**
 * Injects the GSAP section into the Advanced tab of supported elements.
 */
class Widget_Hooks {

	/**
	 * Widget filter.
	 *
	 * @var Widget_Filter
	 */
	private Widget_Filter $widget_filter;

	/**
	 * Controls.
	 *
	 * @var Animation_Controls
	 */
	private Animation_Controls $controls;

	/**
	 * Constructor.
	 *
	 * @param Widget_Filter      $widget_filter Widget filter.
	 * @param Animation_Controls $controls Controls.
	 */
	public function __construct(
		Widget_Filter $widget_filter,
		Animation_Controls $controls
	) {
		$this->widget_filter = $widget_filter;
		$this->controls      = $controls;
	}

	/**
	 * Register hooks.
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
	 * Add our section right after the FIRST section of the Advanced tab.
	 *
	 * The old check `'section_advanced' === $section_id` only matched
	 * Sections/Columns — widgets use "_section_style" (and other IDs in newer
	 * Elementor versions), so the controls never appeared on widgets.
	 * Checking the tab instead works for widgets, containers, sections and columns.
	 *
	 * The hook also fires for documents (Kit / Site Settings, Page Settings),
	 * which are NOT Element_Base, so the arguments must stay untyped and
	 * every non-element is ignored before anything else runs.
	 *
	 * @param mixed $element Controls stack that owns the section (element or document).
	 * @param mixed $section_id Section that just closed.
	 * @param mixed $args Section args.
	 */
	public function register_controls( $element, $section_id = '', $args = array() ): void {

		if ( ! $element instanceof Element_Base ) {
			return;
		}

		if ( ! is_array( $args ) ) {
			return;
		}

		if ( Animation_Controls::SECTION_ID === $section_id ) {
			return;
		}

		if ( Controls_Manager::TAB_ADVANCED !== ( $args['tab'] ?? '' ) ) {
			return;
		}

		if ( ! $this->widget_filter->supports( $element ) ) {
			return;
		}

		// Already added to this element's stack.
		if ( null !== $element->get_controls( Animation_Controls::ENABLE_CONTROL ) ) {
			return;
		}

		$this->controls->register_controls( $element );
	}
}
