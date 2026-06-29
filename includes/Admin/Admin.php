<?php
/**
 * Admin integration placeholder.
 *
 * @package GSAP_Elementor_Toolkit\Admin
 */

namespace GSAP_Elementor_Toolkit\Admin;

/**
 * Admin class for plugin admin hooks.
 */
class Admin {
	/**
	 * Register admin hooks.
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	/**
	 * Register admin menu entry.
	 */
	public function register_menu(): void {
		// Placeholder for future admin pages.
	}
}
