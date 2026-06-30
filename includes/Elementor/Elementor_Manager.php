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

		add_action( 'elementor/widget/section/advanced/before_section_end', array( $this, 'register_gsap_controls' ), 10, 2 );
	}

	public function register_gsap_controls( Widget_Base $element, string $section_id ): void {
		if ( 'section_advanced' !== $section_id ) {
			return;
		}

		$this->animation_controls->register_controls( $element );
	}
}
