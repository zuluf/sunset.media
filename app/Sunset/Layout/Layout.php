<?php

namespace Sunset;

/**
 *	Class Layout
 *
 */
class Layout {

	/**
	 * Loads default layout files (content, header, footer) depending on the given $config params
	 *
	 * @param  array   $config
	 * @return string
	 */
	public static function fire ( $config = array() ) {

		Scripts::register();

		$defaults = array(
			'header' => true,
			'content' => true,
			'footer' => true
		);

		$config = array_merge( $defaults, (array) $config );

		$loadDir = __DIR__ . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR;

		$load = array();

		foreach( $config as $_file => $_include ) {
			if ( $_include ) {
				static::includeFile( $loadDir . $_file . '.php' );
			}
		}
	}

	/**
	 * Includes file by given filepath
	 *
	 * @param  string   $filepath
	 * @return content|void
	 */
	private static function includeFile ( $filepath = false ) {

		if ( is_file ( $filepath ) ) {
			include $filepath;
		}
	}
}
