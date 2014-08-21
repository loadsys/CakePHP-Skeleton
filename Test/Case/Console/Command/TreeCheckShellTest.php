<?php
App::uses('ShellDispatcher', 'Console');
App::uses('ShellTestCase', 'Test');
App::uses('TreeCheckShell', 'Console/Command');

/**
 * Class TestTreeCheckShell
 */
class TestTreeCheckShell extends TreeCheckShell {
	public function parseArgs() {
		return parent::parseArgs();
	}
	public function loadRecordsToModel($csvPath) {
		return parent::loadRecordsToModel($csvPath);
	}
	public function repairTree() {
		return parent::repairTree();
	}
	public function printTree() {
		return parent::printTree();
	}
}

/**
 * Class TreeCheckShellTest
 */
class TreeCheckShellTest extends ShellTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
	);

	/**
	 * setUp test case
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->Shell = $this->initSUT();
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Dispatch, $this->Shell);
	}

	/**
	 * testStartup
	 *
	 * @return void
	 */
	public function testStartup() {
		$this->markTestIncomplete('testStartup not implemented. (Might not even be feasible.)');
	}

	/**
	 * testMain
	 *
	 * @return void
	 */
	public function testMain() {
		$this->markTestIncomplete('testMain not implemented.');
	}

	/**
	 * testParseArgs
	 *
	 * @return void
	 */
	public function testParseArgs() {
		$expected = array(
			'some path',
		);
		$this->Shell->args = $expected;
		$this->assertEquals($expected, $this->Shell->parseArgs());
	}

	/**
	 * testLoadRecordsToModel
	 *
	 * @return void
	 */
	public function testLoadRecordsToModel() {
		$this->markTestIncomplete('testLoadRecordsToModel not implemented.');
	}

	/**
	 * testRepairTree
	 *
	 * @return void
	 */
	public function testRepairTree() {
		$this->markTestIncomplete('testRepairTree not implemented.');
	}

	/**
	 * testPrintTree
	 *
	 * @return void
	 */
	public function testPrintTree() {
		$this->markTestIncomplete('testPrintTree not implemented.');
	}

	/**
	 * testPrintLine
	 *
	 * @return void
	 */
	public function testPrintLine() {
		$this->markTestIncomplete('testPrintLine not implemented.');
	}
}
