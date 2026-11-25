/**
 * Type "entrance": the element (or its children, one by one) animates in
 * from a preset start state when it scrolls into view or when the page loads.
 *
 * Settings: gsap_animation, gsap_duration, gsap_delay, gsap_ease, gsap_distance,
 * gsap_trigger, gsap_start, gsap_once,
 * gsap_stagger (yes), gsap_stagger_each (s), gsap_stagger_from.
 */
(function () {
	'use strict';

	var Toolkit = window.GSAPToolkit;
	if (!Toolkit || !Toolkit.Engine) {
		return;
	}

	var Config = Toolkit.Config;

	// Direct children Elementor elements of a container (boxed or full width).
	function childElements(element) {
		var children = element.querySelectorAll(':scope > .elementor-element, :scope > .e-con-inner > .elementor-element, :scope > .elementor-container > .elementor-column, :scope > .elementor-widget-wrap > .elementor-element');

		if (!children.length) {
			children = element.children;
		}

		return Array.prototype.slice.call(children);
	}

	Toolkit.Engine.registerEffect('entrance', function (element, config, api) {
		var preset = Toolkit.Registry.get(config.animation);
		if (!preset) {
			return;
		}

		var s = config.settings || {};
		var useStagger = Config.bool(s, 'gsap_stagger', false);
		var targets = useStagger ? childElements(element) : element;

		var vars = preset.build(config, element) || {};
		vars.duration = config.duration;
		vars.delay = config.delay;
		vars.ease = config.ease;

		if (useStagger) {
			vars.stagger = {
				each: Config.num(s, 'gsap_stagger_each', 0.15),
				from: Config.str(s, 'gsap_stagger_from', 'start')
			};
		}

		var scrollTrigger = api.inViewTrigger(element, config);
		if (scrollTrigger) {
			vars.scrollTrigger = scrollTrigger;
		}

		window.gsap.from(targets, api.filterVars(vars, element, config));
	});
})();
