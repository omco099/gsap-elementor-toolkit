/**
 * Elementor bridge.
 *
 * Two separate paths:
 *  - Live site: PHP writes the settings on each animated element as
 *    data-gsap-et="{...}". We read them directly on DOM ready, so nothing
 *    depends on Elementor's own init event or its data-settings attribute.
 *  - Editor preview: an Elementor handler reads the live panel values and
 *    re-plays whenever a gsap_* control changes.
 */
(function ($) {
	'use strict';

	var Toolkit = window.GSAPToolkit;
	var ATTRIBUTE = 'data-gsap-et';
	var DONE_FLAG = '__gsapEtStarted';

	function isEditMode() {
		// The preview iframe URL always carries ?elementor-preview=ID. Checked first
		// because this file may load before Elementor prints elementorFrontendConfig.
		if (/[?&]elementor-preview=/.test(window.location.search)) {
			return true;
		}

		var config = window.elementorFrontendConfig;
		return !!(config && config.environmentMode && config.environmentMode.edit);
	}

	function readSettings(element) {
		try {
			return JSON.parse(element.getAttribute(ATTRIBUTE)) || {};
		} catch (error) {
			return {};
		}
	}

	/* ---------- Live site ---------- */

	function runFrontend() {
		if (!Toolkit || !Toolkit.Engine || !Toolkit.Config) {
			return;
		}

		var elements = document.querySelectorAll('[' + ATTRIBUTE + ']');

		Array.prototype.forEach.call(elements, function (element) {
			if (element[DONE_FLAG]) {
				return;
			}

			element[DONE_FLAG] = true;
			Toolkit.Engine.play(element, Toolkit.Config.fromElementor(readSettings(element)));
		});

		// Images and web fonts change the page height after DOM ready:
		// recalculate every ScrollTrigger start/end once everything has loaded.
		if (elements.length && window.ScrollTrigger) {
			window.addEventListener('load', function () {
				window.ScrollTrigger.refresh();
			}, { once: true });

			if (document.fonts && document.fonts.ready) {
				document.fonts.ready.then(function () {
					window.ScrollTrigger.refresh();
				});
			}
		}
	}

	/* ---------- Editor preview ---------- */

	function bootEditor() {
		if (!Toolkit || !Toolkit.Engine || !Toolkit.Config || !window.elementorModules || !window.elementorFrontend) {
			return;
		}

		var Base = elementorModules.frontend.handlers.Base;

		var Handler = Base.extend({
			replayTimer: null,

			onInit: function onInit() {
				Base.prototype.onInit.apply(this, arguments);
				this.play();
			},

			play: function play() {
				var element = this.$element[0];

				if (this.getElementSettings('gsap_enable') !== 'yes') {
					Toolkit.Engine.kill(element);
					return;
				}

				Toolkit.Engine.play(element, Toolkit.Config.fromElementor(this.getElementSettings()));
			},

			onElementChange: function onElementChange(propertyName) {
				if (propertyName.indexOf('gsap_') !== 0) {
					return;
				}

				var self = this;
				clearTimeout(this.replayTimer);
				this.replayTimer = setTimeout(function () {
					self.play();
				}, 250);
			},

			onDestroy: function onDestroy() {
				clearTimeout(this.replayTimer);
				Toolkit.Engine.kill(this.$element[0]);
				Base.prototype.onDestroy.apply(this, arguments);
			}
		});

		elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($element) {
			elementorFrontend.elementsHandler.addHandler(Handler, { $element: $element });
		});
	}

	/* ---------- Start ---------- */

	if (isEditMode()) {
		$(window).on('elementor/frontend/init', bootEditor);
		return;
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', runFrontend, { once: true });
	} else {
		runFrontend();
	}
})(jQuery);
