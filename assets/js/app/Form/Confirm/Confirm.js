(function($, window){
	/**
	 * Register 'Form.Confirm' control
	 */
	Control.extend('Form.Confirm', {
		/**
		 * Shows control element
		 *
		 * @return void
		 */
		show : function (element, options) {
			this.element.css('opacity', 1);
		},

		/**
		 * Hides control element
		 *
		 * @return void
		 */
		hide : function (element, options) {
			this.element.css('opacity', 0);
		}
	});
})(jQuery, window);