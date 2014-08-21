<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');
App::uses('Validation', 'Utility');
App::uses('AppEmail', 'Lib/Network/Email');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 */
class AppModel extends Model {

	/**
	 * App-wide Behaviors.
	 *
	 * @var array
	 */
	public $actsAs = array(
		'Containable',
	);

	/**
	 * __construct
	 *
	 * Sets virtualFields and default sort order dynamically to match the
	 * model alias. Model names must be backtick-quoted in $this->order and
	 * $this->VirtualFields entries in order for this automatic replacement
	 * to take effect! For example: $this->order = '`Model`.field ASC'; will
	 * be turned into $this->order = '`Alias`.field ASC'; This helps avoid
	 * the feature accidentally engaging when it shouldn't.
	 *
	 * @codeCoverageIgnore	Child calls all tested independently.
	 * @access	public
	 * @param	mixed	$id		See: http://api.cakephp.org/2.3/source-class-Model.html#641-750
	 * @param	mixed	$table	See: http://api.cakephp.org/2.3/source-class-Model.html#641-750
	 * @param	mixed	$ds		See: http://api.cakephp.org/2.3/source-class-Model.html#641-750
	 * @return	void
	 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->constructVirtualFields();
		$this->constructOrderProperty();
		$this->constructValidate();
	}

	/**
	 * Used by the constructor to make virtualFields dynamically match the
	 * model alias. Model names must be backtick-quoted in $this->VirtualFields
	 * entries in order for this automatic replacement to take effect!
	 *
	 * @access	protected
	 * @return	void
	 */
	protected function constructVirtualFields() {
		foreach ($this->virtualFields as $field => $sql) {
			$this->virtualFields[$field] = str_replace("`{$this->name}`", "`{$this->alias}`", $sql);
		}
	}

	/**
	 * Used by the constructor to make $this->order dynamically match the
	 * model alias. Model names must be backtick-quoted in $this->order
	 * for this automatic replacement to take effect! For example:
	 *
	 *    $this->order = '`Model`.field ASC';
	 *
	 * will be turned into
	 *
	 *    $this->order = '`Alias`.field ASC';
	 *
	 * This helps avoid the feature accidentally engaging when it shouldn't.
	 *
	 * @access	protected
	 * @return	void
	 */
	protected function constructOrderProperty() {
		// Make default sort order(s) more flexible!
		if (is_array($this->order)) {
			foreach ($this->order as $field => $fieldOrDir) {
				if (is_numeric($field)) { // Format is: $this->order = array('Model.field ASC', 'Model.second DESC');
					$this->order[$field] = str_replace("`{$this->name}`", "`{$this->alias}`", $fieldOrDir);
				} else { // Format is: $this->order = array('Model.field' => 'asc', 'Model.second' => 'desc');
					$replacement = str_replace("`{$this->name}`", "`{$this->alias}`", $field);
					if ($replacement !== $field) {
						$this->order[$replacement] = $fieldOrDir;
						unset($this->order[$field]); // Since the key changed, clear the old one.
					}
				}
			}
		} elseif (!is_null($this->order)) { // Format is: $this->order = 'Model.field ASC';
			$this->order = str_replace("`{$this->name}`", "`{$this->alias}`", $this->order);
		}
	}

	/**
	 * Dummy method meant to be overriden in child classes that need to set
	 * dynamic validation rules. Will be called by AppModel's constructor
	 * automatically. Copy this method to the child model and populate the
	 * `$dynamicRules` array the same way you would `::$validate` but with
	 * access to `__()`, `Configure::`, etc.
	 *
	 * Provides a place to inject dynamic validation rules. These will
	 * REPLACE any static rules defined in the `::$validate` property!
	 *
	 * @access	protected
	 * @return	void
	 */
	protected function constructValidate() {
		$dynamicRules = array(
		);
		$this->validate = array_merge($this->validate, $dynamicRules);
	}

	/**
	 * fullTableName
	 *
	 * Provides access to the Model's DataSource's ::fullTableName() method.
	 * Returns the fully quoted and prefixed table name for the current Model.
	 *
	 * @access	public
	 * @param	boolean		$quote		Whether you want the table name quoted.
	 * @param	boolean		$schema		Whether you want the schema name included.
	 * @return	string					Full quoted table name
	 */
	public function fullTableName($quote = true, $schema = true) {
		$datasource = $this->GetDataSource();
		return $datasource->fullTableName($this, $quote, $schema);
	}

