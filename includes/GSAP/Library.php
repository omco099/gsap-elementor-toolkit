<?php
/**
 * Resolves where the GSAP library files are loaded from.
 *
 * @package GSAP_Elementor_Toolkit\GSAP
 */

namespace GSAP_Elementor_Toolkit\GSAP;

/**
 * Prefers a local copy in assets/vendor/gsap/ and falls back to the CDN.
 *
 * Bundle the files locally before publishing: a pinned local copy is faster,
 * GDPR-friendly and does not change under your users when GSAP updates.
 */
class Library {

	/**
	 * Pinned GSAP version (never load a floating "gsap@3").
	 */
	public const VERSION = '3.13.0';

	/**
	 * Return the URL for a GSAP dist file, e.g. "gsap.min.js".
	 *
	 * @param string $file File name inside gsap/dist.
	 * @return string
	 */
	public static function url( string $file ): string {
		$local = 'assets/vendor/gsap/' . $file;

		if ( file_exists( GSAP_ELEMENTOR_TOOLKIT_DIR . $local ) ) {
			return GSAP_ELEMENTOR_TOOLKIT_URL . $local;
		}

		return 'https://cdn.jsdelivr.net/npm/gsap@' . self::VERSION . '/dist/' . $file;
	}
}
