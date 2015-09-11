<?php

namespace Sunset;

use \Sunset\Request;
use \Sunset\Events;
use \Sunset\Layout;
use \Sunset\Actions;
use \Sunset\Actions\Visitors;

/**
 *	Class Router
 *
 */
class Router {

	/**
	 * Renders the response for the given request
	 *
	 * @return void
	 */
	public static function fire () {

		$request = Request::load();

		if ( $request->method === "GET" ) {
			Visitors::save();
			Layout::fire();
		} else if ( $request->method === "POST" ) {
			Actions::fire();
		}
	}
}