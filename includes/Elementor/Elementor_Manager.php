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
	 * Animation controls.
	 *
	 * @var Animation_Controls
	 */
	private Animation_Controls $animation_controls;

	/**
	 * Constructor.
	 *
	 * @param Animation_Controls|null $animation_controls Controls instance.
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

		add_action(
			'elementor/widget/section/advanced/before_section_end',
			array( $this, 'register_gsap_controls' ),
			10,
			2
		);
	}

	/**
	 * Register GSAP controls.
	 *
	 * @param object $element Elementor element.
	 * @param string $section_id Section ID.
	 */
	public function register_gsap_controls( object $element, string $section_id ): void {

		if ( ! $element instanceof Widget_Base ) {
			return;
		}

		if ( 'section_advanced' !== $section_id ) {
			return;
		}

		$this->animation_controls->register_controls( $element );
	}
}
