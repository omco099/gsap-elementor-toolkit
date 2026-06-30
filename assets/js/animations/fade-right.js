(function () {
	'use strict';

	var globalScope = window;
	var registry = globalScope.GSAPToolkit && globalScope.GSAPToolkit.Registry;

	if (!registry || typeof registry.register !== 'function' || !globalScope.gsap || typeof globalScope.gsap.from !== 'function') {
		return;
	}

	var FadeRightAnimation = {
		run: function run(element) {
			if (!element) {
				return;
			}

			var config = globalScope.GSAPToolkit && globalScope.GSAPToolkit.Config && typeof globalScope.GSAPToolkit.Config.read === 'function'
				? globalScope.GSAPToolkit.Config.read(element)
				: (globalScope.GSAPToolkit && globalScope.GSAPToolkit.Config && typeof globalScope.GSAPToolkit.Config.getDefaults === 'function'
					? globalScope.GSAPToolkit.Config.getDefaults()
					: {
						duration: 0.8,
						delay: 0,
						ease: 'power2.out',
						repeat: 0,
						repeatDelay: 0,
						yoyo: false,
						stagger: 0,
						once: false,
						markers: false,
						toggleActions: 'play none none none',
						scroll: false,
						start: 'top bottom',
						end: 'bottom top',
						scrub: false
					});

			var options = {
				x: 60,
				opacity: 0,
				duration: config.duration,
				delay: config.delay,
				ease: config.ease,
				repeat: config.repeat,
				repeatDelay: config.repeatDelay,
				yoyo: config.yoyo,
				stagger: config.stagger
			};

			var scrollTriggerOptions = globalScope.GSAPToolkit && globalScope.GSAPToolkit.Config && typeof globalScope.GSAPToolkit.Config.buildScrollTriggerOptions === 'function'
				? globalScope.GSAPToolkit.Config.buildScrollTriggerOptions(element, config)
				: null;

			if (scrollTriggerOptions) {
				options.scrollTrigger = scrollTriggerOptions;
			}

			globalScope.gsap.from(element, options);
		}
	};

	registry.register('fade-right', FadeRightAnimation);
})();
