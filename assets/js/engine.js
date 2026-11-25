/**
 * Engine: turns (element + config) into a GSAP tween.
 * One place handles ScrollTrigger, reduced motion, cleanup and the
 * "hidden until animated" class, so presets only describe the start state.
 */
(function () {
	'use strict';

	var globalScope = window;
	var PENDING_CLASS = 'gsap-et-yes';
	var DONE_CLASS = 'gsap-et-done';
	var STORE_KEY = '__gsapToolkitTween';
	var varsFilters = [];

	function reveal(element) {
		element.classList.add(DONE_CLASS);
	}

	function prefersReducedMotion() {
		return !!(globalScope.matchMedia && globalScope.matchMedia('(prefers-reduced-motion: reduce)').matches);
	}

	function currentDevice() {
		var ef = globalScope.elementorFrontend;
		if (ef && typeof ef.getCurrentDeviceMode === 'function') {
			var mode = ef.getCurrentDeviceMode();
			if (mode === 'mobile' || mode === 'mobile_extra') {
				return 'mobile';
			}
			if (mode === 'tablet' || mode === 'tablet_extra') {
				return 'tablet';
			}
			return 'desktop';
		}

		var width = globalScope.innerWidth;
		if (width <= 767) {
			return 'mobile';
		}
		if (width <= 1024) {
			return 'tablet';
		}
		return 'desktop';
	}

	var Engine = {
		PENDING_CLASS: PENDING_CLASS,

		/**
		 * Pro add-on hook: change the GSAP vars before the tween is created.
		 * fn(vars, element, config) must return vars.
		 */
		addVarsFilter: function addVarsFilter(fn) {
			if (typeof fn === 'function') {
				varsFilters.push(fn);
			}
		},

		play: function play(element, config) {
			if (!element || !config) {
				return null;
			}

			Engine.kill(element);

			var gsap = globalScope.gsap;
			var registry = globalScope.GSAPToolkit && globalScope.GSAPToolkit.Registry;
			var preset = registry ? registry.get(config.animation) : null;

			if (!gsap || !preset || prefersReducedMotion() || config.disableOn.indexOf(currentDevice()) !== -1) {
				reveal(element);
				return null;
			}

			var vars = preset.build(config, element) || {};
			vars.duration = config.duration;
			vars.delay = config.delay;
			vars.ease = config.ease;

			if (config.trigger === 'scroll' && globalScope.ScrollTrigger) {
				vars.scrollTrigger = {
					trigger: element,
					start: config.start,
					end: config.end,
					once: config.once,
					toggleActions: config.once ? 'play none none none' : 'play none none reverse',
					markers: config.markers
				};
			}

			varsFilters.forEach(function (fn) {
				vars = fn(vars, element, config) || vars;
			});

			var tween = gsap.from(element, vars);
			element[STORE_KEY] = tween;

			// gsap.from() has already applied the start state, so it is safe to un-hide now.
			reveal(element);

			return tween;
		},

		kill: function kill(element) {
			var tween = element && element[STORE_KEY];
			if (!tween) {
				return;
			}

			if (tween.scrollTrigger) {
				tween.scrollTrigger.kill();
			}

			tween.kill();
			delete element[STORE_KEY];

			if (globalScope.gsap) {
				globalScope.gsap.set(element, { clearProps: 'opacity,visibility,transform,filter' });
			}
		},

		// Backwards compatibility: run from data-gsap attributes.
		run: function run(element) {
			var Config = globalScope.GSAPToolkit && globalScope.GSAPToolkit.Config;
			if (!element || !Config) {
				return null;
			}

			return Engine.play(element, Config.fromAttributes(element));
		}
	};

	globalScope.GSAPToolkit = globalScope.GSAPToolkit || {};
	globalScope.GSAPToolkit.Engine = Engine;
})();
