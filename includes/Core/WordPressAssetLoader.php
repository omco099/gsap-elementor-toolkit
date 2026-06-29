<?php
/**
 * WordPress asset loader.
 *
 * @package GSAP_Elementor_Toolkit\Core
 */

namespace GSAP_Elementor_Toolkit\Core;

/**
 * Responsible for registering and enqueuing assets with WordPress.
 */
class WordPressAssetLoader {
	/**
	 * Asset registry.
	 *
	 * @var Assets
	 */
	private Assets $assets;

	/**
	 * Create a new loader instance.
	 *
	 * @param Assets $assets Asset registry.
	 */
	public function __construct( Assets $assets ) {
		$this->assets = $assets;
	}

	/**
	 * Register hook callbacks and WordPress asset registrations.
	 */
	public function register(): void {
		$this->registerAssets();
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueFrontend' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdmin' ) );
	}

	/**
	 * Register all discovered assets with WordPress.
	 */
	public function registerAssets(): void {
		foreach ( $this->assets->all() as $asset ) {
			$this->registerAsset( $asset );
		}
	}

	/**
	 * Enqueue frontend assets.
	 */
	public function enqueueFrontend(): void {
		$this->enqueueArea( Asset::AREA_FRONTEND );
	}

	/**
	 * Enqueue admin assets.
	 */
	public function enqueueAdmin(): void {
		$this->enqueueArea( Asset::AREA_ADMIN );
	}

	/**
	 * Register an individual asset with WordPress.
	 *
	 * @param Asset $asset Asset definition.
	 */
	private function registerAsset( Asset $asset ): void {
		if ( Asset::TYPE_STYLE === $asset->getType() ) {
			wp_register_style( $asset->getHandle(), $this->resolveSource( $asset->getSource() ), $asset->getDependencies(), $asset->getVersion() );
			return;
		}

		wp_register_script( $asset->getHandle(), $this->resolveSource( $asset->getSource() ), $asset->getDependencies(), $asset->getVersion(), $asset->isFooter() );
	}

	/**
	 * Enqueue assets for the requested area.
	 *
	 * @param string $area Asset area.
	 */
	private function enqueueArea( string $area ): void {
		foreach ( $this->assets->forArea( $area ) as $asset ) {
			if ( Asset::TYPE_STYLE === $asset->getType() ) {
				wp_enqueue_style( $asset->getHandle() );
				continue;
			}

			wp_enqueue_script( $asset->getHandle() );
		}
	}

	/**
	 * Resolve an asset source to a URL.
	 *
	 * @param string $source Asset source.
	 * @return string
	 */
	private function resolveSource( string $source ): string {
		if ( preg_match( '/^https?:\/\//', $source ) ) {
			return $source;
		}

		return GSAP_ELEMENTOR_TOOLKIT_URL . ltrim( $source, '/' );
	}
}
