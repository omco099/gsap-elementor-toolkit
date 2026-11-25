/**
 * Type "mouse": reacts to the mouse. Skipped automatically on touch devices.
 *
 * gsap_mouse_effect:
 *   hover-lift / hover-grow / hover-shrink / hover-rotate – animates on hover and back on leave
 *   magnetic        – the element is pulled toward the cursor while hovered
 *   tilt            – 3D tilt that follows the cursor over the element
 *   mouse-parallax  – moves with the cursor anywhere on the page (depth layers)
 *
 * Settings: gsap_mouse_strength (1–10), gsap_mouse_invert (mouse-parallax),
 *           gsap_duration, gsap_ease (hover effects).
 */
(function () {
	'use strict';

	var Toolkit = window.GSAPToolkit;
	if (!Toolkit || !Toolkit.Engine) {
		return;
	}

	var Config = Toolkit.Config;

	function hoverVars(effect, strength) {
		switch (effect) {
			case 'hover-grow':
				return { scale: 1 + strength * 0.02 };
			case 'hover-shrink':
				return { scale: 1 - strength * 0.02 };
			case 'hover-rotate':
				return { rotation: strength * 1.5 };
			default: // hover-lift
				return { y: -strength * 2 };
		}
	}

	function listen(target, events) {
		Object.keys(events).forEach(function (name) {
			target.addEventListener(name, events[name]);
		});

		return function () {
			Object.keys(events).forEach(function (name) {
				target.removeEventListener(name, events[name]);
			});
		};
	}

	Toolkit.Engine.registerEffect('mouse', function (element, config, api) {
		if (!api.hasMouse()) {
			return;
		}

		var gsap = window.gsap;
		var s = config.settings || {};
		var effect = Config.str(s, 'gsap_mouse_effect', 'hover-lift');
		var strength = Config.num(s, 'gsap_mouse_strength', 5);

		if (effect.indexOf('hover-') === 0) {
			var vars = hoverVars(effect, strength);
			vars.duration = config.duration;
			vars.ease = config.ease;
			vars.paused = true;

			var tween = gsap.to(element, vars);

			return listen(element, {
				mouseenter: function () {
					tween.play();
				},
				mouseleave: function () {
					tween.reverse();
				}
			});
		}

		if (effect === 'magnetic') {
			var moveX = gsap.quickTo(element, 'x', { duration: 0.4, ease: 'power3.out' });
			var moveY = gsap.quickTo(element, 'y', { duration: 0.4, ease: 'power3.out' });
			var pull = strength / 20; // 0.05 – 0.5 of the cursor offset

			return listen(element, {
				mousemove: function (event) {
					var box = element.getBoundingClientRect();
					moveX((event.clientX - (box.left + box.width / 2)) * pull);
					moveY((event.clientY - (box.top + box.height / 2)) * pull);
				},
				mouseleave: function () {
					moveX(0);
					moveY(0);
				}
			});
		}

		if (effect === 'tilt') {
			var maxAngle = strength * 2;
			gsap.set(element, { transformPerspective: 800 });
			var rotateX = gsap.quickTo(element, 'rotationX', { duration: 0.5, ease: 'power2.out' });
			var rotateY = gsap.quickTo(element, 'rotationY', { duration: 0.5, ease: 'power2.out' });

			return listen(element, {
				mousemove: function (event) {
					var box = element.getBoundingClientRect();
					var px = (event.clientX - box.left) / box.width - 0.5;
					var py = (event.clientY - box.top) / box.height - 0.5;
					rotateY(px * maxAngle * 2);
					rotateX(-py * maxAngle * 2);
				},
				mouseleave: function () {
					rotateX(0);
					rotateY(0);
				}
			});
		}

		if (effect === 'mouse-parallax') {
			var depth = strength * 4 * (Config.bool(s, 'gsap_mouse_invert', false) ? -1 : 1);
			var followX = gsap.quickTo(element, 'x', { duration: 0.8, ease: 'power3.out' });
			var followY = gsap.quickTo(element, 'y', { duration: 0.8, ease: 'power3.out' });

			return listen(window, {
				mousemove: function (event) {
					followX((event.clientX / window.innerWidth - 0.5) * depth);
					followY((event.clientY / window.innerHeight - 0.5) * depth);
				}
			});
		}
	});
})();
