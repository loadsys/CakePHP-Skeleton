<?php
/**
 * Tests for the AppController.
 */
namespace App\Test\TestCase\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity as User;
use Cake\TestSuite\IntegrationTestCase;

/**
 * \App\Test\TestCase\Controller\TestAppController
 */
class TestAppController extends AppController {
	public function ssl() {
		return parent::ssl();
	}

	public function authError() {
		return parent::authError();
	}

	public function forceSsl() {
		return parent::forceSsl();
	}
}

/**
 * \App\Test\TestCase\Controller\AppControllerTest
 */
class AppControllerTest extends IntegrationTestCase {
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [];

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::tearDown();

		$this->user = new User([
			'id' => 1,
			'role' => 'admin',
			'email' => 'admin@localhost',
		]);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		Configure::write('Defaults.ssl_force', false);
		unset($this->user);

		parent::tearDown();
	}

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
			$componentRegistry->has('RequestHandler'),
			'AppController must load the RequestHandler component.'
		);
		$this->assertTrue(
			$componentRegistry->has('Flash'),
			'AppController must load the Flash component.'
		);
		$this->assertTrue(
			$componentRegistry->has('Auth'),
			'AppController must load the Auth component.'
		);
		$this->assertTrue(
			$componentRegistry->has('Security'),
			'AppController must load the Security component.'
		);
		$this->assertTrue(
			$componentRegistry->has('Csrf'),
			'AppController must load the Csrf component.'
		);
	}

	/**
	 * Test isAuthorized method
	 *
	 * @param bool $expected Whether the isAuthorized() method is expected to pass or fail.
	 * @param string $msg Optional PHPUnit assertion failure message.
	 * @return void
	 * @covers \App\Controller\AppController::isAuthorized
	 * @dataProvider provideIsAuthorizedArgs
	 */
	public function testIsAuthorized($expected, $msg = '') {
		$request = $this->getMock('Cake\Network\Request');
		$controller = new AppController($request);

		$this->assertEquals(
			$expected,
			$controller->isAuthorized([]),
			$msg
		);
	}

	/**
	 * Provide arguments for the isAuthorized method
	 *
	 * @return array
	 */
	public function provideIsAuthorizedArgs() {
		return [
			[false, 'All authorization is denied by default.'],
		];
	}

	/**
	 * Test beforeFilter() method.
	 *
	 * @return void
	 * @covers \App\Controller\AppController::beforeFilter
	 * @covers \App\Controller\AppController::auth
	 */
	public function testBeforeFilter() {
		$controller = $this->getMock(
			'App\Controller\AppController',
			['viewBuilder', 'layout'],
			[$this->getMock('Cake\Network\Request')]
		);
		//$controller->expects($this->once())
		//	->method('viewBuilder')
		//	->will($this->returnSelf());
		//$controller->expects($this->once())
		//	->method('layout')
		//	->with('admin');

		$controller->beforeFilter(new Event([]));
	}

	/**
	 * Test blackHole method with the various types of errors that can be passed,
	 * when debug is equal to true.
	 *
	 * @param string $errorType The string for the type of error to handle.
	 * @return void
	 * @covers \App\Controller\AppController::blackHole
	 * @dataProvider providerBlackHole
	 */
	public function testBlackHoleDebugTrue($errorType, $exception) {
		Configure::write('debug', true);
		$exceptionMessage = 'Sample Message';
		$exceptionClass = new $exception($exceptionMessage);

		$requestUrl = 'http://localhost.com/pages/view/12345';
		$request = $this->getMock(
			'Cake\Network\Request',
			['here'],
			[$requestUrl]
		);
		$request->expects($this->once())
			->method('here')
			->with()
			->will($this->returnValue($requestUrl));

		$controller = $this->getMock(
			'\App\Test\TestCase\Controller\TestAppController',
			['log', 'authError', 'forceSsl'],
			[$request]
		);
		$controller->expects($this->once())
			->method('log')
			->with(
				"Security Component black-holed this request: Request URL: {$requestUrl} Exception Type: {$errorType} Exception Message: {$exceptionMessage} Exception Reason: ",
				'error',
				['scope' => ['security']]
			)
			->will($this->returnValue(true));

		$controller->expects($this->never())
				->method('authError');
		$controller->expects($this->never())
				->method('forceSsl');

		$this->setExpectedException(
			$exception,
			$exceptionMessage
		);

		$controller->blackHole($errorType, $exceptionClass);
	}

	/**
	 * Test blackHole method with the various types of errors that can be passed,
	 * when debug is equal to false.
	 *
	 * @param string $errorType The string for the type of error to handle.
	 * @return void
	 * @covers \App\Controller\AppController::blackHole
	 * @dataProvider providerBlackHole
	 */
	public function testBlackHoleDebugFalse($errorType, $exception) {
		Configure::write('debug', false);
		$exceptionMessage = 'Sample Message';
		$exceptionClass = new $exception($exceptionMessage);

		$requestUrl = 'http://localhost.com/pages/view/12345';
		$request = $this->getMock(
			'Cake\Network\Request',
			['here'],
			[$requestUrl]
		);
		$request->expects($this->once())
			->method('here')
			->with()
			->will($this->returnValue($requestUrl));

		$controller = $this->getMock(
			'\App\Test\TestCase\Controller\TestAppController',
			['log', 'authError', 'forceSsl'],
			[$request]
		);
		$controller->expects($this->once())
			->method('log')
			->with(
				"Security Component black-holed this request: Request URL: {$requestUrl} Exception Type: {$errorType} Exception Message: {$exceptionMessage} Exception Reason: ",
				'error',
				['scope' => ['security']]
			)
			->will($this->returnValue(true));

		if ($errorType === 'secure') {
			$controller->expects($this->never())
				->method('authError');
			$controller->expects($this->once())
				->method('forceSsl')
				->with()
				->will($this->returnValue(true));
		} else {
			$controller->expects($this->once())
				->method('authError')
				->with()
				->will($this->returnValue(true));
			$controller->expects($this->never())
				->method('forceSsl');
		}

		$controller->blackHole($errorType, $exceptionClass);
	}

	/**
	 * Test blackHole method with secure passed.
	 *
	 * @return array Data inputs for testBlackHole
	 */
	public function providerBlackHole() {
		return [
			[
				'auth',
				'\Cake\Controller\Exception\AuthSecurityException',
			],
			[
				'secure',
				'\Cake\Controller\Exception\SecurityException',
			],
		];
	}

	/**
	 * Test beforeRender() method.
	 *
	 * @return void
	 * @covers \App\Controller\AppController::beforeRender
	 */
	public function testBeforeRender() {

		// Set up auth data, AuthComponent and Controller.
		$controller = $this->getMock(
			'App\Controller\AppController',
			['set'],
			[$this->getMock('Cake\Network\Request')]
		);
		$controller->expects($this->once())
			->method('set')
			->with(['u' => $this->user])
			->willreturn('canary');
		$controller->Auth = $this->getMock('AuthComponent', ['userEntity']);
		$controller->Auth->expects($this->once())
			->method('userEntity')
			->with()
			->willReturn($this->user);

		$controller->beforeRender(new Event([]));
	}

	/**
	 * Test ssl enforcement, when the Defaults.ssl_force is set to true.
	 *
	 * @return void
	 * @covers \App\Controller\AppController::ssl
	 */
	public function testSslEnforcementTrue() {
		Configure::write('Defaults.ssl_force', true);
		$request = $this->getMock('Cake\Network\Request');

		$securityComponent = $this->getMock(
			'SecurityComponent',
			['requireSecure']
		);
		$securityComponent->expects($this->once())
			->method('requireSecure')
			->will($this->returnValue(true));

		$controller = $this->getMock(
			'\App\Test\TestCase\Controller\TestAppController',
			['redirect'],
			[$request]
		);
		$controller->Security = $securityComponent;

		$controller->ssl();
	}

	/**
	 * Test ssl enforcement, when the Defaults.ssl_force is set to false.
	 *
	 * @return void
	 * @covers \App\Controller\AppController::ssl
	 */
	public function testSslEnforcementFalse() {
		Configure::write('Defaults.ssl_force', false);
		$request = $this->getMock('Cake\Network\Request');

		$securityComponent = $this->getMock(
			'SecurityComponent',
			['requireSecure']
		);
		$securityComponent->expects($this->never())
			->method('requireSecure')
			->will($this->returnValue(true));

		$controller = $this->getMock(
			'\App\Test\TestCase\Controller\TestAppController',
			['redirect'],
			[$request]
		);
		$controller->Security = $securityComponent;

		$controller->ssl();
	}

	/**
	 * Test ssl redirects
	 *
	 * @return void
	 * @covers \App\Controller\AppController::forceSsl
	 */
	public function testForceSslEnforcement() {
		$request = $this->getMock(
			'Cake\Network\Request',
			[],
			['http://localhost.com']
		);

		$controller = $this->getMock(
			'\App\Test\TestCase\Controller\TestAppController',
			['redirect'],
			[$request]
		);
		$controller->expects($this->once())
			->method('redirect')
			->will($this->returnValue(true));

		$controller->forceSsl();
	}

	/**
	 * Test authError should just throw an exception.
	 *
	 * @return void
	 * @covers \App\Controller\AppController::authError
	 */
	public function testAuthError() {
		$request = $this->getMock(
			'Cake\Network\Request',
			[],
			['http://localhost.com']
		);

		$controller = $this->getMock(
			'\App\Test\TestCase\Controller\TestAppController',
			['redirect'],
			[$request]
		);

		$this->setExpectedException(
			'\Cake\Network\Exception\BadRequestException',
			'There was an error with your request. Please, try again.'
		);
		$controller->authError();
	}
}
