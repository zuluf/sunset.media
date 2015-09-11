(function($, window){
	/**
	 * Register 'Form.Textarea' control
	 */
	Control.extend('Form.Textarea',	{
		/**
		 * Adds focus class on textarea focus event
		 *
		 * @return void
		 */
		'focus' : function () {
			this.element.addClass('focus');
		},

		/**
		 * Removes focus class if textarea is empty
		 *
		 * @return void
		 */
		'blur' : function () {
			if (this.element.val() === '') {
				this.element.removeClass('focus');
			}
		}
	});
})(jQuery, window);