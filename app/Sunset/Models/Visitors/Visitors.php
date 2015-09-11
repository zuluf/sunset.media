<?php

namespace Sunset\Models;

use Sunset\Models;

/**
 *	Class Sunset\Models\Visitors
 *
 */
class Visitors extends Models {

	/**
	 * Visitors model table name
	 *
	 * @var string
	 */
	protected static $_table = 'visitors';

	/**
	 * Visitors model table primary key
	 *
	 * @var string
	 */
	protected static $_primary = 'visitor_id';

	/**
	 * Static instance property for holding the model instance
	 *
	 * @var \Sunset\Models\Visitors
	 */
	protected static $_instance;


	/**
	 * Prepares the given data for the database insert; Data should contain column_name => value properties
	 *
	 * @param  array 		$wikipage
	 * @return object|bool 	Returns false on failure
	 */

	public static function insert ( $visitor = false ) {

		if ( ! empty ( $visitor ) ) {
			$insert = array (
				'visitor_ip' => static::escape($visitor->visitor_ip),
				'user_agent' => static::escape($visitor->user_agent),
				'visits' => ( isset($visitor->visits) ? (int) $visitor->visits : 1 )
			);

			return parent::insert ( $insert );
		}

		return false;
	}
}