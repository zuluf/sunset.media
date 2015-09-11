<?php

namespace Sunset\Request;

/**
 *	Class Server
 *
 *  notes: https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet
 */
class Server {

	/**
	 * Static server object
	 *
	 * @var object
	 */
	private static $_server;

	/**
	 * Returns current server object
	 *
	 * @return object
	 */
	public static function get() {
		if ( ! empty ( static::$_server ) ) {
			return static::$_server;
		}

		return static::parse();
	}

	/**
	 * Parses server data to static variable
	 *
	 * @return object
	 */
	private static function parse() {

		$server = (object) $_SERVER;

		static::$_server = (object) array (
			'http_host' => null,
			'http_user_agent' => '',
			'server_port' => null,
			'request_uri' => '/',
			'remote_addr' => null,
			'content_type' => '',
			'request_method' => 'GET',
			'query_string' => ''
		);

		static::$_server->ip_address = static::getRemoteAddr( $server );

		foreach (static::$_server as $key => $value) {
			if ( isset ( $server->{strtoupper($key) } ) ) {
				static::$_server->{$key} = $server->{ strtoupper($key) };
			}
		}

		return static::$_server;
	}

	/**
	 * Parses server headers for the users best ip match
	 *
	 * @param  object $server
	 * @return string
	 */
	public static function getRemoteAddr( $server ) {
		if ( empty( $server ) ) {
			return "";
		}

		$forwarded = isset( $server->FORWARDED ) && ! empty( $server->FORWARDED ) ? trim( $server->FORWARDED ) : false;
		$forwardedFor = isset( $server->X_FORWARDED_FOR ) && ! empty( $server->X_FORWARDED_FOR ) ? trim( $server->X_FORWARDED_FOR ) : false;
		if ( $forwarded ) {
			preg_match_all('{(for)=("?\[?)([a-z0-9\.:_\-/]*)}', $forwarded, $matches);
			$ips = $matches[3];
		} else {
			$ips = array_map( 'trim', explode( ',', $forwardedFor ) );
		}

		$ips[] = isset( $server->REMOTE_ADDR ) && ! empty( $server->REMOTE_ADDR ) ? trim( $server->REMOTE_ADDR ) : false;

		$ips = array_reverse( array_filter( $ips ) );

		return array_shift( $ips );
	}
}