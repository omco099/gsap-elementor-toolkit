<?php
/**
 * Plugin Name: GSAP Elementor Toolkit
 * Plugin URI: https://example.com/gsap-elementor-toolkit
 * Description: Professional GSAP animation toolkit for WordPress and Elementor.
 * Version: 1.0.0
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * Author: Your Name
 * License: GPL-2.0-or-later
 * Text Domain: gsap-elementor-toolkit
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'GSAP_ELEMENTOR_TOOLKIT_VERSION' ) ) {
	define( 'GSAP_ELEMENTOR_TOOLKIT_VERSION', '1.0.0' );
}

if ( ! defined( 'GSAP_ELEMENTOR_TOOLKIT_FILE' ) ) {
	define( 'GSAP_ELEMENTOR_TOOLKIT_FILE', __FILE__ );
}

if ( ! defined( 'GSAP_ELEMENTOR_TOOLKIT_DIR' ) ) {
	define( 'GSAP_ELEMENTOR_TOOLKIT_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'GSAP_ELEMENTOR_TOOLKIT_URL' ) ) {
	define( 'GSAP_ELEMENTOR_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'GSAP_ELEMENTOR_TOOLKIT_BASENAME' ) ) {
	define( 'GSAP_ELEMENTOR_TOOLKIT_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'GSAP_ELEMENTOR_TOOLKIT_TEXT_DOMAIN' ) ) {
	define( 'GSAP_ELEMENTOR_TOOLKIT_TEXT_DOMAIN', 'gsap-elementor-toolkit' );
}

if ( file_exists( GSAP_ELEMENTOR_TOOLKIT_DIR . 'vendor/autoload.php' ) ) {
	require_once GSAP_ELEMENTOR_TOOLKIT_DIR . 'vendor/autoload.php';
} else {
	spl_autoload_register( 'gsap_elementor_toolkit_autoload' );
}

require_once GSAP_ELEMENTOR_TOOLKIT_DIR . 'includes/Core/Plugin.php';

use GSAP_Elementor_Toolkit\Core\Plugin;

if ( class_exists( Plugin::class ) ) {
	$gsap_elementor_toolkit_plugin = new Plugin();
	$gsap_elementor_toolkit_plugin->run();
}

/**
 * Load classes for the plugin when Composer autoload is unavailable.
 *
 * @param string $class Fully qualified class name.
 */
function gsap_elementor_toolkit_autoload( string $class ): void {
	$prefix = 'GSAP_Elementor_Toolkit\\';
	if ( strncmp( $class, $prefix, strlen( $prefix ) ) !== 0 ) {
		return;
	}

	$relative_class = substr( $class, strlen( $prefix ) );
	$file = GSAP_ELEMENTOR_TOOLKIT_DIR . 'includes/' . str_replace( '\\', '/', $relative_class ) . '.php';

	if ( file_exists( $file ) ) {
		require_once $file;
	}
}
