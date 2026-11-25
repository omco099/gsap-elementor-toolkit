<?php
/**
 * Elementor widget filter.
 *
 * @package GSAP_Elementor_Toolkit\Elementor\Support
 */

namespace GSAP_Elementor_Toolkit\Elementor\Support;

use Elementor\Element_Base;
use Elementor\Widget_Base;

/**
 * Determines whether an Elementor element supports GSAP controls.
 */
class Widget_Filter {

	/**
	 * Non-widget element types that get the controls.
	 */
	private const ELEMENT_TYPES = array( 'container', 'section', 'column' );

	/**
	 * Check if the current element is supported.
	 *
	 * Filter `gsap_elementor_toolkit/supports_element` to exclude specific
	 * widgets, e.g. return false when $element->get_name() === 'html'.
	 *
	 * @param Element_Base $element Elementor element.
	 * @return bool
	 */
	public function supports( Element_Base $element ): bool {

		$supported = $element instanceof Widget_Base
			|| in_array( $element->get_name(), self::ELEMENT_TYPES, true );

		return (bool) apply_filters( 'gsap_elementor_toolkit/supports_element', $supported, $element );
	}
}
