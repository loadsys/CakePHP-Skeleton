<?php
App::uses('ShellDispatcher', 'Console');
App::uses('ShellTestCase', 'Test');
App::uses('PasswordGenShell', 'Console/Command');

/**
 * Class TestPasswordGenShell
 */
class TestPasswordGenShell extends PasswordGenShell {
	public function isActualClassMethod($method, $obj) {
		return parent::isActualClassMethod($method, $obj);
	}
}

/**
 * Class PasswordGenShellTest
 */
class PasswordGenShellTest extends ShellTestCase {

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
	 * testMain
	 *
	 * @return void
	 */
	public function testMain() {
		$this->markTestIncomplete('@TODO: testMain not implemented.');
	}

	/**
	 * testHashPassword
	 *
	 * @return void
	 */
	public function testHashPassword() {
		$this->markTestIncomplete('@TODO: testHashPassword not implemented.');
	}

	/**
	 * testGeneratePassword
	 *
	 * @return void
	 */
	public function testGeneratePassword() {
		$this->markTestIncomplete('@TODO: testGeneratePassword not implemented.');
	}

	/**
	 * testIsActualClassMethod
	 *
	 * @return void
	 */
	public function testIsActualClassMethod() {
		$this->markTestIncomplete('@TODO: testIsActualClassMethod not implemented.');
	}

	/**
	 * testGetOptionParser
	 *
	 * @return void
	 */
	public function testGetOptionParser() {
		$this->markTestIncomplete('@TODO: testGetOptionParser not implemented.');
	}
}
