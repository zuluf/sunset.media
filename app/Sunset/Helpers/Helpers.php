<?php

namespace Sunset;

/**
 * Class Helpers
 */

class Helpers {

	/**
	 * Camelize "example-class" or "Example_Class" to ExampleClass; add's class namespace if param provided
	 *
	 * @param  string  $className
	 * @param  string  $namespace
	 * @return string
	 */
	public static function camelClass ( $className, $namespace = "" ) {

		if ( ! empty( $namespace ) ) {
			$namespace = '\\' . trim ( (string) $namespace, '\\' ) . '\\';
		}

		$className = str_replace( array( '_' ), '-', $className );

		return $namespace . implode ('', array_map( 'ucfirst', explode( '-', $className ) ) );
	}

	/**
	 * Returns all valid emails from the given string
	 *
	 * @param  string  $string
	 * @return array
	 */
	public static function getEmails ( $string = "" ) {
		if ( empty( $string ) || ! is_string( $string ) ) {
			return false;
		}

		$emails = preg_match_all('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,12}\b/i', $string, $matches);

		return ! empty( $emails ) && isset( $matches[0] ) ? $matches[0] : false;
	}

	/**
	 * Returns all valid emails from the given string
	 *
	 * @param  string  $string
	 * @return array
	 */
	public static function getPhoneNumbers ( $string = "" ) {
		if ( empty( $string ) || ! is_string( $string ) ) {
			return false;
		}

		$numbers = preg_match_all('/\+?(\d[.\- ]*){9,14}(e?xt?\d{1,5})?/', $string, $matches);

		return ! empty( $numbers ) && isset( $matches[0] ) ? $matches[0] : false;
	}
}