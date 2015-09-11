<?php

namespace Sunset\Actions;

use \Sunset\Models\Visitors as Model;
use \Sunset\Errors;
use \Sunset\Request;

/**
 *	Class \Sunset\Actions\Visitors
 *
 */
class Visitors {

	/**
	 * Saves the current ip/user agent pair visit number
	 *
	 * @return array
	 */
	public static function save () {

		$request = Request::load();

		$visit = Model::get( array (
			'visitor_ip' => $request->ip_address,
			'user_agent' => $request->user_agent
		), true);

		if ( empty( $visit ) ) {
			return Model::insert( (object) array (
				'visitor_ip' => $request->ip_address,
				'user_agent' => $request->user_agent,
				'visits' => 1
			));
		} else {
			return $visit->save( array ( 'visits' => ($visit->visits + 1 ) ) );
		}
	}
}