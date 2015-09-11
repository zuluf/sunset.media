(function (window) {

	if (Object && typeof Object.create !== "function") {
		/**
		 * Add a Object.create polyfill
		 *
		 * @param  {Object} object
		 * @param  {Object} props
		 * @return {Object} cloned object
		 */
		Object.create = function (object, props) {
			var clone = {};

			if (typeof object === "object") {
				function Clone() {};
				Clone.prototype = object;
				clone = new Clone();

				if (props && typeof props === "object") {
					for (var prop in props) {
						if (props.hasOwnProperty((prop))) {
							clone[prop] = props[prop].value;
						}
					}
				}
			}

			return clone;
		}
	}

	if (Object && typeof Object.keys !== "function") {
		/**
		 * Add a Object.keys polyfill
		 *
		 * @param  {Object} object
		 * @return {Array}  list of object property names
		 */
		Object.keys = function (object) {
			var keys = [];

			if (object && typeof object === "object") {
				for (var prop in object) {
					if (object.hasOwnProperty((prop))) {
						keys.push(prop);
					}
				}
			}

			return keys;
		}
	}

	if (Array && typeof Array.prototype.indexOf !== "function") {
		/**
		 * Add a Array.indexOf polyfill
		 *
		 * @param  {Mixed} 	element
		 * @param  {Number} from
		 * @return {Array}  list of object property names
		 */
		Array.prototype.indexOf = function (element, from) {
			var length;

			length = this.length;
			from = Number(from) || 0;
			from = (from < 0) ? Math.ceil(from) : Math.floor(from);

			if (from < 0) {
				from += length;
			}

			for(; from < length; from++) {
				if (from in this && this[from] === element) {
					return from;
				}
			}

			return -1;
		}
	}

	if (Function && typeof Function.prototype.bind !== "function") {
		/**
		 * Add a Function.bind polyfill
		 *
		 * @param  {Function} callable
		 * @return {Function} new instance of the bind callable function
		 */
		Function.prototype.bind = function (callable) {
			if (typeof this !== "function") {
				throw new TypeError("Function.prototype.bind - object is not callable");
			}

			var slice = Array.prototype.slice,
				args = slice.call(arguments, 1),
				instance = this,
				clone = function() {},
				cloned = function() {
					return instance.apply(this instanceof clone ? this : callable || window, args.concat(slice.call(arguments)));
				};
			clone.prototype = this.prototype;
			cloned.prototype = new clone();
			return cloned;
		};
	}
})(window);