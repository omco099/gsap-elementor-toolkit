<?php
/**
 * Animation preset library (the list shown in the Elementor select).
 *
 * @package GSAP_Elementor_Toolkit\Animations
 */

namespace GSAP_Elementor_Toolkit\Animations;

/**
 * Holds the presets the user can pick. Each id here MUST have a matching
 * definition registered in assets/js/presets.js (or in the Pro add-on JS).
 */
class Preset_Library {

	/**
	 * Cached, filtered presets.
	 *
	 * @var array<string, array{label: string, group: string}>|null
	 */
	private ?array $presets = null;

	/**
	 * Free presets.
	 *
	 * @return array<string, array{label: string, group: string}>
	 */
	private function defaults(): array {
		return array(
			'fade'       => array( 'label' => __( 'Fade', 'gsap-elementor-toolkit' ), 'group' => 'fade' ),
			'fade-up'    => array( 'label' => __( 'Fade Up', 'gsap-elementor-toolkit' ), 'group' => 'fade' ),
			'fade-down'  => array( 'label' => __( 'Fade Down', 'gsap-elementor-toolkit' ), 'group' => 'fade' ),
			'fade-left'  => array( 'label' => __( 'Fade From Left', 'gsap-elementor-toolkit' ), 'group' => 'fade' ),
			'fade-right' => array( 'label' => __( 'Fade From Right', 'gsap-elementor-toolkit' ), 'group' => 'fade' ),
			'zoom-in'    => array( 'label' => __( 'Zoom In', 'gsap-elementor-toolkit' ), 'group' => 'zoom' ),
			'zoom-out'   => array( 'label' => __( 'Zoom Out', 'gsap-elementor-toolkit' ), 'group' => 'zoom' ),
			'blur-in'    => array( 'label' => __( 'Blur In', 'gsap-elementor-toolkit' ), 'group' => 'special' ),
			'rotate-in'  => array( 'label' => __( 'Rotate In', 'gsap-elementor-toolkit' ), 'group' => 'special' ),
			'flip-up'    => array( 'label' => __( 'Flip Up (3D)', 'gsap-elementor-toolkit' ), 'group' => 'special' ),
		);
	}

	/**
	 * All presets, after the `gsap_elementor_toolkit/presets` filter
	 * (the Pro add-on appends its presets through this filter).
	 *
	 * @return array<string, array{label: string, group: string}>
	 */
	public function all(): array {
		if ( null === $this->presets ) {
			$this->presets = (array) apply_filters( 'gsap_elementor_toolkit/presets', $this->defaults() );
		}

		return $this->presets;
	}

	/**
	 * Options array for an Elementor SELECT control.
	 *
	 * @return array<string, string>
	 */
	public function options(): array {
		$options = array();

		foreach ( $this->all() as $id => $preset ) {
			$options[ $id ] = $preset['label'] ?? $id;
		}

		return $options;
	}
}
