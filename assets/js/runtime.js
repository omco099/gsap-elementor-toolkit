(function () {
	'use strict';

	var globalScope = window;

	if (!globalScope.gsap || typeof globalScope.gsap.registerPlugin !== 'function') {
		return;
	}

	if (!globalScope.ScrollTrigger) {
		return;
	}

	if (globalScope.GSAP_ELEMENTOR_TOOLKIT_RUNTIME_INITIALIZED) {
		return;
	}

	globalScope.gsap.registerPlugin(globalScope.ScrollTrigger);
	globalScope.GSAP_ELEMENTOR_TOOLKIT_RUNTIME_INITIALIZED = true;
})();
