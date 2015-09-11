<?php

namespace Sunset;

use \DateTime;

/**
 *	Class \Sunset\Date
 *
 */
class Date extends DateTime {

	/**
	 * Static config object
	 *
	 * @var object
	 */
	private static $_instance;

	/**
	 * Supported formats
	 *
	 * @var array
	 */
	private static $_formats = array (
		'mysqli' => "Y-m-d H:i:s"
	);

	/**
	 * Returns formated datetime for the given unix timestamp;
	 * If no unix timestamp value is passed function returns the current timestamp
	 * Why? Because this is not a framework and i'm making the app do just what it needs to :P
	 *
	 * @param  int 		$datetime unix timestamp
	 * @param  string	$format   desired datetime format
	 * @return string|bool
	 */
	public static function formatDateTime ( $datetime = 0, $format = '' ) {

		if ( ! is_string( $format ) || empty( $format ) ) {
			return false;
		}

		$datetime = ! empty( $datetime ) && is_numeric( $datetime ) ? $datetime : static::instance()->getTimestamp();

		return date( $format, $datetime );
	}

	/**
	 * Returns mysqli formated datetime
	 *
	 * @return string
	 */
	public static function mysqli ( $datetime = null ) {
		return static::formatDateTime( $datetime, static::$_formats[ 'mysqli' ] );
	}

	/**
	 * Returns unix formated datetime
	 *
	 * @return string
	 */
	public static function unix ( $datetime = null ) {
		return strtotime( $datetime );
	}

	/**
	 * Returns static class instance
	 *
	 * @return \Ibt\Date
	 */
	public static function instance() {
		if ( empty( static::$_instance ) ) {
			static::$_instance = new static;
		}

		return static::$_instance;
	}
}