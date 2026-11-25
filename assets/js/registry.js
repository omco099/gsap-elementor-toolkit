/**
 * Preset registry.
 * A preset is an object: { build: function (config, element) { return gsapFromVars; } }
 * Free presets live in presets.js; the Pro add-on registers more the same way.
 */
(function () {
	'use strict';

	var globalScope = window;
	var presets = {};

	var Registry = {
		register: function register(name, preset) {
			if (!name || !preset || typeof preset.build !== 'function') {
				return;
			}

			presets[name] = preset;
		},
		get: function get(name) {
			return Object.prototype.hasOwnProperty.call(presets, name) ? presets[name] : null;
		},
		has: function has(name) {
			return Object.prototype.hasOwnProperty.call(presets, name);
		},
		list: function list() {
			return Object.keys(presets);
		}
	};

	globalScope.GSAPToolkit = globalScope.GSAPToolkit || {};
	globalScope.GSAPToolkit.Registry = Registry;
})();
