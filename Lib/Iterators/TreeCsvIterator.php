<?php
/**
 * Convenience iterator that wraps around both SplFileObject and
 * FilterIterator to make looping over records in a CSV file as painless
 * as possible.
 *
 * @usedby        TreeCheckShell
 */

/**
 * Extends SplFileObject to make reading a specific CSV file format
 * less painful. Used in the ProductImportShell.
 *
 * @link: https://github.com/codeinthehole/php-csv-file/blob/master/CSV/Iterator.php
 * @usedby        TreeCheckShell
 */
class TreeCsvIterator extends FilterIterator {

	/**
	 * Store the columns (and their order) obtained from the first row of
	 * the CSV file.
	 *
	 * @var array $_names
	 */
	protected $_names = null;

	/**
	 * Minimum fields required to be present in first row.
	 *
	 * @var array $_requiredFields
	 */
	protected $_requiredFields = array(
		'id',
		'parent_id',
		'name',
	);

	/**
	 * __construct
	 *
	 * Build a new Iterator using a combination of SplFileObject and fgetcsv()
	 * options.
	 *
	 * @access	public
	 * @param string $pathToFile A full or relative path to a CSV-formatted file.
	 * @param string $delimiter The field delimiter to use for the given CSV file. Default: `,`.
	 * @param string $fieldEnclosure The field enclosure character. Default: `"`.
	 * @param string $escapeChar The escape character. Default: `\\`
	 */
	public function __construct($pathToFile, $delimiter = ",", $fieldEnclosure = '"', $escapeChar = "\\") {
		parent::__construct(new SplFileObject($pathToFile, 'r'));
		$file = parent::getInnerIterator();
		$file->setFlags(SplFileObject::READ_CSV);
		$file->setCsvControl($delimiter, $fieldEnclosure, $escapeChar);
		$this->setColumnNames($this->detectCsvColumns());
	}

	/**
	 * Called internally before each call to `::current()`. When
	 * it returns false, the iterator skips to the next record.
	 *
	 * Ensures that only "valid" records are returned by
	 * the iterator. Also handily skips accidentally-empty rows.
	 *
	 * @access	public
	 * @return	boolean					True if record is valid, false otherwise.
	 */
	public function accept() {
		$current = parent::current();
		if ($this->_names) {
			if (count($current) != count($this->_names) || $this->key() === 0) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Return the "current" record in the iterator.
	 *
	 * Attempt to build an associative array out of the values for the current
	 * row. ::accept() ensures that we have the proper number of columns ahead
	 * of time. Also does some post-processing on the fields.
	 *
	 * @access	public
	 * @return	array					Returns the array of fields recognized from the CSV file with keys defined by `::$_names`.
	 */
	public function current() {
		$row = parent::current();
		if ($this->_names) {
			$row = array_combine($this->_names, $row);
		}
		return $row;
	}

	/**
	 * getColumnNames
	 *
	 * Returns the ordered array of column names in use for the given file.
	 * Useful for verifying the columns present in the file against an
	 * outside (expected) list.
	 *
	 * @return	array			The numerically indexed, ordered array of columns in the current CSV file.
	 */
	public function getColumnNames() {
		return $this->_names;
	}

	/**
	 * setColumnNames
	 *
	 * Provides an internal mechanism for assigning an array to ::$_names.
	 *
	 * @access protected
	 * @throws RuntimeException
	 * @param array $names
	 * @return CSV_Iterator
	 */
	protected function setColumnNames(array $names) {
		$missingFields = array_diff($this->_requiredFields, $names);
		if (count($missingFields)) {
			throw new RuntimeException('Minimum required fields [' . implode(', ', $missingFields) . '] missing from header row.');
		}
		$this->_names = $names;
		return $this;
	}

	/**
	 * detectCsvColumns
	 *
	 * Attempts to read the first line from the handle and use the values
	 * as CSV field names. If successful, this is used to initialized the
	 * handle's "naming" feature so array results returned from the Iterator
	 * are keyed by field name instead of numerically.
	 *
	 * @access	protected
	 * @return	array					Returns the array of fields recognized.
	 */
	protected function detectCsvColumns() {
		$this->rewind();
		return parent::current();
	}
}
