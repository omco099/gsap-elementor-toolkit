<?php
/**
 * Asset registry for the plugin.
 *
 * @package GSAP_Elementor_Toolkit\Core
 */

namespace GSAP_Elementor_Toolkit\Core;

/**
 * Stores asset definitions for later loading by a dedicated WordPress asset loader.
 */
class Assets {
	/**
	 * Plugin version string.
	 *
	 * @var string
	 */
	private string $version;

	/**
	 * Registered assets.
	 *
	 * @var array<int, Asset>
	 */
	private array $assets = array();

	/**
	 * Create a new asset registry instance.
	 *
	 * @param string $version Plugin version.
	 */
	public function __construct( string $version = GSAP_ELEMENTOR_TOOLKIT_VERSION ) {
		$this->version = $version;
	}

	/**
	 * Register default frontend and admin assets.
	 */
	public function registerDefaultAssets(): void {
		$this->add( new Asset( 'gsap-elementor-toolkit-frontend', 'assets/css/frontend.css', Asset::TYPE_STYLE, Asset::AREA_FRONTEND, array(), $this->version ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-frontend', 'assets/js/frontend.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array(), $this->version ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-admin', 'assets/css/admin.css', Asset::TYPE_STYLE, Asset::AREA_ADMIN, array(), $this->version ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-admin', 'assets/js/admin.js', Asset::TYPE_SCRIPT, Asset::AREA_ADMIN, array(), $this->version ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-runtime', 'assets/js/runtime.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap', 'scroll-trigger' ), $this->version, false ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-registry', 'assets/js/registry.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-runtime' ), $this->version, false ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-config', 'assets/js/config.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-registry' ), $this->version, false ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-engine', 'assets/js/engine.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-config' ), $this->version, false ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-fade-up', 'assets/js/animations/fade-up.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-engine' ), $this->version, false ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-fade-down', 'assets/js/animations/fade-down.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-engine' ), $this->version, false ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-fade-left', 'assets/js/animations/fade-left.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-engine' ), $this->version, false ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-fade-right', 'assets/js/animations/fade-right.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-engine' ), $this->version, false ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-zoom-in', 'assets/js/animations/zoom-in.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-engine' ), $this->version, false ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-scanner', 'assets/js/scanner.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-engine', 'gsap-elementor-toolkit-fade-up', 'gsap-elementor-toolkit-fade-down', 'gsap-elementor-toolkit-fade-left', 'gsap-elementor-toolkit-fade-right', 'gsap-elementor-toolkit-zoom-in' ), $this->version, false ) );
	}

	/**
	 * Add an asset definition to the registry.
	 *
	 * @param Asset $asset Asset definition.
	 */
	public function add( Asset $asset ): void {
		$this->assets[] = $asset;
	}

	/**
	 * Return all registered assets.
	 *
	 * @return array<int, Asset>
	 */
	public function all(): array {
		return $this->assets;
	}

	/**
	 * Return assets for the requested area.
	 *
	 * @param string $area Asset area.
	 * @return array<int, Asset>
	 */
	public function forArea( string $area ): array {
		$assets = array();

		foreach ( $this->assets as $asset ) {
			if ( $asset->getArea() === $area ) {
				$assets[] = $asset;
			}
		}

		return $assets;
	}
}
