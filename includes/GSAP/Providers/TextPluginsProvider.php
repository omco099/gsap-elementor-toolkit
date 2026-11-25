<?php
/**
 * Text plugins provider (SplitText, ScrambleText, TextPlugin).
 *
 * @package GSAP_Elementor_Toolkit\GSAP\Providers
 */

namespace GSAP_Elementor_Toolkit\GSAP\Providers;

use GSAP_Elementor_Toolkit\Core\Asset;
use GSAP_Elementor_Toolkit\Core\Assets;
use GSAP_Elementor_Toolkit\GSAP\Contracts\ProviderInterface;
use GSAP_Elementor_Toolkit\GSAP\Library;

/**
 * Registers the GSAP text plugins. They are only registered here; the
 * Elementor manager enqueues each one on pages whose elements need it.
 */
class TextPluginsProvider implements ProviderInterface {

	/**
	 * Handle => file in gsap/dist.
	 */
	public const PLUGINS = array(
		'gsap-split-text'    => 'SplitText.min.js',
		'gsap-scramble-text' => 'ScrambleTextPlugin.min.js',
		'gsap-text-plugin'   => 'TextPlugin.min.js',
	);

	/**
	 * Register the text plugin assets.
	 *
	 * @param Assets $assets Asset registry.
	 */
	public function register( Assets $assets ): void {
		foreach ( self::PLUGINS as $handle => $file ) {
			$assets->add(
				new Asset(
					$handle,
					Library::url( $file ),
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
}
