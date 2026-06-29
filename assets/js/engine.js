(function () {
	'use strict';

	var globalScope = window;

	if (!globalScope.gsap || typeof globalScope.gsap.from !== 'function') {
		return;
	}

	var Engine = {
		run: function run(element) {
			if (!element) {
				return;
			}

			var animationType = element.getAttribute('data-gsap');
			if (!animationType) {
				return;
			}

			var registry = globalScope.GSAPToolkit && globalScope.GSAPToolkit.Registry;
			var animation = registry && typeof registry.get === 'function' ? registry.get(animationType) : null;

			if (!animation || typeof animation.run !== 'function') {
				return;
			}

			animation.run(element);
		}
	};

	globalScope.GSAPToolkit = globalScope.GSAPToolkit || {};
	globalScope.GSAPToolkit.Engine = Engine;
})();
