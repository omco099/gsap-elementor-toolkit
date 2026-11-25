<?php
/**
 * ScrollTrigger GSAP provider.
 *
 * @package GSAP_Elementor_Toolkit\GSAP\Providers
 */

namespace GSAP_Elementor_Toolkit\GSAP\Providers;

use GSAP_Elementor_Toolkit\Core\Asset;
use GSAP_Elementor_Toolkit\Core\Assets;
use GSAP_Elementor_Toolkit\GSAP\Contracts\ProviderInterface;
use GSAP_Elementor_Toolkit\GSAP\Library;

/**
 * Registers the ScrollTrigger plugin asset.
 */
class ScrollTriggerProvider implements ProviderInterface {
	/**
	 * Register the ScrollTrigger asset.
	 *
	 * @param Assets $assets Asset registry.
	 */
	public function register( Assets $assets ): void {
		$assets->add(
			new Asset(
				'scroll-trigger',
				Library::url( 'ScrollTrigger.min.js' ),
				Asset::TYPE_SCRIPT,
				Asset::AREA_FRONTEND,
				array( 'gsap' ),
				Library::VERSION,
				true,
				'default'
			)
		);
	}
}
