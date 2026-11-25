<?php
/**
 * Elementor integration manager.
 *
 * @package GSAP_Elementor_Toolkit\Elementor
 */

namespace GSAP_Elementor_Toolkit\Elementor;

use GSAP_Elementor_Toolkit\Core\Assets;
use GSAP_Elementor_Toolkit\Elementor\Hooks\Widget_Hooks;
use GSAP_Elementor_Toolkit\Elementor\Support\Compatibility;
use GSAP_Elementor_Toolkit\Elementor\Support\Widget_Filter;

/**
 * Boots the Elementor integration layer.
 */
class Elementor_Manager {

	/**
	 * Compatibility checker.
	 *
	 * @var Compatibility
	 */
	private Compatibility $compatibility;

	/**
	 * Widget hooks.
	 *
	 * @var Widget_Hooks
	 */
	private Widget_Hooks $widget_hooks;

	/**
	 * Constructor.
	 *
	 * @param Compatibility|null      $compatibility Compatibility service.
	 * @param Widget_Hooks|null       $widget_hooks Widget hooks service.
	 * @param Widget_Filter|null      $widget_filter Widget filter service.
	 * @param Animation_Controls|null $controls Controls service.
	 */
	public function __construct(
		?Compatibility $compatibility = null,
		?Widget_Hooks $widget_hooks = null,
		?Widget_Filter $widget_filter = null,
		?Animation_Controls $controls = null
	) {

		$this->compatibility = $compatibility ?? new Compatibility();

		$widget_filter = $widget_filter ?? new Widget_Filter();
		$controls      = $controls ?? new Animation_Controls();

		$this->widget_hooks = $widget_hooks ?? new Widget_Hooks(
			$widget_filter,
			$controls
		);
	}

	/**
	 * Boot Elementor integration.
	 */
	public function register(): void {

		if ( ! $this->compatibility->can_boot() ) {
			$this->compatibility->register_notice();
			return;
		}

		$this->widget_hooks->register();

		// Live site + editor preview iframe (both render the Elementor frontend).
		add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Load the animation chain on pages rendered by Elementor only.
	 */
	public function enqueue_scripts(): void {
		wp_enqueue_script( Assets::ELEMENTOR_ENTRY );
	}

	/**
	 * Load the frontend stylesheet.
	 */
	public function enqueue_styles(): void {
		wp_enqueue_style( Assets::FRONTEND_STYLE );
	}
}
