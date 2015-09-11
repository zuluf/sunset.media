<?php

namespace Sunset\Actions;

use \Sunset\Models\Contacts as Model;
use \Sunset\Mail;
use \Sunset\Config;
use \Sunset\Errors;
use \Sunset\Request;
use \Sunset\Helpers;

/**
 *	Class \Sunset\Actions\Contacts
 *
 */
class Contacts {

	/**
	 * Saves user message to database
	 *
	 * @return object
	 */
	public static function save ( $params = false, $data = false ) {

		$content = isset( $data[ 'content' ] ) && is_string( $data[ 'content' ] ) ? trim ( $data[ 'content' ] ) : false;

		if ( ! empty( $content ) ) {

			$request = Request::load();

			$contact = (object) array (
				'content' => $content,
				'contact' => array (
					'emails' => Helpers::getEmails( $content ),
					'numbers' => Helpers::getPhoneNumbers( $content )
				),
				'visitor_ip' => $request->ip_address
			);

			return Model::insert( $contact );
		}

		return false;
	}

	/**
	 * Sends message to config.contacts.email value
	 *
	 * @return object
	 */
	public static function send ( $params = false, $data = false ) {
		$config = Config::get('contacts');

		if ( isset( $config->email ) && ! empty( $config->email ) ) {
			$contact_id = isset( $data[ 'contact_id' ] ) ? (int) $data[ 'contact_id' ] : false;

			if ( ! empty( $contact_id ) ) {
				$message = Model::get( array( 'contact_id' => $contact_id ), true );
				if ( ! empty( $message ) ) {
					$signature = (string) array_pop( explode("\n", $message->content) );

					return Mail::send( $config->email, "New message from " . ucfirst( $signature ), $message->content );
				}
			}
		}

		return false;
	}
}