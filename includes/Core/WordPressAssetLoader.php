<?php
/**
 * WordPress asset loader.
 *
 * @package GSAP_Elementor_Toolkit\Core
 */

namespace GSAP_Elementor_Toolkit\Core;

/**
 * Registers assets with WordPress. Frontend files are only *registered* here;
 * they are enqueued on demand (Elementor pages) by Elementor_Manager so pages
 * without animations do not download GSAP.
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
	 * Register hook callbacks.
	 */
	public function register(): void {
		// Registering before `init` triggers a "_doing_it_wrong" notice.
		add_action( 'init', array( $this, 'registerAssets' ) );
		add_action( 'wp_head', array( $this, 'printHeadScript' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueFrontend' ) );
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
	 * Enqueue the stand-alone scanner on every page only when explicitly allowed.
	 * Use: add_filter( 'gsap_elementor_toolkit/load_everywhere', '__return_true' );
	 */
	public function enqueueFrontend(): void {
		if ( ! apply_filters( 'gsap_elementor_toolkit/load_everywhere', false ) ) {
			return;
		}

		wp_enqueue_style( Assets::FRONTEND_STYLE );
		wp_enqueue_script( Assets::SCANNER_ENTRY );
	}

	/**
	 * Tiny inline script in <head>: marks that JS is running so animated
	 * elements can be hidden before first paint (prevents a "flash" before
	 * GSAP starts). A safety timer reveals everything if GSAP never loads.
	 */
	public function printHeadScript(): void {
		if ( is_admin() ) {
			return;
		}

		$js = "(function(d){var h=d.documentElement;h.classList.add('gsap-et-js');setTimeout(function(){h.classList.add('gsap-et-timeout');},3000);})(document);";

		// Printed in <head> so animated elements are hidden before the first paint
		// (frontend.css may only load in the footer).
		echo '<style id="gsap-elementor-toolkit-head-css">html.gsap-et-js:not(.gsap-et-timeout) body:not(.elementor-editor-active) .gsap-et-yes:not(.gsap-et-done){visibility:hidden}@media (prefers-reduced-motion:reduce){html.gsap-et-js body .gsap-et-yes{visibility:visible}}</style>' . "\n";

		if ( function_exists( 'wp_print_inline_script_tag' ) ) {
			wp_print_inline_script_tag( $js, array( 'id' => 'gsap-elementor-toolkit-head' ) );
			return;
		}

		echo '<script id="gsap-elementor-toolkit-head">' . $js . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
