/**
 * Engine: runs one animation "type" (effect) on an element.
 *
 * Every effect lives in its own file under assets/js/effects/ and registers
 * itself with Engine.registerEffect(type, fn). The engine handles what all
 * types share: reduced motion, "disable on device", the hidden-until-animated
 * class, and cleanup (every tween / ScrollTrigger / SplitText an effect makes
 * is created inside a gsap.context, so one revert() undoes all of it).
 */
(function () {
	'use strict';

	var globalScope = window;
	var PENDING_CLASS = 'gsap-et-yes';
	var DONE_CLASS = 'gsap-et-done';
	var STORE_KEY = '__gsapToolkitRun';
	var effects = {};
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

	/**
	 * Toolbox handed to every effect.
	 */
	var api = {
		/**
		 * ScrollTrigger vars for "play when it scrolls into view" effects
		 * (entrance, text). Returns null when the trigger is "page load".
		 */
		inViewTrigger: function inViewTrigger(element, config) {
			if (config.trigger !== 'scroll' || !globalScope.ScrollTrigger) {
				return null;
			}

			return {
				trigger: element,
				start: config.start,
				end: config.end,
				once: config.once,
				toggleActions: config.once ? 'play none none none' : 'play none none reverse',
				markers: config.markers
			};
		},

		// Lets the Pro add-on (or later phases) adjust tween vars.
		filterVars: function filterVars(vars, element, config) {
			varsFilters.forEach(function (fn) {
				vars = fn(vars, element, config) || vars;
			});
			return vars;
		},

		// True on devices with a real mouse (mouse effects are skipped on touch).
		hasMouse: function hasMouse() {
			return !!(globalScope.matchMedia && globalScope.matchMedia('(hover: hover) and (pointer: fine)').matches);
		},

		// Registers a GSAP plugin if its file was loaded.
		usePlugin: function usePlugin(name) {
			var plugin = globalScope[name];
			if (plugin && globalScope.gsap) {
				globalScope.gsap.registerPlugin(plugin);
				return plugin;
			}
			return null;
		}
	};

	var Engine = {
		PENDING_CLASS: PENDING_CLASS,
		api: api,

		/**
		 * @param {string}   type Animation type, e.g. "scroll".
		 * @param {Function} fn   fn(element, config, api) -> optional cleanup function.
		 */
		registerEffect: function registerEffect(type, fn) {
			if (type && typeof fn === 'function') {
				effects[type] = fn;
			}
		},

		hasEffect: function hasEffect(type) {
			return Object.prototype.hasOwnProperty.call(effects, type);
		},

		/**
		 * fn(vars, element, config) must return vars.
		 */
		addVarsFilter: function addVarsFilter(fn) {
			if (typeof fn === 'function') {
				varsFilters.push(fn);
			}
		},

		play: function play(element, config) {
			if (!element || !config) {
				return;
			}

			Engine.kill(element);

			var gsap = globalScope.gsap;
			var effect = effects[config.type || 'entrance'];
			var disabled = (config.disableOn || []).indexOf(currentDevice()) !== -1;

			if (!gsap || !effect || disabled || prefersReducedMotion()) {
				reveal(element);
				return;
			}

			var cleanups = [];
			var ctx = gsap.context(function () {
				var cleanup = effect(element, config, api);
				if (typeof cleanup === 'function') {
					cleanups.push(cleanup);
				}
			});

			element[STORE_KEY] = { ctx: ctx, cleanups: cleanups };

			// Every effect applies its start state synchronously, so un-hide now.
			reveal(element);
		},

		kill: function kill(element) {
			var run = element && element[STORE_KEY];
			if (!run) {
				return;
			}

			run.cleanups.forEach(function (fn) {
				try {
					fn();
				} catch (error) {
					// Ignore: cleanup must never break the page.
				}
			});

			run.ctx.revert();
			delete element[STORE_KEY];
		},

		// Backwards compatibility: run from data-gsap attributes.
		run: function run(element) {
			var Config = globalScope.GSAPToolkit && globalScope.GSAPToolkit.Config;
			if (!element || !Config) {
				return;
			}

			Engine.play(element, Config.fromAttributes(element));
		}
	};

	globalScope.GSAPToolkit = globalScope.GSAPToolkit || {};
	globalScope.GSAPToolkit.Engine = Engine;
})();
