/**
 * Elementor bridge.
 * Reads the GSAP controls of every element (works on the live site AND live
 * in the editor) and hands them to the Engine. Re-plays when a gsap_* control
 * changes in the editor panel.
 */
(function ($) {
	'use strict';

	function boot() {
		var Toolkit = window.GSAPToolkit;
		if (!Toolkit || !Toolkit.Engine || !Toolkit.Config || !window.elementorModules) {
			return;
		}

		var Handler = elementorModules.frontend.handlers.Base.extend({
			replayTimer: null,

			onInit: function onInit() {
				elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				this.play();
			},

			isEnabled: function isEnabled() {
				return this.getElementSettings('gsap_enable') === 'yes';
			},

			play: function play() {
				var element = this.$element[0];

				if (!this.isEnabled()) {
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
				elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(this, arguments);
			}
		});

		elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($element) {
			elementorFrontend.elementsHandler.addHandler(Handler, { $element: $element });
		});
	}

	$(window).on('elementor/frontend/init', boot);
})(jQuery);
