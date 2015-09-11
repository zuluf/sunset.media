<?php

namespace Sunset;

use \Sunset\Errors;

/**
 *	Class Mail
 */
class Mail {

	/**
	 * Default email headers
	 *
	 * @var array
	 */
	private static $_headers = array(
		'From: Sunset Media <contact@sunset.media>',
		'Content-type: text/html; charset=iso-8859-1',
		'MIME-Version: 1.0',
		'X-Mailer: PHP/5.3.0'
	);

	/**
	 * Sends a email to the given $email address
	 *
	 * @param  string $server
	 * @return bool
	 */
	public static function send( $emails, $subject = "", $message = "", $headers = array() ) {

		if ( empty( $emails ) ) {
			return false;
		}

		if ( ! is_array( $emails ) ) {
			$emails = array( $emails );
		}

		$emails = implode( ', ', $emails);

		$headers[] = 'To: ' . $emails;
		$headers = implode( "\r\n", array_merge( static::$_headers, $headers ) );

		$message = str_replace(PHP_EOL, '<br />', $message);

		$mail = @mail( $emails, $subject, $message, $headers);

		return $mail;
	}
}