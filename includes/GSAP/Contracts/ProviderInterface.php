<?php
/**
 * Provider contract for GSAP integrations.
 *
 * @package GSAP_Elementor_Toolkit\GSAP\Contracts
 */

namespace GSAP_Elementor_Toolkit\GSAP\Contracts;

use GSAP_Elementor_Toolkit\Core\Assets;

/**
 * Describes a GSAP provider that can contribute asset definitions.
 */
interface ProviderInterface {
	/**
	 * Register asset definitions with the asset registry.
	 *
	 * @param Assets $assets Asset registry.
	 */
	public function register( Assets $assets ): void;
}
