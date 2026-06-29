(function () {
	'use strict';

	var globalScope = window;

	var Config = {
		read: function read(element) {
			if (!element) {
				return {
					duration: 0.8,
					delay: 0,
					ease: 'power2.out',
					scroll: false,
					start: 'top bottom',
					end: 'bottom top',
					scrub: false
				};
			}

			var duration = element.getAttribute('data-duration');
			var delay = element.getAttribute('data-delay');
			var ease = element.getAttribute('data-ease');
			var scroll = element.getAttribute('data-scroll');
			var start = element.getAttribute('data-start');
			var end = element.getAttribute('data-end');
			var scrub = element.getAttribute('data-scrub');

			return {
				duration: duration ? parseFloat(duration) : 0.8,
				delay: delay ? parseFloat(delay) : 0,
				ease: ease || 'power2.out',
				scroll: Config.normalizeBoolean(scroll, false),
				start: start || 'top bottom',
				end: end || 'bottom top',
				scrub: Config.normalizeBoolean(scrub, false)
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
				var normalizedValue = value.toLowerCase();
				if (normalizedValue === 'true' || normalizedValue === '1' || normalizedValue === 'yes' || normalizedValue === 'on') {
					return true;
				}

				if (normalizedValue === 'false' || normalizedValue === '0' || normalizedValue === 'no' || normalizedValue === 'off') {
					return false;
				}
			}

			return defaultValue;
		}
	};

	globalScope.GSAPToolkit = globalScope.GSAPToolkit || {};
	globalScope.GSAPToolkit.Config = Config;
})();
