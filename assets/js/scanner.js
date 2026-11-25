/**
 * Stand-alone mode (no Elementor): animates any element with a data-gsap attribute.
 *   <div data-gsap="fade-up" data-duration="1.2" data-trigger="scroll"></div>
 * Loaded only when the `gsap_elementor_toolkit/load_everywhere` filter is true.
 */
(function () {
	'use strict';

	function scan() {
		var engine = window.GSAPToolkit && window.GSAPToolkit.Engine;
		if (!engine) {
			return;
		}

		Array.prototype.forEach.call(document.querySelectorAll('[data-gsap]'), function (element) {
			engine.run(element);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', scan, { once: true });
		return;
	}

	scan();
})();
