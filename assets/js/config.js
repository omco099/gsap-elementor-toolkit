(function () {
	'use strict';

	var globalScope = window;

	var defaults = {
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
	};

	var Config = {
		getDefaults: function getDefaults() {
			return {
				duration: defaults.duration,
				delay: defaults.delay,
				ease: defaults.ease,
				repeat: defaults.repeat,
				repeatDelay: defaults.repeatDelay,
				yoyo: defaults.yoyo,
				stagger: defaults.stagger,
				once: defaults.once,
				markers: defaults.markers,
				toggleActions: defaults.toggleActions,
				scroll: defaults.scroll,
				start: defaults.start,
				end: defaults.end,
				scrub: defaults.scrub
			};
		},
		read: function read(element) {
			if (!element) {
				return Config.getDefaults();
			}

			var duration = element.getAttribute('data-duration');
			var delay = element.getAttribute('data-delay');
			var ease = element.getAttribute('data-ease');
			var repeat = element.getAttribute('data-repeat');
			var repeatDelay = element.getAttribute('data-repeat-delay');
			var yoyo = element.getAttribute('data-yoyo');
			var stagger = element.getAttribute('data-stagger');
			var once = element.getAttribute('data-once');
			var markers = element.getAttribute('data-markers');
			var toggleActions = element.getAttribute('data-toggle-actions');
			var scroll = element.getAttribute('data-scroll');
			var start = element.getAttribute('data-start');
			var end = element.getAttribute('data-end');
			var scrub = element.getAttribute('data-scrub');

			return {
				duration: Config.normalizeNumber(duration, defaults.duration),
				delay: Config.normalizeNumber(delay, defaults.delay),
				ease: Config.normalizeString(ease, defaults.ease),
				repeat: Config.normalizeNumber(repeat, defaults.repeat),
				repeatDelay: Config.normalizeNumber(repeatDelay, defaults.repeatDelay),
				yoyo: Config.normalizeBoolean(yoyo, defaults.yoyo),
				stagger: Config.normalizeNumber(stagger, defaults.stagger),
				once: Config.normalizeBoolean(once, defaults.once),
				markers: Config.normalizeBoolean(markers, defaults.markers),
				toggleActions: Config.normalizeString(toggleActions, defaults.toggleActions),
				scroll: Config.normalizeBoolean(scroll, defaults.scroll),
				start: Config.normalizeString(start, defaults.start),
				end: Config.normalizeString(end, defaults.end),
				scrub: Config.normalizeBoolean(scrub, defaults.scrub)
			};
		},
		buildScrollTriggerOptions: function buildScrollTriggerOptions(element, config) {
			if (!element || !config || !config.scroll) {
				return null;
			}

			return {
				trigger: element,
				start: config.start,
				end: config.end,
				scrub: config.scrub,
				markers: config.markers,
				toggleActions: config.toggleActions,
				once: config.once
			};
		},
		normalizeBoolean: function normalizeBoolean(value, defaultValue) {
			if (value === null || value === undefined) {
				return defaultValue;
			}

			if (typeof value === 'boolean') {
				return value;
			}

			if (typeof value === 'string') {
				var normalizedValue = value.trim().toLowerCase();
				if (normalizedValue === 'true' || normalizedValue === '1' || normalizedValue === 'yes' || normalizedValue === 'on') {
					return true;
				}

				if (normalizedValue === 'false' || normalizedValue === '0' || normalizedValue === 'no' || normalizedValue === 'off') {
					return false;
				}
			}

			return defaultValue;
		},
		normalizeNumber: function normalizeNumber(value, defaultValue) {
			if (value === null || value === undefined || value === '') {
				return defaultValue;
			}

			if (typeof value === 'number') {
				return isNaN(value) ? defaultValue : value;
			}

			if (typeof value === 'string') {
				var normalizedValue = value.trim();
				if (normalizedValue === '') {
					return defaultValue;
				}

				var parsedValue = parseFloat(normalizedValue);
				if (!isNaN(parsedValue)) {
					return parsedValue;
				}
			}

			return defaultValue;
		},
		normalizeString: function normalizeString(value, defaultValue) {
			if (value === null || value === undefined) {
				return defaultValue;
			}

			if (typeof value === 'string') {
				var normalizedValue = value.trim();
				return normalizedValue || defaultValue;
			}

			return value === '' ? defaultValue : String(value);
		}
	};

	globalScope.GSAPToolkit = globalScope.GSAPToolkit || {};
	globalScope.GSAPToolkit.Config = Config;
})();
