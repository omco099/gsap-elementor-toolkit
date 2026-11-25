/**
 * Free animation presets.
 * Each preset only returns the START state (gsap.from vars); the Engine adds
 * duration, delay, ease and ScrollTrigger.
 * IDs must match includes/Animations/Preset_Library.php.
 */
(function () {
	'use strict';

	var registry = window.GSAPToolkit && window.GSAPToolkit.Registry;
	if (!registry) {
		return;
	}

	var presets = {
		'fade': function () {
			return { autoAlpha: 0 };
		},
		'fade-up': function (c) {
			return { autoAlpha: 0, y: c.distance };
		},
		'fade-down': function (c) {
			return { autoAlpha: 0, y: -c.distance };
		},
		'fade-left': function (c) {
			return { autoAlpha: 0, x: -c.distance };
		},
		'fade-right': function (c) {
			return { autoAlpha: 0, x: c.distance };
		},
		'zoom-in': function () {
			return { autoAlpha: 0, scale: 0.8 };
		},
		'zoom-out': function () {
			return { autoAlpha: 0, scale: 1.2 };
		},
		'blur-in': function () {
			return { autoAlpha: 0, filter: 'blur(12px)' };
		},
		'rotate-in': function () {
			return { autoAlpha: 0, rotation: -12, scale: 0.9, transformOrigin: '50% 50%' };
		},
		'flip-up': function () {
			return { autoAlpha: 0, rotationX: -80, transformPerspective: 800, transformOrigin: '50% 100%' };
		}
	};

	Object.keys(presets).forEach(function (name) {
		registry.register(name, { build: presets[name] });
	});
})();
