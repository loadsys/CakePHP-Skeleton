<?php
/**
 * A JsonType that when defining a field as that particular type allows for
 * data to automatically be json_encode and decoded when converted.
 *
 * Usage example is as follows for a Table class that has a column `packed`
 * to use this JsonType:
 *
 *     protected function _initializeSchema(Schema $table) {
 *         $table->columnType('packed', 'json');
 *         return $table;
 *     }
 */
namespace App\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;
use PDO;

/**
 * \App\Database\Type\JsonType
 */
class JsonType extends Type {
	/**
	 * Decodes Json data if present
	 *
	 * @param mixed $value data to be decoded
	 * @param Driver $driver PDO driver trait
	 * @return mixed|null Assume the value is a json-encoded string and unpack it.
	 */
	public function toPHP($value, Driver $driver) {
		if ($value === null) {
			return null;
		}

		return json_decode($value, true);
	}

	/**
	 * Assembles data for use in Entity
	 *
	 * @param mixed $value data to be decoded
	 * @return mixed If the value is already a PHP array (or null) return it,
	 *    otherwise assume its a json-encoded string and unpack it.
	 */
	public function marshal($value) {
		if (is_array($value) || $value === null) {
			return $value;
		}

		return json_decode($value, true);
	}

	/**
	 * Json encodes data to store as a scalar in the database
	 *
	 * @param mixed $value data to be encoded
	 * @param Driver $driver PDO driver trait
	 * @return string The json-encoded version of the provided PHP array.
	 */
	public function toDatabase($value, Driver $driver) {
		return json_encode($value);
	}

	/**
	 * Prepares data for SQL handling
	 *
	 * @param string $value encoded data for storing as a string
	 * @param Driver $driver PDO driver trait
	 * @return int A \PDO constant indicating the parameter's type.
	 */
	public function toStatement($value, Driver $driver) {
		if ($value === null) {
			return PDO::PARAM_NULL;
		}

		return PDO::PARAM_STR;
	}
}
