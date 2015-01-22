<?php
App::uses('AppController', 'Controller');
App::uses('AppSession', 'Controller/Component');

/**
 * TestAppController to access protected helper methods for direct testing.
 */
class TestAppController extends AppController {
}

/**
 * AppController Test Case
 *
 */
class AppControllerTest extends ControllerTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
	);

	/**
	 * Confirm the admin layout is set when the proper URL param is present.
	 *
	 * @return void
	 */
	public function testBeforeFilter() {
		$App = $this->getMock('TestAppController', array('__auth'), array(), 'TestApp', true);
		$App->request = new stdclass();
		$App->request->params = array('admin' => true);
		$App->expects($this->once())
			->method('__auth');

		$App->beforeFilter();
		$this->assertEquals('admin', $App->layout);
	}

	/**
	 * Confirm that necessary universal view vars are present.
	 *
	 * @return void
	 */
	public function testBeforeRender() {
		$App = $this->generate('TestApp', array(
			'components' => array('Auth'),
		));
		$App->Auth->staticExpects($this->once())
			->method('user')
			->will($this->returnValue('canary'));

		$App->beforeRender();

		$this->assertEquals('canary', $App->viewVars['u']);
	}

	/**
	 * Currently kind of pointless, but ensure that AppController allows
	 * unrestricted access to 'display' actions by default.
	 *
	 * @return void
	 */
	public function testAuth() {
		$allowed = array('display');

		$App = $this->generate('TestApp', array(
			'components' => array('Auth'),
		));
		$App->Auth->expects($this->once())
			->method('allow')
			->with($allowed)
			->will($this->returnValue(true));

		$App->__auth();
	}

	/**
	 * Provide pairs of [Routing.prefixes, URL params, User.role, expected boolean result,
	 * PHPUnit assertion message] to testIsAuthorized().
	 *
	 * @return void
	 */
	public function provideIsAuthorizedArgs() {
		return array(
			array(
				array('user', 'admin'),
				array(),
				'user',
				true,
				'When no URL prefixes present, always allow access.',
			),
			array(
				array('user', 'admin'),
				array('admin' => true),
				'user',
				false,
				'When URL prefix present, deny if user role does not match.',
			),
			array(
				array('user', 'admin'),
				array('admin' => true),
				'admin',
				true,
				'When URL prefix present, allow if user role matched.',
			),
		);
	}

	/**
	 * Confirm that routes withvarious prefixes are allowed or blocked
	 * appropriately.
	 *
	 * @dataProvider provideIsAuthorizedArgs
	 * @param	array	$prefixes	Array of routing prefixes to write into Configure before the test.
	 * @param	array	$params		Array of URL params to configure the CakeRequest with prior to the test.
	 * @param	string	$role		The currently-logged-in user's `role` value.
	 * @param	bool	$expected	Whether the test should return true or false.
	 * @param	string	$msg		The message to echo if the assertion fails.
	 * @return	void
	 */
	public function testIsAuthorized($prefixes, $params, $role, $expected, $msg = '') {
		$prefixBackup = Configure::read('Routing.prefixes'); // Backup config'd prefixes.

		Configure::write('Routing.prefixes', $prefixes);
		$App = $this->generate('TestApp', array(
			'components' => array('Auth'),
		));
		$App->request->params = $params;
		$App->Auth->staticExpects($this->any())
			->method('user')
			->with('role')
			->will($this->returnValue($role));

		$this->assertEquals($expected, $App->isAuthorized(), $msg);

		Configure::write('Routing.prefixes', $prefixBackup); // Restore config'd prefixes.
	}

	/**
	 * test emailFactory without passing a config
	 *
	 * @return void
	 */
	public function testEmailFactoryNoConfigPassed() {
		$App = $this->generate('TestApp');
		$this->assertInstanceOf("AppEmail", $App->emailFactory());
	}

	/**
	 * test emailFactory with passing a config
	 *
	 * @return void
	 */
	public function testEmailFactoryConfigPassed() {
		Configure::write('Email.Transports.default.from', 'phpunit@loadsys.com');
		$App = $this->generate('TestApp');
		$this->assertInstanceOf("AppEmail", $App->emailFactory('default'));
	}
}
