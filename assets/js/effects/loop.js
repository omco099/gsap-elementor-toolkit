/**
 * Type "loop": runs forever, no trigger needed.
 *
 * gsap_loop_effect: float, pulse, spin, shake, swing, bounce, heartbeat
 * Settings: gsap_loop_speed (seconds per cycle), gsap_loop_intensity (1–10),
 *           gsap_loop_pause_hover (yes).
 */
(function () {
	'use strict';

	var Toolkit = window.GSAPToolkit;
	if (!Toolkit || !Toolkit.Engine) {
		return;
	}

	var Config = Toolkit.Config;

	function build(gsap, element, effect, speed, i) {
		switch (effect) {
			case 'pulse':
				return gsap.to(element, { scale: 1 + i * 0.015, duration: speed / 2, ease: 'sine.inOut', repeat: -1, yoyo: true });

			case 'spin':
				return gsap.to(element, { rotation: '+=360', duration: speed * 2, ease: 'none', repeat: -1 });

			case 'shake':
				return gsap.to(element, {
					keyframes: { x: [0, -i, i, -i, i, 0] },
					duration: 0.5,
					ease: 'none',
					repeat: -1,
					repeatDelay: speed
				});

			case 'swing':
				gsap.set(element, { transformOrigin: '50% 0%' });
				return gsap.fromTo(element, { rotation: -i * 1.5 }, { rotation: i * 1.5, duration: speed / 2, ease: 'sine.inOut', repeat: -1, yoyo: true });

			case 'bounce':
				return gsap.timeline({ repeat: -1, repeatDelay: speed / 4 })
					.to(element, { y: -i * 3, duration: speed / 3, ease: 'power2.out' })
					.to(element, { y: 0, duration: speed / 2, ease: 'bounce.out' });

			case 'heartbeat':
				return gsap.timeline({ repeat: -1, repeatDelay: speed / 2 })
					.to(element, { scale: 1 + i * 0.02, duration: 0.15, ease: 'power1.out' })
					.to(element, { scale: 1, duration: 0.15, ease: 'power1.in' })
					.to(element, { scale: 1 + i * 0.02, duration: 0.15, ease: 'power1.out' })
					.to(element, { scale: 1, duration: 0.3, ease: 'power1.in' });

			default: // float
				return gsap.to(element, { y: -i * 2, duration: speed / 2, ease: 'sine.inOut', repeat: -1, yoyo: true });
		}
	}

	Toolkit.Engine.registerEffect('loop', function (element, config) {
		var s = config.settings || {};
		var animation = build(
			window.gsap,
			element,
			Config.str(s, 'gsap_loop_effect', 'float'),
			Math.max(0.2, Config.num(s, 'gsap_loop_speed', 2)),
			Config.num(s, 'gsap_loop_intensity', 5)
		);

		if (!Config.bool(s, 'gsap_loop_pause_hover', false)) {
			return;
		}

		var pause = function () {
			animation.pause();
		};
		var resume = function () {
			animation.resume();
		};

		element.addEventListener('mouseenter', pause);
		element.addEventListener('mouseleave', resume);

		return function () {
			element.removeEventListener('mouseenter', pause);
			element.removeEventListener('mouseleave', resume);
		};
	});
})();
