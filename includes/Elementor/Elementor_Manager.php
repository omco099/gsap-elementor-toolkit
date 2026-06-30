<?php
/**
 * Elementor integration manager.
 *
 * @package GSAP_Elementor_Toolkit\Elementor
 */

namespace GSAP_Elementor_Toolkit\Elementor;

use Elementor\Widget_Base;

/**
 * Boots the Elementor integration layer.
 */
class Elementor_Manager {

	/**
	 * Controls registrar.
	 *
	 * @var Animation_Controls
	 */
	private Animation_Controls $controls;

	/**
	 * Constructor.
	 *
	 * @param Animation_Controls|null $controls Controls registrar.
	 */
	public function __construct( ?Animation_Controls $controls = null ) {
		$this->controls = $controls ?? new Animation_Controls();
	}

	/**
	 * Register Elementor integration.
	 */
	public function register(): void {

		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		add_action(
			'elementor/element/after_section_end',
			array( $this, 'register_widget_controls' ),
			10,
			3
		);
	}

	/**
	 * Register controls on supported widgets.
	 *
	 * @param mixed  $element Elementor element.
	 * @param string $section_id Section identifier.
	 * @param array  $args Section arguments.
	 */
	public function register_widget_controls(
		$element,
		string $section_id,
		array $args
	): void {

		if ( ! $element instanceof Widget_Base ) {
			return;
		}

		$this->controls->register_controls(
			$element,
			$section_id,
			$args
		);
	}
}
