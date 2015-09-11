(function($, window){
	var scripts, Script, defaults;

	/**
	 * Scripts collecton object
	 *
	 * @var {Object} scripts
	 */
	scripts = {};

	/**
	 * Default script methods holder
	 *
	 * @var {Object} defaults
	 */
	defaults = {

		/**
		 * Default abstract script init function
		 *
		 * @return void
		 */
		init: function init () {},

		/**
		 * Scripts setup function; Sets script options properties, and calls script init function
		 *
		 * @param  {Object} options 	script options
		 * @return {Mixed}
		 */
		setup : function setup (options) {

			this.options = options || this.options || {};

			this.init(this.options);

			return this;
		}
	};

	/**
	 * Script constructor function; Creates new script for the given parameters extended with default functions
	 *
	 * @param 	{String} 	name 		default script name
	 * @param 	{Object} 	options		script options
	 * @return  {Object}    script object
	 */
	Script = function Script (name, options) {

		if (!scripts[name]) {
			throw Error('Script [' + name + '] : not implemented');
		}

		return Object.create(scripts[name]).setup(options);
	};

	/**
	 * Script extend function for registering new scripts
	 *
	 * @param  {String} name  		name of the registered control
	 * @param  {Object} script  	new script properties to merge with the defaults
	 * @return {Object} registered control
	 */
	Script.extend = function Script (name, script) {
		return (
			scripts[name] = scripts[name] ||
			$.extend({
				name: function() {
					return name
				}
			}, Object.create(defaults), script));
	};

	/**
	 * Add Script to the global scope
	 */
	if (window) {
		window.Script = Script;
	}

	return Script;
})(jQuery, window);