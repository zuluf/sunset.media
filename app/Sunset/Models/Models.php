<?php

namespace Sunset;

use \Sunset\Errors;
use \Sunset\Data;
use \Sunset\Date;

/**
 *	Class Sunset\Models
 *
 */
class Models {

	/**
	 * Executes a query on a database
	 *
	 * @param  string $query
	 * @return mixed
	 */
	private static function query ( $query = "" ) {

		$data = Data::query( $query );

		if ( isset( $data->error ) && ! empty( $data->error ) ) {
			Errors::log ( $query, 'error query' );
			Errors::set ( 'database', $data->error, true );
			return null;
		}

		return $data;
	}

	/**
	 * Executes SELECT query for the current table instance
	 *
	 * @param  array  $where    Array of column => value to add to WHERE section
	 * @param  bool   $unique   If true returns the first row from the result-set
	 * @return array|object
	 */
	public static function get ( $where = array(), $unique = false ) {
		$data = array();

		$where = static::where ( $where );

		$_data = static::query( "SELECT * FROM `" . static::$_table . "` {$where};" );

		if ( ! empty( $_data ) ) {

			foreach ( $_data as $item ) {
				if ( !empty( $item ) && $item->{static::$_primary} ) {

					$instance = new static;

					foreach ( (array) $item as $column => $value ) {
						$instance->{$column} = is_string( $value ) ? stripslashes( static::nl2eol( $value ) ) : $value;
					}

					$data[$item->{static::$_primary}] = $instance;
				}
			}
		}

		if ( ! empty( $unique ) && ! empty ( $data ) ) {
			return array_pop( $data );
		}

		return $data;
	}

	/**
	 * Prepares the query and binds the given parameter data to the mysqli statement
	 *
	 * @param  string  $query
	 * @param  array   $data
	 * @return mysqli_stmt|bool
	 */
	public static function prepare ( $query = "", $data = array(), $where = array() ) {
		return Data::prepare( $query, $data, $where );
	}

	/**
	 * Executes INSERT query for the current table instance
	 *
	 * @param  array  $data    Array of column => value properties for the new data row
	 * @return object|int 	   Returns false on failure, or the table primary_id value for the inserted row
	 */
	public static function insert ( $data = array () ) {

		if ( ! empty ( $data ) ) {

			$data[ 'created_at' ] = Date::mysqli();

			$columns = implode('`, `', array_keys( $data ) );
			$repeat = str_repeat ( ', ?', count( $data ) );

			$query = "INSERT INTO `" . static::$_table . "` (`" . static::$_primary . "`, `{$columns}`) VALUES (null {$repeat})";

			$statement = static::prepare( $query, $data );

			if ( empty ( $statement ) || ! is_object( $statement ) ) {
				Errors::set ( 'database', 'Insert: ' . Data::errorMessage() , true );
				return false;
			}

			if ( ! $statement->execute() ) {
				Errors::set ( 'database', 'Insert: ' . Data::errorMessage() , true );
				return false;
			}

			return static::get( array ( static::$_primary => Data::insertId() ), true );
		}

		return false;
	}

	/**
	 * Executes UPDATE query for the current table instance
	 *
	 * @param  array  $data    Array of column => value properties for the new data row
	 * @param  array  $where   Array of column => value properties for WHERE statement
	 * @return bool|array 	   Returns false on failure, or the updated row
	 */
	public static function update ( $data = array (), $where = array() ) {

		if ( empty ( $where ) || ! is_array( $where ) ) {
			Errors::set ( 'database', 'Update `'. static::$_table .'`: please limit update queries with WHERE condition', true );
			return false;
		}

		if ( ! empty( $data ) ) {

			foreach ( array( 'created_at', 'updated_at', 'deleted_at' ) as $timestamp ) {
				if ( isset( $data[ $timestamp ] ) ) {
					unset( $data[ $timestamp ] );
				}
			}

			$data[ 'updated_at' ] = Date::mysqli();

			$columns = '`' . implode('` = ?, `', array_keys( $data ) ) . '` = ?';
			$condition = '`' . implode('` = ? AND `', array_keys( $where ) ) . '` = ?';
			$query = "UPDATE  `" . static::$_table . "` SET {$columns} WHERE {$condition};";

			$statement = static::prepare( $query, $data, $where );

			if ( empty ( $statement ) || ! is_object( $statement ) ) {
				Errors::set ( 'database', 'Update: ' . Data::errorMessage() , true );
				return false;
			}

			if ( ! $statement->execute() ) {
				Errors::set ( 'database', 'Update: ' . Data::errorMessage() , true );
				return false;
			}

			return static::get( $where, true );
		}

		return false;
	}

