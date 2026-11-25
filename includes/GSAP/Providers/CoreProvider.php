<?php
/**
 * Core GSAP provider.
 *
 * @package GSAP_Elementor_Toolkit\GSAP\Providers
 */

namespace GSAP_Elementor_Toolkit\GSAP\Providers;

use GSAP_Elementor_Toolkit\Core\Asset;
use GSAP_Elementor_Toolkit\Core\Assets;
use GSAP_Elementor_Toolkit\GSAP\Contracts\ProviderInterface;
use GSAP_Elementor_Toolkit\GSAP\Library;

/**
 * Registers the core GSAP runtime asset.
 */
class CoreProvider implements ProviderInterface {
	/**
	 * Register the core GSAP asset.
	 *
	 * @param Assets $assets Asset registry.
	 */
	public function register( Assets $assets ): void {
		$assets->add(
			new Asset(
				'gsap',
				Library::url( 'gsap.min.js' ),
				Asset::TYPE_SCRIPT,
				Asset::AREA_FRONTEND,
				array(),
				Library::VERSION,
				true,
				'default'
			)
		);
	}
}
