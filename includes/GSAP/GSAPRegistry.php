<?php
/**
 * Registry for GSAP providers.
 *
 * @package GSAP_Elementor_Toolkit\GSAP
 */

namespace GSAP_Elementor_Toolkit\GSAP;

use GSAP_Elementor_Toolkit\Core\Assets;
use GSAP_Elementor_Toolkit\GSAP\Contracts\ProviderInterface;

/**
 * Holds and executes GSAP providers.
 */
class GSAPRegistry {
	/**
	 * Registered providers.
	 *
	 * @var array<int, ProviderInterface>
	 */
	private array $providers = array();

	/**
	 * Add a provider to the registry.
	 *
	 * @param ProviderInterface $provider GSAP provider.
	 */
	public function add( ProviderInterface $provider ): void {
		$this->providers[] = $provider;
	}

	/**
	 * Register all providers into the asset registry.
	 *
	 * @param Assets $assets Asset registry.
	 */
	public function register( Assets $assets ): void {
		foreach ( $this->providers as $provider ) {
			$provider->register( $assets );
		}
	}
}
