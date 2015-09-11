<?php

namespace Sunset;

use \Sunset\Config;
use \Sunset\Router;
use \Sunset\Events;

/**
 *	Class Init
 *
 */
class Init {

	/**
	 * Initiates the applicaton, laods the config and starts the router
	 *
	 * @return void
	 */
	public static function app () {

		Config::load();

		Router::fire();

		register_shutdown_function( function () {
			Events::fire( 'shutdown' );
		});
	}
}