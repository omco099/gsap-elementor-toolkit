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
 * Determines whether an Elementor element supports
 * GSAP controls.
 */
class Widget_Filter {

	/**
	 * Check if the current element is supported.
	 *
	 * @param Element_Base $element Elementor element.
	 * @return bool
	 */
	public function supports( Element_Base $element ): bool {

		/*
		 * For the first implementation we only support widgets.
		 *
		 * Containers, Sections and Columns will be added later.
		 */

		return $element instanceof Widget_Base;
	}
}
