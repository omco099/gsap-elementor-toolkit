(function () {
	'use strict';

	var globalScope = window;
	var registry = globalScope.GSAPToolkit && globalScope.GSAPToolkit.Registry;

	if (!registry || typeof registry.register !== 'function' || !globalScope.gsap || typeof globalScope.gsap.from !== 'function') {
		return;
	}

	var FadeLeftAnimation = {
		run: function run(element) {
			if (!element) {
				return;
			}

			var config = globalScope.GSAPToolkit && globalScope.GSAPToolkit.Config && typeof globalScope.GSAPToolkit.Config.read === 'function'
				? globalScope.GSAPToolkit.Config.read(element)
				: {
					duration: 0.8,
					delay: 0,
					ease: 'power2.out',
					scroll: false,
					start: 'top bottom',
					end: 'bottom top',
					scrub: false
				};

			var options = {
				x: -60,
				opacity: 0,
				duration: config.duration,
				delay: config.delay,
				ease: config.ease
			};

			if (config.scroll) {
				options.scrollTrigger = {
					trigger: element,
					start: config.start,
					end: config.end,
					scrub: config.scrub
				};
			}

			globalScope.gsap.from(element, options);
		}
	};

	registry.register('fade-left', FadeLeftAnimation);
})();
