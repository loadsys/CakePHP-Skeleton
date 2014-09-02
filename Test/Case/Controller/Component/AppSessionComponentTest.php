<?php
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('AppSessionComponent', 'Controller/Component');

/**
 * A fake controller to test against
 *
 */
class TestController extends Controller {
}

/**
 * AppSessionComponent Test Case
 *
 */
class AppSessionComponentTest extends CakeTestCase {

	protected static $_sessionBackup;

	/**
	 * Stores the component under test.
	 *
	 * @var Component
	 */
	public $Component = null;

	/**
	 * Stores the fake controller attached to the component being tested.
	 *
	 * @var Controller
	 */
	public $Controller = null;

	/**
	 * Fixtures to load.
	 *
	 * @var array
	 */
	public $fixtures = array(
	);

	/**
	 * test case startup
	 *
	 * @return void
	 */
	public static function setupBeforeClass() {
		self::$_sessionBackup = Configure::read('Session');
		Configure::write('Session', array(
			'defaults' => 'php',
			'timeout' => 100,
			'cookie' => 'test'
		));
	}

	/**
	 * cleanup after test case.
	 *
	 * @return void
	 */
	public static function teardownAfterClass() {
		Configure::write('Session', self::$_sessionBackup);
	}

	/**
	 * setUp the needed stuff for our tests
	 *
	 */
	public function setUp() {
		parent::setUp();

		$_SESSION = null;

		// Setup our component and fake test controller
		$this->Component = new AppSessionComponent(new ComponentCollection());
		$this->Controller = new TestController(new CakeRequest(), new CakeResponse());
		$this->Component->startup($this->Controller);
	}

	/**
	 * Destroy any artifacts created for the test.
	 *
	 */
	public function tearDown() {
		CakeSession::destroy();
		
		unset($this->Component);
		unset($this->Controller);

		parent::tearDown();
	}

	/**
	 * Provide sets of [flash message, element name, view vars, session key,
	 * expected string, phpunit assertion message] to testSetFlash().
	 *
	 * @return void
	 */
	public function provideSetFlashArgs() {
		return array(
			array(
				'Hello world.',
				null,
				null,
				null,
				'Message.flash',
				array(
					'message' => 'Hello world.',
					'element' => 'Layouts/flash_bootstrap',
					'params' => array(
						'class' => 'alert-info',
					),
				),
				'Providing only a message should activate bootstrap default values.',
			),

			array(
				'Hello world.',
				'Layouts/override_element',
				'danger',
				'keymaster',
				'Message.keymaster',
				array(
					'message' => 'Hello world.',
					'element' => 'Layouts/override_element',
					'params' => array(
						'class' => 'alert-danger',
					),
				),
				'Overriding values explicitly should retain those options.',
			),
		);
	}

	/**
	 * Make sure the setFlash override produces expected results for a
	 * variety of inputs.
	 *
	 * @dataProvider provideSetFlashArgs
	 * @param	string	$message	The flash message to display.
	 * @param	string	$element	The name of the element to render.
	 * @param	array	$params		View vars to send to the element.
	 * @param	string	$key		Session key location in which to save the message.
	 * @param	string	$expectedKey	The expected session key where the data was saved.
	 * @param	array	$expectedValue	The expected session array value.
	 * @param	string	$msg		The PHPUnit assertion message to print if the test fails.
	 * @return	void
	 */
	public function testSetFlash($message, $element, $params, $key, $expectedKey, $expectedValue, $msg = '') {
		if (!CakeSession::start()) {
			$this->markTestSkipped('Session not available. Can not test setFlash(). Use PHPUnit --stderr command line option.');
		}
		$this->Component->setFlash($message, $element, $params, $key);

		$component = new AppSessionComponent(new ComponentCollection());
		$component->setFlash($message, $element, $params, $key);

		$this->assertEquals(
			$expectedValue,
			$component->read($expectedKey),
			$msg
		);
	}
}
