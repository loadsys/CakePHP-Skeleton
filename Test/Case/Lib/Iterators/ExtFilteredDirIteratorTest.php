<?php
/**
 * ExtFilteredDirIterator test case
 *
 * PHP 5
 *
 * Copyright (c) Loadsys Web Strategies (http://loadsys.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.md
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Loadsys Web Strategies (http://loadsys.com)
 * @link          http://loadsys.com Loadsys Web Strategies
 * @since         CakePHP(tm) v 2.3
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('ExtFilteredDirIterator', 'Lib/Iterators');

/**
 * Class DirToBasenameIterator
 *
 * Accepts a DirectoryIterator as an argument and extends it so that
 * ::current() returns just the string basename of each record. Makes
 * comparing results to an array easier. Also silently ignores OS
 * parasite files like .DS_Store.
 *
 * @package       Seeds.Test.Case.Lib
 */
class DirToBasenameIterator extends FilterIterator {
	private $parasites = array(
		'.DS_Store',
		'thumb.db'
	);

	public function __construct(Traversable $it) {
		parent::__construct($it);
	}

	public function accept() {
		$current = parent::current();
		return !in_array($current, $this->parasites);
	}

	public function current() {
		$current = parent::current();
		return $this->getBasename();
	}
}

/**
 * Class ExtFilteredDirIteratorTest
 *
 */
class ExtFilteredDirIteratorTest extends CakeTestCase {

	/**
	 * setUp test case
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->testDirRoot = APP . "Test/Samples/DirIterator/";
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
		unset($this->DirIterator);
	}

	/**
	 * Returns the full filesystem path for the provided short path name (no
	 * path or extension). Throws a test error if the file is not present.
	 *
	 * @access	public
	 * @param	string $key The short key to look up the test path name.
	 * @return	string		The full filesystem path to the requested test path.
	 */
	public function getTestPath($key) {
		$path = APP . "Test/Samples/DirIterator/{$key}";
		if (!is_readable($path)) {
			$this->fail("Requested test path {$path} not found.");
		}
		return $path;
	}

	/**
	 * testDirIteratorConstructBadDir
	 *
	 * Attempting to create a DirIterator with an invalid directory path
	 * should throw an exception.
	 *
	 * @return void
	 */
	public function testDirIteratorConstructBadDir() {
		$badPath = './doesNotExist';
		$this->setExpectedException(
			'Exception', 
			'failed to open dir: No such file or directory'
		);
		$DirIterator = new ExtFilteredDirIterator($badPath);
	}

	/**
	 * testDirIteratorNoExtArray
	 *
	 * Creating a DirIterator with no file externsion list should return ALL
	 * contents of a directory.
	 *
	 * @return void
	 */
	public function testDirIteratorNoExtArray() {
		$expected = array(
			'dir1',
			'dir2',
		);
		$path = $this->testDirRoot;

		$DirIterator = iterator_to_array(new DirToBasenameIterator(new ExtFilteredDirIterator($this->getTestPath(''))));
		$this->AssertEmpty(array_diff($expected, $DirIterator));
		$this->AssertEmpty(array_diff($DirIterator, $expected));
	}

	/**
	 * testDirIteratorUsingExtNotPresentInDir
	 *
	 * Creating a DirIterator with an extension list that exclude all files
	 * in a target dir and without subdirs should return an empty array.
	 *
	 * @return void
	 */
	public function testDirIteratorUsingExtNotPresentInDir() {
		$extList = array();
		$DirIterator = iterator_to_array(new DirToBasenameIterator(new ExtFilteredDirIterator($this->getTestPath('dir2'), $extList)));
		$this->AssertEmpty($DirIterator);
	}

	/**
	 * testDirIteratorWithList
	 *
	 * Creating a DirIterator with an extension list should return filtered
	 * contents of a directory.
	 *
	 * @return void
	 */
	public function testDirIteratorWithList() {
		$expected = array(
			'640x360.gif',
			'subdir3'
		);
		$extList = array('gif');
		$DirIterator = iterator_to_array(new DirToBasenameIterator(new ExtFilteredDirIterator($this->getTestPath('dir1'), $extList)));
		$this->AssertEmpty(array_diff($expected, $DirIterator));
		$this->AssertEmpty(array_diff($DirIterator, $expected));
	}

}