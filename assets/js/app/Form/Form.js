(function(){
	/**
	 * Register 'Form' control
	 */
	Control.extend('Form', {

		/**
		 * Last message user sent to the server
		 *
		 * @var {String}  this.lastMessage
		 */
		lastMessage: "",

		/**
		 * Initiates form controls
		 *
		 * @param  {Object}  element
		 * @param  {Object}  options
		 * @return void
		 */
		init : function (element, options) {
			this.button = Control('Form.Button', element.find('[data-action="contact"]'));
			this.confirm = Control('Form.Confirm', element.find('[data-action="confirm"]'));
			this.textarea = Control('Form.Textarea', element.find('textarea'));
		},

		/**
		 * Resets form control to the default state
		 *
		 * @param  {Boolean} enable
		 * @return void
		 */
		reset : function (enable) {
			this.button.reset(enable);
		},

		/**
		 * Binds the textarea keyup event, and reset's the form depending on the textarea content
		 *
		 * @return void
		 */
		'textarea keyup' : function () {
			var message = this.textarea.val();

			if (message !== this.lastMessage) {
				this.reset(!!message);
				this.confirm.hide();
			} else if (message) {
				this.reset(false);
			} else {
				this.confirm.hide();
			}
		},

		/**
		 * Binds the form submit event, and sends the message to the server
		 *
		 * @return void
		 */
		'[data-action="contact"] click' : function () {
			var message, textarea;

			if (this.button.isDisabled()) {
				return;
			}

			message = this.textarea && this.textarea.val();

			if (message && this.lastMessage !== message) {

				this.lastMessage = message;

				Api.post('contacts/save', {content: message}).then(function (response) {
					if (response && response.data && response.data.contact_id) {
						Api.post('contacts/send', {contact_id: response.data.contact_id});

						this.button.hide('Message saved');
						this.confirm.show();
					}
				}.bind(this));
			}
		}
	});
})();