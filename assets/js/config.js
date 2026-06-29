(function () {
	'use strict';

	var globalScope = window;

	var Config = {
		read: function read(element) {
			if (!element) {
				return {
					duration: 0.8,
					delay: 0,
					ease: 'power2.out'
				};
			}

			var duration = element.getAttribute('data-duration');
			var delay = element.getAttribute('data-delay');
			var ease = element.getAttribute('data-ease');

			return {
				duration: duration ? parseFloat(duration) : 0.8,
				delay: delay ? parseFloat(delay) : 0,
				ease: ease || 'power2.out'
			};
		}
	};

	globalScope.GSAPToolkit = globalScope.GSAPToolkit || {};
	globalScope.GSAPToolkit.Config = Config;
})();
