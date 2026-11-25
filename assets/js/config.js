/**
 * Normalises animation settings coming from two sources:
 *  - Elementor control values  -> Config.fromElementor(settings)
 *  - HTML data-* attributes     -> Config.fromAttributes(element)
 * Both return the same plain config object used by the Engine.
 */
(function () {
	'use strict';

	var globalScope = window;

	var defaults = {
		animation: 'fade-up',
		trigger: 'scroll', // 'scroll' | 'load'
		duration: 0.8,
		delay: 0,
		ease: 'power2.out',
		distance: 60,
		start: 'top 85%',
		end: 'bottom top',
		once: true,
		markers: false,
		disableOn: [] // ['mobile', 'tablet']
	};

	function toNumber(value, fallback) {
		if (value === null || value === undefined || value === '') {
			return fallback;
		}

		// Elementor SLIDER controls return { size, unit }.
		if (typeof value === 'object' && value !== null && 'size' in value) {
			value = value.size;
		}

		var parsed = parseFloat(value);
		return isNaN(parsed) ? fallback : parsed;
	}

	function toBoolean(value, fallback) {
		if (value === null || value === undefined) {
			return fallback;
		}

		if (typeof value === 'boolean') {
			return value;
		}

		var normalized = String(value).trim().toLowerCase();

		if (normalized === '') {
			return false; // Elementor switchers send '' when off.
		}

		if (['true', '1', 'yes', 'on'].indexOf(normalized) !== -1) {
			return true;
		}

		if (['false', '0', 'no', 'off'].indexOf(normalized) !== -1) {
			return false;
		}

		return fallback;
	}

	function toString(value, fallback) {
		if (value === null || value === undefined) {
			return fallback;
		}

		var normalized = String(value).trim();
		return normalized || fallback;
	}

	function toList(value) {
		if (Array.isArray(value)) {
			return value;
		}

		if (typeof value === 'string' && value.trim() !== '') {
			return value.split(',').map(function (item) {
				return item.trim();
			});
		}

		return [];
	}

	var Config = {
		getDefaults: function getDefaults() {
			var copy = {};
			Object.keys(defaults).forEach(function (key) {
				copy[key] = Array.isArray(defaults[key]) ? defaults[key].slice() : defaults[key];
			});
			return copy;
		},

		/**
		 * @param {Object} settings Result of elementor handler getElementSettings().
		 */
		fromElementor: function fromElementor(settings) {
			settings = settings || {};

			return {
				animation: toString(settings.gsap_animation, defaults.animation),
				trigger: toString(settings.gsap_trigger, defaults.trigger),
				duration: toNumber(settings.gsap_duration, defaults.duration),
				delay: toNumber(settings.gsap_delay, defaults.delay),
				ease: toString(settings.gsap_ease, defaults.ease),
				distance: toNumber(settings.gsap_distance, defaults.distance),
				start: toString(settings.gsap_start, defaults.start),
				end: defaults.end,
				once: toBoolean(settings.gsap_once, defaults.once),
				markers: false,
				disableOn: toList(settings.gsap_disable_on)
			};
		},

		/**
		 * <div data-gsap="fade-up" data-duration="1" data-trigger="load">
		 * Kept compatible with the old attribute names (data-scroll="true").
		 */
		fromAttributes: function fromAttributes(element) {
			if (!element) {
				return Config.getDefaults();
			}

			var attr = function (name) {
				return element.getAttribute('data-' + name);
			};

			var trigger = attr('trigger');
			if (!trigger && attr('scroll') !== null) {
				trigger = toBoolean(attr('scroll'), true) ? 'scroll' : 'load';
			}

			return {
				animation: toString(attr('gsap'), defaults.animation),
				trigger: toString(trigger, defaults.trigger),
				duration: toNumber(attr('duration'), defaults.duration),
				delay: toNumber(attr('delay'), defaults.delay),
				ease: toString(attr('ease'), defaults.ease),
				distance: toNumber(attr('distance'), defaults.distance),
				start: toString(attr('start'), defaults.start),
				end: toString(attr('end'), defaults.end),
				once: toBoolean(attr('once'), defaults.once),
				markers: toBoolean(attr('markers'), defaults.markers),
				disableOn: toList(attr('disable-on'))
			};
		},

		// Backwards compatibility with the old API name.
		read: function read(element) {
			return Config.fromAttributes(element);
		}
	};

	globalScope.GSAPToolkit = globalScope.GSAPToolkit || {};
	globalScope.GSAPToolkit.Config = Config;
})();
