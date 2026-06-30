<?php
/**
 * Elementor integration manager.
 */

namespace GSAP_Elementor_Toolkit\Elementor;

use Elementor\Widget_Base;

class Elementor_Manager {

	private Animation_Controls $animation_controls;

	public function __construct( ?Animation_Controls $animation_controls = null ) {
		$this->animation_controls = $animation_controls ?? new Animation_Controls();
	}

	public function register(): void {

		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		add_action( 'elementor/element/after_section_end', array( $this, 'register_gsap_controls' ), 10, 3 );
	}

	public function register_gsap_controls( object $element, string $section_id, array $args = array() ): void {
		if ( 'section_advanced' !== $section_id || ! $element instanceof Widget_Base ) {
			return;
		}

		$this->animation_controls->register_controls( $element );
	}
}
