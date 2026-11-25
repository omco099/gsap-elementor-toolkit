/**
 * Type "text": animates the text inside a widget (heading, text editor...).
 *
 * gsap_text_effect:
 *   split-words / split-chars / split-lines – SplitText, each part animates in turn
 *   scramble   – random characters resolve into the text (ScrambleText)
 *   typewriter – text is typed letter by letter, optional cursor (TextPlugin)
 *   counter    – the first number in the text counts up from 0 ("1500+" -> 0..1500+)
 *
 * Settings: gsap_text_anim (split look), gsap_text_stagger, gsap_text_scrub,
 *           gsap_text_chars (scramble), gsap_text_speed + gsap_text_cursor (typewriter),
 *           gsap_counter_separator, gsap_text_selector (optional custom target),
 *           plus the shared gsap_trigger / gsap_start / gsap_once / gsap_duration / gsap_delay / gsap_ease.
 *
 * Arabic text is never split into characters (it would break the letter joining):
 * split-chars falls back to words automatically.
 */
(function () {
	'use strict';

	var Toolkit = window.GSAPToolkit;
	if (!Toolkit || !Toolkit.Engine) {
		return;
	}

	var Config = Toolkit.Config;
	var ARABIC = /[؀-ۿݐ-ݿࢠ-ࣿ]/;
	var DEFAULT_TARGETS = [
		'.elementor-heading-title',
		'.elementor-text-editor',
		'.elementor-widget-text-editor .elementor-widget-container',
		'.elementor-button-text',
		'.elementor-icon-box-title',
		'.elementor-image-box-title',
		'p'
	];

	function findTarget(element, selector) {
		if (selector) {
			try {
				return element.querySelector(selector) || element;
			} catch (error) {
				return element;
			}
		}

		for (var i = 0; i < DEFAULT_TARGETS.length; i++) {
			var found = element.querySelector(DEFAULT_TARGETS[i]);
			if (found) {
				return found;
			}
		}

		// Text editor widgets with the optimized DOM have no inner wrapper.
		return element;
	}

	function splitLook(anim) {
		switch (anim) {
			case 'fade':
				return { opacity: 0 };
			case 'blur':
				return { opacity: 0, filter: 'blur(8px)' };
			case 'rotate':
				return { opacity: 0, rotationX: -90, transformOrigin: '50% 100%' };
			case 'scale':
				return { opacity: 0, scale: 0 };
			case 'mask-up':
				return { yPercent: 110 };
			default: // fade-up
				return { opacity: 0, yPercent: 60 };
		}
	}

	function timing(config, element, api) {
		var vars = { duration: config.duration, delay: config.delay, ease: config.ease };
		var scrollTrigger = api.inViewTrigger(element, config);
		if (scrollTrigger) {
			vars.scrollTrigger = scrollTrigger;
		}
		return vars;
	}

	/* ----- split-words / split-chars / split-lines ----- */

	function split(element, target, config, api, effect) {
		var gsap = window.gsap;
		var s = config.settings || {};
		var SplitText = api.usePlugin('SplitText');
		var anim = Config.str(s, 'gsap_text_anim', 'fade-up');

		var unit = effect.replace('split-', ''); // words | chars | lines
		if (unit === 'chars' && ARABIC.test(target.textContent)) {
			unit = 'words';
		}

		var vars = splitLook(anim);
		var base = timing(config, element, api);
		Object.keys(base).forEach(function (key) {
			vars[key] = base[key];
		});
		vars.stagger = Config.num(s, 'gsap_text_stagger', unit === 'chars' ? 0.03 : 0.08);

		if (Config.bool(s, 'gsap_text_scrub', false) && window.ScrollTrigger) {
			delete vars.delay;
			vars.ease = 'none';
			vars.scrollTrigger = { trigger: element, start: config.start, end: 'bottom 35%', scrub: 1 };
		}

		// No SplitText file on the page: animate the whole text block instead.
		if (!SplitText) {
			delete vars.stagger;
			gsap.from(target, vars);
			return null;
		}

		var instance = SplitText.create(target, {
			type: unit === 'chars' ? 'words,chars' : unit,
			mask: anim === 'mask-up' ? unit : undefined,
			autoSplit: true,
			aria: 'auto',
			onSplit: function (self) {
				return gsap.from(self[unit], vars);
			}
		});

		return function () {
			instance.revert();
		};
	}

	/* ----- scramble ----- */

	function scramble(element, target, config, api) {
		var s = config.settings || {};
		var original = target.textContent;

		if (!api.usePlugin('ScrambleTextPlugin')) {
			return null;
		}

		var vars = timing(config, element, api);
		vars.duration = Math.max(config.duration, 1);
		vars.ease = 'none';
		vars.scrambleText = {
			text: original,
			chars: Config.str(s, 'gsap_text_chars', 'upperCase'),
			revealDelay: 0.2,
			speed: 0.4
		};

		window.gsap.to(target, vars);

		return function () {
			target.textContent = original;
		};
	}

	/* ----- typewriter ----- */

	function typewriter(element, target, config, api) {
		var s = config.settings || {};
		var originalHtml = target.innerHTML;
		var text = target.textContent;

		if (!api.usePlugin('TextPlugin')) {
			return null;
		}

		// Keep the block's height so the layout does not jump while typing.
		var minHeight = target.style.minHeight;
		target.style.minHeight = target.offsetHeight + 'px';

		var typed = document.createElement('span');
		typed.className = 'gsap-et-typed';
		target.innerHTML = '';
		target.appendChild(typed);

		if (Config.bool(s, 'gsap_text_cursor', true)) {
			var cursor = document.createElement('span');
			cursor.className = 'gsap-et-cursor';
			cursor.setAttribute('aria-hidden', 'true');
			cursor.textContent = '|';
			target.appendChild(cursor);
		}

		target.setAttribute('aria-label', text);

		var vars = timing(config, element, api);
		vars.duration = text.length * Config.num(s, 'gsap_text_speed', 0.05);
		vars.ease = 'none';
		vars.text = { value: text, delimiter: '' };

		window.gsap.to(typed, vars);

		return function () {
			target.innerHTML = originalHtml;
			target.style.minHeight = minHeight;
			target.removeAttribute('aria-label');
		};
	}

	/* ----- counter ----- */

	function counter(element, target, config, api) {
		var s = config.settings || {};
		var original = target.textContent;
		var match = original.match(/-?\d[\d,]*(\.\d+)?/);

		if (!match) {
			return null;
		}

		var raw = match[0];
		var prefix = original.slice(0, match.index);
		var suffix = original.slice(match.index + raw.length);
		var end = parseFloat(raw.replace(/,/g, ''));
		var decimals = (raw.split('.')[1] || '').length;
		var separator = raw.indexOf(',') !== -1 || Config.bool(s, 'gsap_counter_separator', false);

		var format = function (number) {
			var fixed = number.toFixed(decimals);
			if (!separator) {
				return fixed;
			}
			var parts = fixed.split('.');
			parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
			return parts.join('.');
		};

		var state = { value: 0 };
		target.textContent = prefix + format(0) + suffix;

		var vars = timing(config, element, api);
		vars.value = end;
		vars.onUpdate = function () {
			target.textContent = prefix + format(state.value) + suffix;
		};

		window.gsap.to(state, vars);

		return function () {
			target.textContent = original;
		};
	}

	Toolkit.Engine.registerEffect('text', function (element, config, api) {
		var s = config.settings || {};
		var effect = Config.str(s, 'gsap_text_effect', 'split-words');
		var target = findTarget(element, Config.str(s, 'gsap_text_selector', ''));

		switch (effect) {
			case 'scramble':
				return scramble(element, target, config, api);
			case 'typewriter':
				return typewriter(element, target, config, api);
			case 'counter':
				return counter(element, target, config, api);
			default:
				return split(element, target, config, api, effect);
		}
	});
})();
