<?php
App::uses('TreeCsvIterator', 'Lib/Iterators');

/**
 * TestTreeCsvIterator class. Overrides protected methods for easier testing.
 *
 */
class TestTreeCsvIterator extends TreeCsvIterator {
	public function setColumnNames(array $names) {
		return parent::setColumnNames($names);
	}
	public function detectCsvColumns() {
		return parent::detectCsvColumns();
	}
}

/**
 * TreeCsvIterator Test Case
 *
 */
class TreeCsvIteratorTest extends CakeTestCase {

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
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
		$path = APP . "Test/Samples/TreeCsvIterator/{$key}.csv";
		if (!is_readable($path)) {
			$this->fail("Requested test file {$path} not found.");
		}
		return $path;
	}


	/**
	 * Confirm that a CSV without minimum required header columns throws an exception.
	 *
	 * @return void
	 */
	public function testNoHeaderColumns() {
		$this->expectException('RuntimeException', 1, 'Minimum required fields [id, parent_id, name] missing from header row.');
		$result = iterator_to_array(new TestTreeCsvIterator($this->getTestFilePath('no_headers')));
	}

	/**
	 * Confirm that a CSV bad columns throws an exception.
	 *
	 * @return void
	 */
	public function testBadColumns() {
		$this->expectException('RuntimeException', 1, 'Minimum required fields [parent_id] missing from header row.');
		$result = iterator_to_array(new TestTreeCsvIterator($this->getTestFilePath('bad_columns')));
	}

	/**
	 * Confirm that a valid CSV produces the expected array.
	 *
	 * @return void
	 */
	public function testValidCsv() {
		$expected = array(
			1 => array(
				'id' => '1',
				'parent_id' => 'NULL',
				'name' => 'Valid First Row',
				'extra' => 'extra is allowed',
			),
			2 => array(
				'id' => '2',
				'parent_id' => '1',
				'name' => 'Valid Child Row',
				'extra' => 'this is okay',
			),
		);
		$result = iterator_to_array(new TestTreeCsvIterator($this->getTestFilePath('valid')));
		$this->assertEquals(
			$expected,
			$result,
			'A valid CSV file should produce the expected array.'
		);
	}

	/**
	 * Confirm that a valid CSV returns sane column names.
	 *
	 * @return void
	 */
	public function testGetColumnNames() {
		$expected = array(
			'id',
			'parent_id',
			'name',
			'extra',
		);
		$iterator = new TestTreeCsvIterator($this->getTestFilePath('valid'));
		$this->assertEquals(
			$expected,
			$iterator->getColumnNames(),
			'A valid CSV file should return the correct header fields.'
		);
	}
}