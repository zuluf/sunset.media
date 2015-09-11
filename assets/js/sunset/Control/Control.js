(function($, window){
	var controls, events, defaults, Control, jClean;

	/**
	 * Controls collecton object
	 *
	 * @var {Object} controls
	 */
	controls = {};

	/**
	 * List of event names available for controls element binding
	 *
	 * @var {Array} defaultEvents
	 */
	defaultEvents = ["change", "click", "contextmenu", "dblclick", "keydown", "keyup",
		"keypress", "mousedown", "mousemove", "mouseout", "mouseover",
		"mouseup", "reset", "resize", "scroll", "select", "submit", "focusin",
		"focusout", "mouseenter", "mouseleave",
		"touchstart", "touchmove", "touchcancel", "touchend", "touchleave",
		"inserted","removed"
	];

	/**
	 * Original jQuery.cleanData function holder
	 *
	 * @var {Function} jClean
	 */
	jClean = $.cleanData;


	/**
	 * Overwritten jQuery.cleanData function
	 *
	 * @var {Function} $.cleanData
	 */
	$.cleanData = function(elems) {
		for ( var i = 0, elem; (elem = elems[i]) !== undefined; i++ ) {
			$(elem).triggerHandler("destroyed");
		}
		// call the overwriten jQuery clean fn
		jClean(elems);
	};

	/**
	 * Default control methods holder
	 *
	 * @var {Object} defaults
	 */
	defaults = {

		/**
		 * Default abstract control init function
		 *
		 * @return void
		 */
		init: function init () {},

		/**
		 * Controls setup function; Sets control element and options properties, and creates a control instance
		 *
		 * @param  {Object} element dom node element
		 * @param  {Object} control state options
		 * @return {Object} control instance
		 */
		setup : function setup (element, options) {

			this.element = element || this.element || null;
			this.options = options || this.options || {};

			return this.instance();
		},

		/**
		 * Binds control events, creates default options, triggers control init
		 *
		 * @return {Object} control instance
		 */
		instance: function instance () {
			var events, callback, defaults, args;

			// bind events
			if (!this.isBound()) {
				defaults = defaultEvents.concat(Object.keys($.event.special));

				// trigger onBind events
				this.onBind(this.element, this.options);

				for (var property in this) {

					args = [];
					callback = this[property];

					if (typeof callback === "function") {

						if (~property.indexOf(' ')) {
							args = property.split(' ').reverse();
						} else if (~defaults.indexOf(property)) {
							args.push(property);
						}

						if (args.length) {
							args.push(function(callback, event) {
								callback.call(this, event, $(event.target));
							}.bind(this, callback));

							this.element.on.apply(this.element, args);
						}

					}
				}

				// bind element destroyed
				this.element.on('destroyed', this.destroy.bind(this));
			}

			if (this.defaults) {
				this.options = $.extend(this.defaults, this.options);
			}

			this.init(this.element, this.options);

			return this;
		},

		/**
		 * Removes binded events from controls element
		 *
		 * @return void
		 */
		destroy: function destroy () {
			if (this.element && this.element.length) {
				for (var i in this) {
					if (~i.indexOf(' ') && typeof this[i] === "function") {
						this.element.off(i.split(' ').pop(), this[i]);
					}
				}
			}
		},

		/**
		 * Removes controls element;
		 * Removing of the element will also trigger the 'destroyed' event for the control instance
		 *
		 * @return void
		 */
		remove : function remove () {
			if (this.element) {
				this.element.remove();
			}
		},

		/**
		 * Returns current control element bound events
		 *
		 * @return {Object}
		 */
		getEvents : function getEvents () {
			return this.element ? $._data(this.element[0], 'events') : undefined;
		},

		/**
		 * Check's if the current control element has bound events
		 *
		 * @return {Boolean}
		 */
		isBound : function isBound () {
			return !!this.getEvents();
		},

		/**
		 * Default abstract onBind function
		 *
		 * @return void
		 */
		onBind: function onBind () {},

		is : function is () {
			return $.fn.is.apply(this.element, arguments);
		},

		/**
		 * jQuery .html() wrapper for the controls element
		 *
		 * @return {String}
		 */
		html : function html (html) {
			return this.element && this.element.html(html);
		},

		/**
		 * jQuery .text() wrapper for the controls element
		 *
		 * @return {String}
		 */
		text : function text (text) {
			return this.element && this.element.text(text);
		},

		/**
		 * Add display: none css class to the control element
		 *
		 * @return {Object} jQuery element
		 */
		hide : function hide () {
			return this.element && this.element.addClass('dn');
		},

		/**
		 * Removes display: none css class from the control element
		 *
		 * @return {Object} jQuery element
		 */
		show : function show () {
			return this.element && this.element.removeClass('dn');
		},

		/**
		 * jQuery .val() wrapper for the controls element
		 *
		 * @return {Mixed} value of the controls element
		 */
		val : function val () {
			return this.element && this.element.val();
		}
	};

	/**
	 * Control constructor function; Creates new control for the given parameters with default functions
	 *
	 * @param 	{String} 	name 		default control name
	 * @param 	{Object} 	element		control element
	 * @param 	{Object} 	options		control options
	 * @return  {Object}    control object
	 */
	Control = function Control (name, element, options) {

		if (!controls[name]) {
			throw Error('Control [' + name + '] : not implemented');
		}

		if ((!element || !$(element).length) && !controls[name].element) {
			throw Error('Control [' + name + '] : add controls DOM node you dumb fuck');
		}

		return Object.create(controls[name]).setup(element, options);
	}

	/**
	 * Control extend function for registering new controls
	 *
	 * @param  {String} name  		name of the registered control
	 * @param  {Object} control  	new control properties to merge with the defaults
	 * @return {Object} registered control
	 */
	Control.extend = function Control (name, control) {
		return (
			controls[name] = controls[name] ||
			$.extend({
				name: function() {
					return name
				}
			}, Object.create(defaults), control));
	}

	/**
	 * Add Control to the global scope
	 */
	if (window) {
		window.Control = Control;
	}

	return Control;
})(jQuery, window);