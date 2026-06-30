<?php
/**
 * Elementor integration manager.
 */

namespace GSAP_Elementor_Toolkit\Elementor;

class Elementor_Manager {

	private Animation_Controls $animation_controls;

	public function __construct( ?Animation_Controls $animation_controls = null ) {
		$this->animation_controls = $animation_controls ?? new Animation_Controls();
	}

	public function register(): void {

		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		$hooks = array(
			'elementor/element/common/_section_style/after_section_end',
			'elementor/element/container/section_advanced/after_section_end',
			'elementor/element/section/section_advanced/after_section_end',
			'elementor/element/column/section_advanced/after_section_end',
		);

		foreach ( $hooks as $hook ) {
			add_action(
				$hook,
				array( $this, 'register_gsap_controls' ),
				10,
				2
			);
		}
	}

	public function register_gsap_controls( $element, $args ): void {

		if ( ! method_exists( $element, 'start_controls_section' ) ) {
			return;
		}

		$this->animation_controls->register_controls( $element );
	}
}
