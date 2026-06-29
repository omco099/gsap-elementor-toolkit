<?php
/**
 * Main plugin bootstrap class.
 *
 * @package GSAP_Elementor_Toolkit\Core
 */

namespace GSAP_Elementor_Toolkit\Core;

use GSAP_Elementor_Toolkit\Admin\Admin;
use GSAP_Elementor_Toolkit\Elementor\Elementor_Manager;
use GSAP_Elementor_Toolkit\GSAP\GSAPRegistry;
use GSAP_Elementor_Toolkit\GSAP\Manager as GsapManager;
use GSAP_Elementor_Toolkit\GSAP\Providers\CoreProvider;
use GSAP_Elementor_Toolkit\GSAP\Providers\ObserverProvider;
use GSAP_Elementor_Toolkit\GSAP\Providers\ScrollTriggerProvider;
use GSAP_Elementor_Toolkit\Helpers\Settings;

/**
 * Core plugin bootstrapper.
 */
class Plugin {
	/**
	 * Asset registry instance.
	 *
	 * @var Assets
	 */
	private Assets $assets;

	/**
	 * WordPress asset loader instance.
	 *
	 * @var WordPressAssetLoader
	 */
	private WordPressAssetLoader $asset_loader;

	/**
	 * GSAP provider registry.
	 *
	 * @var GSAPRegistry
	 */
	private GSAPRegistry $gsap_registry;

	/**
	 * Plugin settings helper.
	 *
	 * @var Settings
	 */
	private Settings $settings;

	/**
	 * Admin integration.
	 *
	 * @var Admin
	 */
	private Admin $admin;

	/**
	 * Elementor integration.
	 *
	 * @var Elementor_Manager
	 */
	private Elementor_Manager $elementor;

	/**
	 * GSAP integration placeholder.
	 *
	 * @var GsapManager
	 */
	private GsapManager $gsap;

	/**
	 * Initialize the plugin services.
	 *
	 * @param Assets|null $assets Optional asset registry instance.
	 * @param WordPressAssetLoader|null $asset_loader Optional WordPress asset loader.
	 * @param Settings|null $settings Optional settings service.
	 * @param Admin|null $admin Optional admin service.
	 * @param Elementor_Manager|null $elementor Optional Elementor service.
	 * @param GsapManager|null $gsap Optional GSAP service.
	 */
	public function __construct(
		?Assets $assets = null,
		?WordPressAssetLoader $asset_loader = null,
		?Settings $settings = null,
		?Admin $admin = null,
		?Elementor_Manager $elementor = null,
		?GsapManager $gsap = null
	) {
		$this->assets       = $assets ?? $this->create_assets();
		$this->asset_loader = $asset_loader ?? $this->create_asset_loader( $this->assets );
		$this->gsap_registry = $this->create_gsap_registry();
		$this->settings     = $settings ?? $this->create_settings();
		$this->admin        = $admin ?? $this->create_admin();
		$this->elementor    = $elementor ?? $this->create_elementor();
		$this->gsap         = $gsap ?? $this->create_gsap();
	}

	/**
	 * Boot the plugin and register hooks.
	 */
	public function run(): void {
		$this->load_textdomain();
		$this->gsap_registry->register( $this->assets );
		$this->asset_loader->register();
		$this->admin->register();
		$this->elementor->register();
		$this->gsap->register();
	}

	/**
	 * Create the asset registry.
	 *
	 * @return Assets
	 */
	private function create_assets(): Assets {
		$assets = new Assets();
		$assets->registerDefaultAssets();

		return $assets;
	}

	/**
	 * Create the WordPress asset loader.
	 *
	 * @param Assets $assets Asset registry.
	 * @return WordPressAssetLoader
	 */
	private function create_asset_loader( Assets $assets ): WordPressAssetLoader {
		return new WordPressAssetLoader( $assets );
	}

	/**
	 * Create the GSAP registry and register built-in providers.
	 *
	 * @return GSAPRegistry
	 */
	private function create_gsap_registry(): GSAPRegistry {
		$registry = new GSAPRegistry();
		$registry->add( new CoreProvider() );
		$registry->add( new ScrollTriggerProvider() );
		$registry->add( new ObserverProvider() );

		return $registry;
	}

	/**
	 * Create the settings helper.
	 *
	 * @return Settings
	 */
	private function create_settings(): Settings {
		return new Settings();
	}

	/**
	 * Create the admin integration service.
	 *
	 * @return Admin
	 */
	private function create_admin(): Admin {
		return new Admin();
	}

	/**
	 * Create the Elementor integration service.
	 *
	 * @return Elementor_Manager
	 */
	private function create_elementor(): Elementor_Manager {
		return new Elementor_Manager();
	}

	/**
	 * Create the GSAP integration placeholder service.
	 *
	 * @return GsapManager
	 */
	private function create_gsap(): GsapManager {
		return new GsapManager();
	}

	/**
	 * Load the plugin text domain.
	 */
	private function load_textdomain(): void {
		load_plugin_textdomain(
			GSAP_ELEMENTOR_TOOLKIT_TEXT_DOMAIN,
			false,
			dirname( GSAP_ELEMENTOR_TOOLKIT_BASENAME ) . '/languages/'
		);
	}
}
