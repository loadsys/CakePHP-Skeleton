<?php
/**
 * CsvWithHeaderRowIterator
 */

/**
 * Extends SplFileObject to make reading a CSV file a lot less painful.
 *
 * @link: https://github.com/codeinthehole/php-csv-file/blob/master/CSV/Iterator.php
 *
 */
class CsvWithHeaderRowIterator extends FilterIterator {

	/**
	 * Store the columns (and their order) to _expect_ from the CSV file.
	 *
	 * @var	array
	 */
	protected $_names = null;

	/**
	 * __construct
	 *
	 * Build a new Iterator using a combination of SplFileObject and fgetcsv()
	 * options.
	 *
	 * @access	public
	 * @param	string	$pathToFile		A full or relative path to a CSV-formatted file.
	 * @param	string	$delimiter		Optional field delimiter to use for the given CSV file. Default: `,`.
	 * @param	string	$fieldEnclosure	Optional field enclosure character. Default: `"`.
	 * @param	string	$escapeChar		Optional escape character. Default: `\\`
	 */
	public function __construct($pathToFile, $delimiter = ",", $fieldEnclosure = '"', $escapeChar = "\\") {
		parent::__construct(new SplFileObject($pathToFile, 'r'));
		$file = $this->getInnerIterator();
		$file->setFlags(SplFileObject::READ_CSV);
		$file->setCsvControl($delimiter, $fieldEnclosure, $escapeChar);
		$this->setColumnNames($file->current());
	}

	/**
	 * getColumnNames
	 *
	 * Returns the ordered array of column names in use for the given file.
	 * Useful for verifying the columns present in the file against an
	 * outside (expected) list.
	 *
	 * @return	null|array				The numerically indexed, ordered array of columns taken from the first row of the current CSV file.
	 */
	public function getColumnNames() {
		return $this->_names;
	}

	/**
	 * Provides a way to manually specify the fields expected from the CSV
	 * file. Set to null to turn off header-row processing. Fluent. Returns
	 * self.
	 *
	 * @access	public
	 * @param	null|array	$names
	 * @return	CsvWithHeaderRowIterator
	 */
	public function setColumnNames($names) {
		$this->_names = $names;
		return $this;
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
		$key = $this->getInnerIterator()->key();
		$current = $this->getInnerIterator()->current();
		if (
			is_array($this->_names) && $key === 0 // Skips the first line when column names are set.
			|| count($current) != count($this->_names) // Skip lines with incorrect field count.
		) {
			return false;
		}
		return true;
	}

	/**
	 * Return the "current" record in the iterator.
	 *
	 * Attempt to build an associative array out of the values for the current
	 * row. ::accept() ensures that we have the proper number of columns ahead
	 * of time.
	 *
	 * @access	public
	 * @return	array					Returns the array of fields recognized from the CSV file with keys defined by `::$names`.
	 */
	public function current() {
		return array_combine($this->_names, $this->getInnerIterator()->current());
	}
}