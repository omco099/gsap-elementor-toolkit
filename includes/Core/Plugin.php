<?php
/**
 * Main plugin bootstrap class.
 *
 * @package GSAP_Elementor_Toolkit\Core
 */

namespace GSAP_Elementor_Toolkit\Core;

use GSAP_Elementor_Toolkit\Admin\Admin;
use GSAP_Elementor_Toolkit\Elementor\Animation_Controls;
use GSAP_Elementor_Toolkit\Elementor\Elementor_Manager;
use GSAP_Elementor_Toolkit\Elementor\Hooks\Widget_Hooks;
use GSAP_Elementor_Toolkit\Elementor\Support\Compatibility;
use GSAP_Elementor_Toolkit\Elementor\Support\Widget_Filter;
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
	 * WordPress asset loader.
	 *
	 * @var WordPressAssetLoader
	 */
	private WordPressAssetLoader $asset_loader;

	/**
	 * GSAP registry.
	 *
	 * @var GSAPRegistry
	 */
	private GSAPRegistry $gsap_registry;

	/**
	 * Settings helper.
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
	 * GSAP manager.
	 *
	 * @var GsapManager
	 */
	private GsapManager $gsap;

	/**
	 * Constructor.
	 */
	public function __construct(
		?Assets $assets = null,
		?WordPressAssetLoader $asset_loader = null,
		?Settings $settings = null,
		?Admin $admin = null,
		?Elementor_Manager $elementor = null,
		?GsapManager $gsap = null
	) {
		$this->assets        = $assets ?? $this->create_assets();
		$this->asset_loader  = $asset_loader ?? $this->create_asset_loader( $this->assets );
		$this->gsap_registry = $this->create_gsap_registry();
		$this->settings      = $settings ?? $this->create_settings();
		$this->admin         = $admin ?? $this->create_admin();
		$this->elementor     = $elementor ?? $this->create_elementor();
		$this->gsap          = $gsap ?? $this->create_gsap();
	}

	/**
	 * Boot plugin.
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
	 * Create assets.
	 */
	private function create_assets(): Assets {

		$assets = new Assets();

		$assets->registerDefaultAssets();

		return $assets;
	}

	/**
	 * Create asset loader.
	 */
	private function create_asset_loader(
		Assets $assets
	): WordPressAssetLoader {

		return new WordPressAssetLoader( $assets );
	}

	/**
	 * Create GSAP registry.
	 */
	private function create_gsap_registry(): GSAPRegistry {

		$registry = new GSAPRegistry();

		$registry->add( new CoreProvider() );
		$registry->add( new ScrollTriggerProvider() );
		$registry->add( new ObserverProvider() );

		return $registry;
	}

	/**
	 * Create settings.
	 */
	private function create_settings(): Settings {

		return new Settings();
	}

	/**
	 * Create admin integration.
	 */
	private function create_admin(): Admin {

		return new Admin();
	}

	/**
	 * Create Elementor integration.
	 */
	private function create_elementor(): Elementor_Manager {

		$compatibility = new Compatibility();

		$widget_filter = new Widget_Filter();

		$controls = new Animation_Controls();

		$widget_hooks = new Widget_Hooks(
			$widget_filter,
			$controls
		);

		return new Elementor_Manager(
			$compatibility,
			$widget_hooks,
			$widget_filter,
			$controls
		);
	}

	/**
	 * Create GSAP manager.
	 */
	private function create_gsap(): GsapManager {

		return new GsapManager();
	}

	/**
	 * Load translations.
	 */
	private function load_textdomain(): void {

		load_plugin_textdomain(
			GSAP_ELEMENTOR_TOOLKIT_TEXT_DOMAIN,
			false,
			dirname( GSAP_ELEMENTOR_TOOLKIT_BASENAME ) . '/languages/'
		);
	}
}
