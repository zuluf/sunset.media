(function(){
	/**
	 * Register 'Init' script
	 */
	Script.extend('Init', {

		/**
		 * True if environment is a mobile or a tablet device
		 *
		 * @var {Boolean}  this.mobile
		 */
		mobile: false,

		/**
		 * Device/browser minimum support setting
		 *
		 * @var {Object}  this.support
		 */
		support : {
			desktop : {
				ie : 9,
				opera : 11,
				firefox : 4,
				chrome : 5,
				safari : 5
			},

			mobile : {
				firefox : 4,
				opera : 11
			}
		},

		/**
		 * Parses the environment browser/device, bind's resize function and initiates the form controll
		 *
		 * @return void
		 */
		init: function () {
			this.parseBrowser();
			this.resize();

			if ( $('.js-form').length ) {
				Control('Form', $('.js-form'));
			}
		},

		/**
		 * Parses the environment browser/device and adds appropriate classes to the app <body>
		 * In case device or browser are not supported, starts the Upgrade control for handling exceptions
		 *
		 * @return void
		 */
		parseBrowser:  function () {
			var name, version, support;

			this.parser = new UAParser();
			this.browser = this.parser.getBrowser();
			this.device = this.parser.getDevice();

			if (this.device && this.device.type) {
				this.mobile = !!~['tablet', 'mobile'].indexOf(this.device.type);
				$('body').addClass(this.device.type);
			}

			if (this.browser && this.browser.name) {
				name = this.browser.name.toLowerCase();
				version = parseInt(this.browser.major) || 0;

				$('body').addClass(name + ' ' + name + '-' + version);

				support = this.mobile ? this.support.mobile : this.support.desktop;

				if (support[name] && version < support[name]) {
					Control('Upgrade', $('.js-form [data-action="confirm"]'));
				}
			}
		},

		/**
		 * Sets content height depending on the browser available height
		 *
		 * @return void
		 */
		setContentHeight: function setContentHeight() {
			var content, body;

			body = $('body').innerHeight() - $('#footer').height() - $('#header').height() - 100;
			content = this.initialHeight + 210;

			$('#content').css('height', (content < body ? body : content));
		},

		/**
		 * Binds browser resize function and calls this.setContentHeight
		 *
		 * @return void
		 */
		resize: function resize() {
			this.initialHeight = $('#content').height();

			$(window).on('resize', function () {
				this.setContentHeight();
			}.bind(this));

			this.setContentHeight();
		}
	});
})();