	/**
	 * Converts given array ( column => value ) to string prepared|escaped for the query WHERE section
	 * Adding an "order" array ( 'DESC'||'ASC' => (column1, column2, ...)) will add ORDER BY to the result string
	 * Adding an "group" array ( column1, column2, ...) will add GROUP BY to the result string
	 * Adding an "limit" int will add LIMIT to the result string
	 *
	 * @param  array   $where      Array of column => value properties for the WHERE query section
	 * @param  string  $operator   Operator to be used for where column conditions
	 * @return string
	 */
	public static function where( $where = array (), $operator = ' AND ' ){
		$_where = "";
		$_order = "";
		$_group = "";
		$_limit = "";

		if ( isset( $where[ 'order' ] ) && is_array( $where[ 'order' ] ) && !empty( $where[ 'order' ] ) ) {
			if ( isset( $where[ 'order' ][ 'DESC' ] ) ) {
				$_order = "DESC";
			} else if ( isset( $where[ 'order'][ 'ASC' ] ) ) {
				$_order = "ASC";
			}

			if ( $_order ) {
				if ( ! empty( $where[ 'order' ][ $_order ] ) ) {
					if ( is_array( $where[ 'order' ][ $_order ] ) ) {
						$_order = " ORDER BY `" . implode( "`, `", array_map( 'static::escape', $where[ 'order' ][ $_order ] ) ) . "` {$_order}";
					} else {
						$_order = " ORDER BY " . static::escape( $where[ 'order' ][ $_order ] ) . " {$_order}";
					}

					unset( $where[ 'order' ] );
				}
			}
		}

		if ( isset( $where[ 'group' ] ) && !empty( $where[ 'group' ] ) ) {

			$_group = " GROUP BY `" . implode('`, `', $where[ 'group' ]) . "`";
			unset( $where[ 'group' ] );
		}


		if ( isset( $where[ 'limit' ] ) && !empty( $where[ 'limit' ] ) ) {

			$_limit = (int) $where[ 'limit' ];

			if ( $_limit ) {
				$_limit = " LIMIT {$_limit}";
				unset( $where[ 'limit' ] );
			}
		}


		if ( is_array( $where ) && !empty( $where ) ) {
			$compare = " = ";

			foreach ( $where as $key => $value ) {
				if ( is_string( $value ) && strpos( $value, ':' ) ) {
					$value = explode( ':', $value );
					$compare = $value[0];
					$value = $value[1];
				}

				if ( ! is_array( $value ) ) {
					$key = static::escape( $key );
					$value = static::escape( $value );
					$compare = static::escape( $compare );
					$where[$key] = "`{$key}` {$compare} '{$value}'";
				} else {
					array_walk( $value, 'static::escape' );
					$where[$key] = "`{$key}` in ('". implode("','", $value)."')";
				}
			}

			$operator = ! in_array( $operator, array( 'AND', 'OR' ) ) ? ' AND ' : ' ' . $operator . ' ';

			$_where = implode( $operator, array_values( $where ) );
		}

		if ( ! empty( $_where ) ) {
			$_where = "WHERE {$_where}";
		}

		return $_where . $_group . $_order . $_limit;
	}

	/**
	 * Escapes the given string value
	 *
	 * @param  string   $value
	 * @return string
	 */
	public static function escape ( $value = false ) {
		return Data::escape( $value );
	}

	/**
	 * Escapes the urlencoded string
	 *
	 * @param  string   $value
	 * @return string
	 */
	public static function urlencode ( $value = '' ) {
		if ( ! empty( $value ) ) {
			return static::escape( urldecode( $value ) );
		}

		return "";
	}

	/**
	 * Encodes text values ( json )
	 *
	 * @param  mixed   $value
	 * @return string
	 */
	public static function encode ( $value ) {
		return base64_encode ( json_encode( $value, JSON_UNESCAPED_UNICODE ) );
	}

	/**
	 * Decodes text values ( json )
	 *
	 * @param  string   $value
	 * @return string
	 */
	public static function decode ( $value = '' ) {
		return json_decode( base64_decode( $value ), true );
	}

	/**
	 * Converts new lines (\n) to PHP_EOL values
	 *
	 * @param  string   $value
	 * @return string
	 */
	public static function nl2eol ( $value = '' ) {
		return str_replace( '\n', PHP_EOL, trim ( $value ) );
	}

	/**
	 * Updates the model instance with the new set of data
	 *
	 * @param  array   $data
	 * @return \Ibt\Models\* instance|bool
	 */
	public function save ( array $data = array() ) {
		return static::update( $data, array ( static::$_primary => $this->{static::$_primary} ) );
	}
}