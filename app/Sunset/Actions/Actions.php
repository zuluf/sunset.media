<?php

namespace Sunset;

use \Sunset\Events;
use \Sunset\Request;
use \Sunset\Errors;
use \Sunset\Helpers;

/**
 *	Class Actions
 *
 */
class Actions {

	/**
	 * Static response object
	 *
	 * @var object
	 */
	private static $_response;

	/**
	 * Returns current errors collection, or a specific type errors for the given type
	 *
	 * @return array
	 */
	public static function fire () {

		$request = Request::load();

		$request->class = Helpers::camelClass( $request->resource, '\Sunset\Actions' );

		$request->action = array_shift( $request->params );

		if ( ! Errors::hasErrors() ) {
			if ( class_exists( $request->class ) && is_callable( array( $request->class, $request->action ) ) ) {
				static::$_response = call_user_func ( array ( $request->class , $request->action ), array_shift ( $request->params ), $request->data );
			} else {
				Errors::set('router', 'Resource not found: ' . $request->class . '::' . $request->action, true );
			}
		}

		Events::register( 'shutdown', function () {
			return static::response();
		});
	}

	/**
	 * Returns current errors collection, or a specific type errors for the given type
	 *
	 * @return array
	 */
	public static function response () {

		if ( !headers_sent() ) {
			header('Content-Type: application/json');
		}

		$response = array(
			'error' => Errors::get(),
			'data' => static::$_response,
			'time' => ( microtime(true) - Request::getStart() )
		);

		die( json_encode( $response ) );
	}
}