	/**
	 * truncate
	 *
	 * Truncates ALL RECORDS from the Model it is called from! VERY DANGEROUS!
	 * Depends on the ::fullTableName() method to concatenate the configured
	 * table prefix and table name together.
	 *
	 * @access	public
	 * @return	mixed
	 */
	public function truncate() {
		$fullName = $this->fullTableName();
		$q = 'TRUNCATE TABLE %s;';
		return $this->query(sprintf($q, $fullName));
	}

	/**
	 * Template method to allow for dynamic values for ENUM-like fields.
	 * Each key is a Model field name, and each value is an array of
	 * [dbval => Display Value] pairs. Models should copy and paste this
	 * method and populate their own $properties array using the example
	 * below.
	 *
	 * @access	public
	 * @param	string|null	$key	The field name to look up, or null to return all fields together.
	 * @return	mixed			The result of AppModel::enumList($properties, $keys).
	 */
	public function getList($key = null) {
		$properties = array(
			// 'field_name' => array(
			// 	'dbval1' => __('Display Val 1'),
			// 	'dbval2' => __('Display Val 2'),
			// ),
			// 'different_field' => array(
			// 	'none' => __('None'),
			// 	'option1' => __('Important Option'),
			// ),
		);
		return $this->enumList($properties, $key);
	}

	/**
	 * A consolidation method designed to be used by subclasses in their
	 * `getList()` override methods. This method unifies the logic of determining
	 * which key to return, if any. This makes the logic testable and
	 * simplifies the individual getList() methods.
	 *
	 * @access	public
	 * @param	array	$enums	A nested array of [field_name => [db_value => Display Value]] sets.
	 * @param	string|null	$key	The field name to look up, or null to return all fields together.
	 * @return	mixed			If $key is not null, and exists as a key in $enums, that sub-array is returned.
	 *							If no matching key is found, false is returned.
	 *							If $key is null, all of $enum is returns.
	 */
	public function enumList($enums, $key) {
		if (is_null($key)) {
			return $enums;
		}
		if (!isset($enums[$key])) {
			return false;
		}
		return $enums[$key];
	}

	/**
	 * Extract TranslateBehavior field names from `::$actsAs` array.
	 *
	 *
	 * @access	public
	 * @return	array
	 */
	public function getTranslatedFields() {
		if (!isset($this->actsAs['Translate']) || !is_array($this->actsAs['Translate'])) {
			return array();
		}
		$fields = $this->actsAs['Translate'];
		$reduceToFieldName = function (&$v, $k) {
			$v = (is_string($k) ? $k : $v);
		};
		array_walk($fields, $reduceToFieldName);
		return array_values($fields);
	}

	/**
	 * validateUnique
	 *
	 * Verifies that there are no other records in the DB with a matching
	 * value for the field specified in $check. Adds the `NOT [id => x]` element
	 * into $check if present in $this->data to exclude the existing record
	 * from the lookup. (Essentially will only return true if any *OTHER*
	 * records besides this one have a field value matching what's in $check.)
	 *
	 * @access public
	 * @param	array	$check	An array with the field and its value to be validated. Example: array('email' => 'actual@email.submitted.com').
	 * @param	boolean	$excludeExisting	If true, and if $this->data[Model][id] is present, will exclude that existing record from the check. When this is false, attempting to update an existing record will cause this method to fail (because the existing copy of the record with the matching field IS in the database.)
	 * @return	boolean			Returns true if there are no existing records with a matching $check field.
	 */
	public function validateUnique($check, $excludeExisting = false) {
		if (!empty($this->data[$this->alias][$this->primaryKey]) && $excludeExisting) {
			$check[] = array(
				'NOT' => array(
					$this->alias . '.' . $this->primaryKey => $this->data[$this->alias][$this->primaryKey]
				)
			);
		}

		$options = array(
			'conditions' => $check,
			'recursive' => -1,
		);
		return ($this->find('count', $options) === 0);
	}

	/**
	 * validateNull
	 *
	 * Verifies the provided value is explicitly PHP's `null` value. Fails
	 * in all other cases.
	 *
	 * @access public
	 * @param	array	$check	An array with the field and its value to be validated. Example: array('email' => null).
	 * @return	boolean			Returns true if the $check value is `null`.
	 */
	public function validateNull($check) {
		list($field, $value) = each($check);
		return is_null($value);
	}

	/**
	 * Instantiates and returns an instance of the application's email
	 * handler class, AppEmail.
	 *
	 * @access	public
	 * @param	string	$config	The name of the CakeEmail config class to use.
	 * @return	AppEmail		Instance of the subclassed CakeEmail class.
	 */
	public function emailFactory($config = null) {
		return new AppEmail($config);
	}
}
