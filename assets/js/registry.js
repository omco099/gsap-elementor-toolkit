(function () {
	'use strict';

	var globalScope = window;
	var registry = {};

	var Registry = {
		register: function register(name, animation) {
			if (!name || !animation || typeof animation.run !== 'function') {
				return;
			}

			registry[name] = animation;
		},
		get: function get(name) {
			return registry[name] || null;
		}
	};

	globalScope.GSAPToolkit = globalScope.GSAPToolkit || {};
	globalScope.GSAPToolkit.Registry = Registry;
})();
