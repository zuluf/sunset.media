(function($, window){
	/**
	 * Register 'Upgrade' control
	 */
	Control.extend('Upgrade', {
		/**
		 * Controls init function, adds upgrade browser message on the contact form
		 * Add's app background image since background-size: cover; is probably not supported
		 *
		 * @return void
		 */
		init : function (element, options) {
			this.element.html(
				'<span class="line">Thank you for your interest.</span>' +
				'<span class="line">In the meantime, please checkout how to upgrade your browser to a modern version.</span>' +
				'<span class="line">Cheers :)</span>'
			);

			this.element.parents('body').prepend(
				'<div id="background">' +
					'<img src="assets/img/back.jpg" />' +
				'</div>'
			);
		}
	});
})(jQuery, window);