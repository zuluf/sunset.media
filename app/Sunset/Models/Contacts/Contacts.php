<?php

namespace Sunset\Models;

use Sunset\Models;
use \Sunset\Request;

/**
 *	Class Sunset\Models\Contacts
 *
 */
class Contacts extends Models {

	/**
	 * Contacts model table name
	 *
	 * @var string
	 */
	protected static $_table = 'contacts';

	/**
	 * Contacts model table primary key
	 *
	 * @var string
	 */
	protected static $_primary = 'contact_id';

	/**
	 * Static instance property for holding the model instance
	 *
	 * @var \Sunset\Models\Contacts
	 */
	protected static $_instance;

	/**
	 * Calls the parent get method and parses the result row set
	 *
	 * @param  array 	$where
	 * @param  array 	$unique
	 * @return array|object|bool
	 */
	public static function get ( $where = array(), $unique = false ) {
		$contact = parent::get( $where, $unique );

		if ( ! empty ( $contact ) ) {
			$contact = ! is_array ( $contact ) ? array ( $contact ) : $contact;

			foreach ( $contact as & $value ) {
				$value->contact = static::decode( $value->contact );
			}

			return $unique ? array_shift( $contact ) : $contact;
		}

		return false;
	}

	/**
	 * Prepares the given data for the database insert; Data should contain column_name => value properties
	 *
	 * @param  array 		$wikipage
	 * @return object|bool 	Returns false on failure
	 */
	public static function insert ( $message = false ) {

		if ( ! empty ( $message ) ) {
			$insert = array (
				'content' => (string) $message->content,
				'contact' => static::encode( $message->contact ),
				'visitor_ip' => $message->visitor_ip
			);

			return parent::insert ( $insert );
		}

		return false;
	}
}