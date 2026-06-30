<?php
/**
 * Elementor integration manager.
 *
 * @package GSAP_Elementor_Toolkit\Elementor
 */

namespace GSAP_Elementor_Toolkit\Elementor;

use Elementor\Widget_Base;

/**
 * Elementor integration manager.
 */
class Elementor_Manager {
	/**
	 * Animation controls instance.
	 *
	 * @var Animation_Controls
	 */
	private Animation_Controls $animation_controls;

	/**
	 * Initialize the Elementor integration manager.
	 *
	 * @param Animation_Controls|null $animation_controls Optional controls instance.
	 */
	public function __construct( ?Animation_Controls $animation_controls = null ) {
		$this->animation_controls = $animation_controls ?? new Animation_Controls();
	}

	/**
	 * Register Elementor hooks.
	 */
	public function register(): void {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		add_action( 'elementor/element/after_section_end', array( $this, 'register_gsap_controls' ), 10, 3 );
	}

	/**
	 * Register GSAP controls for supported widget elements.
	 *
	 * @param object $element Elementor element instance.
	 * @param string $section_id Section identifier.
	 * @param array<mixed> $args Section arguments.
	 */
	public function register_gsap_controls( object $element, string $section_id, array $args = array() ): void {
		if ( 'section_advanced' !== $section_id || ! $element instanceof Widget_Base ) {
			return;
		}

		$this->animation_controls->register_controls( $element, $args );
	}
}
