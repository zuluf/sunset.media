<?php

namespace Sunset;

/**
 * Registers the Sunset namespace class file load function
 */
spl_autoload_register( function ( $class ) {

	if ( class_exists( $class ) || interface_exists( $class ) ) {
		return;
	}

	if ( ! strstr( $class, 'Sunset\\') ) {
		return;
	}

	$name = explode( '\\', $class );
	$className = DIRECTORY_SEPARATOR . array_pop ( $name ) . '.php';
	$base = dirname( __FILE__ ). DIRECTORY_SEPARATOR;
	$file = str_replace('\\', '/', $base . $class . $className);

	if ( is_file ( $file ) ) {
		require $file;
	}
});