<?php
/**
 * Uninstall script for GSAP Elementor Toolkit.
 *
 * Element animation settings live inside Elementor's own page data, so only
 * the plugin's global options are removed here.
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb;

$wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$wpdb->prepare(
		"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
		$wpdb->esc_like( 'gsap_elementor_toolkit_' ) . '%'
	)
);
