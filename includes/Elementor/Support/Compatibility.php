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
	 * 3.20 keeps the large base of sites still on Elementor 3.x
	 * (4.1 would lock them out). Classic V3 widgets still exist in Elementor 4.
	 */
	public const MINIMUM_ELEMENTOR_VERSION = '3.20.0';

	/**
	 * Check whether Elementor is loaded.
	 *
	 * @return bool
	 */
	public function is_elementor_loaded(): bool {
		return did_action( 'elementor/loaded' ) > 0;
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

	/**
	 * Show an admin notice explaining why the integration is inactive.
	 */
	public function register_notice(): void {
		add_action( 'admin_notices', array( $this, 'render_notice' ) );
	}

	/**
	 * Render the notice.
	 */
	public function render_notice(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$message = $this->is_elementor_loaded()
			/* translators: %s: minimum Elementor version. */
			? sprintf( esc_html__( 'GSAP Elementor Toolkit needs Elementor %s or newer.', 'gsap-elementor-toolkit' ), esc_html( self::MINIMUM_ELEMENTOR_VERSION ) )
			: esc_html__( 'GSAP Elementor Toolkit needs Elementor to be installed and active.', 'gsap-elementor-toolkit' );

		printf( '<div class="notice notice-warning"><p>%s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
