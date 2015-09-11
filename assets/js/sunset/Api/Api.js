(function($, window){
	var Api, Request;

	/**
	 * Starts the app ajax request based on the app params
	 *
	 * @param  {String} 	action	url of the action we want to execute
	 * @param  {Object} 	data	data to send to the back-end node
	 * @param  {String} 	type	define the type of the call to invoke
	 * @return {Object}     deferred ajax object
	 */
	Request = function Request (action, data, type) {
		return $.ajax({
			url : action,
			type : type,
			data: data
		});
	}

	/**
	 * Function wrapper for app ajax calls
	 */
	Api = function Api () {
		return $.extend(true, {}, this.prototype, {

			/**
			 * Starts the get ajax request
			 *
			 * @param  {String} 	action	url of the action we want to execute
			 * @param  {Object} 	data	data to send to the back-end node
			 * @return {Object}     deferred ajax object
			 */
			get : function get (action, data) {
				return new Request(action, data, 'get');
			},

			/**
			 * Starts the post ajax request
			 *
			 * @param  {String} 	action	url of the action we want to execute
			 * @param  {Object} 	data	data to send to the back-end node
			 * @return {Object}     deferred ajax object
			 */
			post : function post (action, data) {
				return new Request(action, data, 'post');
			}
		})
	};

	/**
	 * Add Api to the global scope
	 */
	if (window) {
		window.Api = new Api();
	}

	return Api;
})(jQuery, window);