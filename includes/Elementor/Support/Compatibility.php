<?php
/**
 * Elementor compatibility helper.
 *
 * @package GSAP_Elementor_Toolkit\Elementor\Support
 */

namespace GSAP_Elementor_Toolkit\Elementor\Support;

/**
 * Validates Elementor availability and compatibility.
 */
class Compatibility {

	/**
	 * Minimum supported Elementor version.
	 */
	private const MINIMUM_ELEMENTOR_VERSION = '4.1.0';

	/**
	 * Check whether Elementor is loaded.
	 *
	 * @return bool
	 */
	public function is_elementor_loaded(): bool {
		return did_action( 'elementor/loaded' );
	}

	/**
	 * Check whether the installed Elementor version is supported.
	 *
	 * @return bool
	 */
	public function is_supported_version(): bool {

		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return false;
		}

		return version_compare(
			ELEMENTOR_VERSION,
			self::MINIMUM_ELEMENTOR_VERSION,
			'>='
		);
	}

	/**
	 * Determine whether the integration can be initialized.
	 *
	 * @return bool
	 */
	public function can_boot(): bool {

		return $this->is_elementor_loaded()
			&& $this->is_supported_version();
	}
}
