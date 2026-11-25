/**
 * Type "scroll": the animation is tied to the scroll position (scrub),
 * so it moves forward and backward with the scrollbar.
 *
 * gsap_scroll_effect:
 *   parallax   – moves slower/faster than the page          (gsap_scroll_speed)
 *   move-x     – slides horizontally                          (from/to in px)
 *   move-y     – slides vertically                            (from/to in px)
 *   rotate     – rotates                                      (from/to in deg)
 *   scale      – grows or shrinks                             (from/to, 1 = normal)
 *   fade       – changes opacity                              (from/to, 0–1)
 *   blur       – blurs / un-blurs                             (from/to in px)
 *   pin        – sticks to the top while you scroll           (gsap_pin_duration, % of screen)
 *   horizontal – a container's children scroll sideways while the container is pinned
 *   progress   – fills from 0 to 100% width over the whole page (reading progress bar)
 *
 * Shared: gsap_scroll_from, gsap_scroll_to (empty = effect default),
 *         gsap_scroll_start, gsap_scroll_end, gsap_scrub (smoothing in s, 0 = exact).
 */
(function () {
	'use strict';

	var Toolkit = window.GSAPToolkit;
	if (!Toolkit || !Toolkit.Engine) {
		return;
	}

	var Config = Toolkit.Config;

	// property, default from, default to, unit
	var simpleEffects = {
		'move-x': ['x', -100, 100, 'px'],
		'move-y': ['y', -100, 100, 'px'],
		'rotate': ['rotation', 0, 360, ''],
		'scale': ['scale', 0.8, 1.2, ''],
		'fade': ['opacity', 0, 1, ''],
		'blur': ['filter', 10, 0, 'px']
	};

	function value(property, number, unit) {
		if (property === 'filter') {
			return 'blur(' + number + 'px)';
		}
		return unit ? number + unit : number;
	}

	function isRtl() {
		return document.documentElement.getAttribute('dir') === 'rtl';
	}

	Toolkit.Engine.registerEffect('scroll', function (element, config) {
		var gsap = window.gsap;
		var ScrollTrigger = window.ScrollTrigger;
		if (!ScrollTrigger) {
			return;
		}

		var s = config.settings || {};
		var effect = Config.str(s, 'gsap_scroll_effect', 'parallax');
		var scrubValue = Config.num(s, 'gsap_scrub', 1);
		var scrub = scrubValue > 0 ? scrubValue : true;
		var start = Config.str(s, 'gsap_scroll_start', 'top bottom');
		var end = Config.str(s, 'gsap_scroll_end', 'bottom top');

		if (effect === 'pin') {
			ScrollTrigger.create({
				trigger: element,
				pin: true,
				start: 'top top',
				end: '+=' + Config.num(s, 'gsap_pin_duration', 100) + '%'
			});
			return;
		}

		if (effect === 'progress') {
			gsap.fromTo(element, { scaleX: 0 }, {
				scaleX: 1,
				ease: 'none',
				transformOrigin: isRtl() ? 'right center' : 'left center',
				scrollTrigger: {
					trigger: document.documentElement,
					start: 'top top',
					end: 'bottom bottom',
					scrub: true
				}
			});
			return;
		}

		if (effect === 'horizontal') {
			// Boxed containers wrap their children in .e-con-inner.
			var track = element.querySelector(':scope > .e-con-inner') || element;
			var distance = function () {
				return Math.max(0, track.scrollWidth - element.clientWidth);
			};
			var direction = isRtl() ? 1 : -1;

			gsap.to(track, {
				x: function () {
					return direction * distance();
				},
				ease: 'none',
				scrollTrigger: {
					trigger: element,
					pin: true,
					start: 'top top',
					end: function () {
						return '+=' + distance();
					},
					scrub: scrub,
					invalidateOnRefresh: true
				}
			});
			return;
		}

		var fromVars = {};
		var toVars = { ease: 'none' };

		if (effect === 'parallax') {
			var speed = Config.num(s, 'gsap_scroll_speed', 3);
			fromVars.y = -speed * 30;
			toVars.y = speed * 30;
		} else {
			var def = simpleEffects[effect] || simpleEffects['move-y'];
			var from = Config.num(s, 'gsap_scroll_from', def[1]);
			var to = Config.num(s, 'gsap_scroll_to', def[2]);
			fromVars[def[0]] = value(def[0], from, def[3]);
			toVars[def[0]] = value(def[0], to, def[3]);
		}

		toVars.scrollTrigger = {
			trigger: element,
			start: start,
			end: end,
			scrub: scrub
		};

		gsap.fromTo(element, fromVars, toVars);
	});
})();
