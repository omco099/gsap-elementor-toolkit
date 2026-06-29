(function () {
	'use strict';

	var globalScope = window;

	function scan() {
		var elements = document.querySelectorAll('[data-gsap]');
		var engine = globalScope.GSAPToolkit && globalScope.GSAPToolkit.Engine;

		if (!engine || typeof engine.run !== 'function') {
			return;
		}

		Array.prototype.forEach.call(elements, function (element) {
			engine.run(element);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', scan, { once: true });
		return;
	}

	scan();
})();
