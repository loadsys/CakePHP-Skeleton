<?php
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('AppHelper', 'View/Helper');

/**
 * AppHelper Test Case
 *
 */
class AppHelperTest extends CakeTestCase {

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->AppHelper = new AppHelper($View);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->AppHelper);
		parent::tearDown();
	}

	/**
	 * testIncomplete
	 *
	 * @return void
	 */
	public function testIncomplete() {
		$this->markTestSkipped('AppHelper has no methods to test.');
	}
}
