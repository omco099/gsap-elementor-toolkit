(function () {
	'use strict';

	var globalScope = window;
	var registry = globalScope.GSAPToolkit && globalScope.GSAPToolkit.Registry;

	if (!registry || typeof registry.register !== 'function' || !globalScope.gsap || typeof globalScope.gsap.from !== 'function') {
		return;
	}

	var FadeUpAnimation = {
		run: function run(element) {
			if (!element) {
				return;
			}

			globalScope.gsap.from(element, {
				y: 60,
				opacity: 0,
				duration: 0.8,
				ease: 'power2.out'
			});
		}
	};

	registry.register('fade-up', FadeUpAnimation);
})();
