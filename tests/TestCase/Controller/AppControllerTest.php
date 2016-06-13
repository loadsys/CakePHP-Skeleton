<?php
/**
 * Tests for the AppController.
 */
namespace App\Test\TestCase\Controller;

use App\Controller\AppController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Test\TestCase\Controller\AppControllerTest
 */
class AppControllerTest extends IntegrationTestCase {
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [];

	/**
	 * Test initialize() method.
	 *
	 * @return void
	 * @covers \App\Controller\AppController::initialize
	 */
	public function testInitialize() {
		$this->session([]); // Empty the fake session.
		// What we get back doesn't matter, we just need to be able
		// to inspect the controller instance.
		$this->get('/');
		$componentRegistry = $this->_controller->components();
		$this->assertTrue(
			$componentRegistry->has('Flash'),
			'AppController must load the Flash component.'
		);
		//$this->assertTrue(
		//	$componentRegistry->has('Auth'),
		//	'AppController must load the Auth component.'
		//);
	}

	/**
	 * Test isAuthorized() method.
	 *
	 * @return void
	 * @covers \App\Controller\AppController::isAuthorized
	 */
	public function testIsAuthorized() {
		$this->markTestSkipped('No method to test.');
	}

	/**
	 * Test beforeFilter() method.
	 *
	 * @return void
	 * @covers \App\Controller\AppController::beforeFilter
	 * @covers \App\Controller\AppController::auth
	 */
	public function testBeforeFilter() {
		$this->markTestSkipped('No method to test.');
	}

	/**
	 * Test beforeRender() method.
	 *
	 * @return void
	 * @covers \App\Controller\AppController::beforeRender
	 */
	public function testBeforeRender() {
		$this->markTestSkipped('No method to test.');
	}
}
