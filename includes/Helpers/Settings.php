<?php
/**
 * Shared settings helper.
 *
 * @package GSAP_Elementor_Toolkit\Helpers
 */

namespace GSAP_Elementor_Toolkit\Helpers;

/**
 * Settings helper placeholder.
 */
class Settings {
	/**
	 * Retrieve a plugin option.
	 *
	 * @param string $key Option key.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	public function get( string $key, mixed $default = null ): mixed {
		return get_option( 'gsap_elementor_toolkit_' . $key, $default );
	}
}
