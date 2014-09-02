<?php
App::uses('CsvWithHeaderRowIterator', 'Lib/Iterators');

/**
 * TestCsvWithHeaderRowIterator class. Overrides protected methods for easier testing.
 *
 */
class TestCsvWithHeaderRowIterator extends CsvWithHeaderRowIterator {
	public $_names = null;
	public function detectCsvColumns() {
		parent::detectCsvColumns();
	}
}

/**
 * CsvWithHeaderRowIterator Test Case
 *
 */
class CsvWithHeaderRowIteratorTest extends CakeTestCase {

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->CsvIt = new TestCsvWithHeaderRowIterator($this->getTestFilePath('good1'));
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
		unset($this->CsvIt);
	}

	/**
	 * Returns the full filesystem path for the provided short file name (no
	 * path or extension). Throws a test error if the file is not present.
	 *
	 * @access	public
	 * @param	string $key The short key to look up the test file name.
	 * @return	string		The full filesystem path to the requested test file.
	 */
	public function getTestFilePath($key) {
		$path = APP . "Test/Samples/CsvWithHeaderRowIterator/{$key}.csv";
		if (!is_readable($path)) {
			$this->fail("Requested test file {$path} not found.");
		}
		return $path;
	}


	/**
	 * Confirm that a known-good csv file produces the expected full array.
	 *
	 * @return void
	 */
	public function testConstruct() {
		$expected = array(
			1 => array(
				'one' => 'first',
				'two' => 'second',
				'three' => 'third',
			),
			2 => array(
				'one' => 'uno',
				'two' => 'dos',
				'three' => 'tres',
			),
			3 => array(
				'one' => 'eins',
				'two' => 'zwei',
				'three' => 'drei',
			),
		);
		$this->assertEquals(
			$expected,
			iterator_to_array($this->CsvIt),
			'Converting a known good iterator to an array should produced expected results.'
		);
	}

	/**
	 * Make sure a CSV file with a header row but no data produces an empty array.
	 *
	 * @return void
	 */
	public function testEmptyFile() {
		$this->CsvIt = new TestCsvWithHeaderRowIterator($this->getTestFilePath('empty'));
		$expected = array(
		);
		$this->assertEquals(
			$expected,
			iterator_to_array($this->CsvIt),
			'A CSV file with only a header row should return an empty array.'
		);
	}

	/**
	 * testGetColumnNames method
	 *
	 * @return void
	 */
	public function testGetColumnNames() {
		$expected = array(
			'one',
			'two',
			'three',
		);
		$this->assertEquals(
			$expected,
			$this->CsvIt->getColumnNames(),
			'Fetching the column names from a known good iterator should produce expected field names.'
		);
	}

	/**
	 * testSetColumnNames method
	 *
	 * @return void
	 */
	public function testSetColumnNames() {
		$expected = array(
			'alpha',
			'beta',
			// Not the same number of columns as in the file! But we don't check that.
		);
		$this->CsvIt->setColumnNames($expected);
		$this->assertEquals(
			$expected,
			$this->CsvIt->_names,
			'Iterator\'s names property should match provided array.'
		);
	}

	/**
	 * testAccept method
	 *
	 * @return void
	 */
	public function testAccept() {
		$this->CsvIt = new TestCsvWithHeaderRowIterator($this->getTestFilePath('badrow1'));
		$expected = array(
			1 => array(
				'one' => 'the',
				'two' => 'final',
				'three' => 'row',
			),
			2 => array(
				'one' => 'has',
				'two' => 'one',
				'three' => 'missing',
			),
		);
		$this->assertEquals(
			$expected,
			iterator_to_array($this->CsvIt),
			'A CSV file with a bad row should return an array with everything else.'
		);
	}
}