(function($, window){
	/**
	 * Register 'Form.Button' control
	 */
	Control.extend('Form.Button', {
		/**
		 * Default options
		 *
		 * @var {Object}  this.defaults
		 */
		defaults : {
			message: 'Send'
		},

		/**
		 * Buttons init function; Resets the element to the default state
		 *
		 * @param  {Object}  element
		 * @param  {Object}  options
		 * @return void
		 */
		init : function (element, options) {
			this.reset();
		},

		/**
		 * Resets the control state
		 *
		 * @param  {Boolean}  show
		 * @return void
		 */
		reset : function (show) {
			if (show) {
				this.element.removeClass('disabled');
			} else {
				this.element.addClass('disabled');
			}
			this.element.removeClass('hide');
			this.element.find('span:first').text(this.options.message);
		},

		/**
		 * Add's the hide css class to the element
		 *
		 * @return void
		 */
		hide : function (message) {
			this.element.addClass('hide');
		},

		/**
		 * Checks if the button element is disabled
		 *
		 * @return {Boolean}
		 */
		isDisabled: function () {
			return this.element.hasClass('disabled');
		}
	});
})(jQuery, window);