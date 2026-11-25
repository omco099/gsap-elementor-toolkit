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
	 * Handle of the Elementor entry script. Enqueuing it pulls in the whole chain.
	 */
	public const ELEMENTOR_ENTRY = 'gsap-elementor-toolkit-elementor';

	/**
	 * Handle of the stand-alone entry script (data-gsap attributes, no Elementor).
	 */
	public const SCANNER_ENTRY = 'gsap-elementor-toolkit-scanner';

	/**
	 * Frontend stylesheet handle.
	 */
	public const FRONTEND_STYLE = 'gsap-elementor-toolkit-frontend';

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
	 *
	 * Load order (each file depends on the previous one):
	 * gsap -> scroll-trigger -> runtime -> registry -> config -> engine -> presets
	 *   -> scanner   (plain HTML with data-gsap attributes)
	 *   -> elementor (Elementor widgets / containers)
	 */
	public function registerDefaultAssets(): void {
		$v = $this->version;

		$this->add( new Asset( self::FRONTEND_STYLE, 'assets/css/frontend.css', Asset::TYPE_STYLE, Asset::AREA_FRONTEND, array(), $v ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-admin', 'assets/css/admin.css', Asset::TYPE_STYLE, Asset::AREA_ADMIN, array(), $v ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-admin', 'assets/js/admin.js', Asset::TYPE_SCRIPT, Asset::AREA_ADMIN, array(), $v, true ) );

		$this->add( new Asset( 'gsap-elementor-toolkit-runtime', 'assets/js/runtime.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap', 'scroll-trigger' ), $v, true ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-registry', 'assets/js/registry.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-runtime' ), $v, true ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-config', 'assets/js/config.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-registry' ), $v, true ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-engine', 'assets/js/engine.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-config' ), $v, true ) );
		$this->add( new Asset( 'gsap-elementor-toolkit-presets', 'assets/js/presets.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-engine' ), $v, true ) );

		$this->add( new Asset( self::SCANNER_ENTRY, 'assets/js/scanner.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-presets' ), $v, true ) );
		$this->add( new Asset( self::ELEMENTOR_ENTRY, 'assets/js/elementor-handler.js', Asset::TYPE_SCRIPT, Asset::AREA_FRONTEND, array( 'gsap-elementor-toolkit-presets', 'jquery' ), $v, true ) );
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
