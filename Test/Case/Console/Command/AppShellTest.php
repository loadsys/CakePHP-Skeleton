<?php

App::uses('ShellDispatcher', 'Console');
App::uses('ShellTestCase', 'Test');
App::uses('AppShell', 'Console/Command');


/**
 * Class TestAppShell
 *
 */
class TestAppShell extends AppShell {
	public function _out($msg, $style = 'error', $level = Shell::NORMAL) {
		return parent::_out($msg, $style, $level);
	}
}

/**
 * Class AppShellTest
 *
 */
class AppShellTest extends ShellTestCase {

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
		unset($this->Shell);
	}


	/**
	 * Returns arrays of [outputMessage, outputType, outputVerosity,
	 * expectedString, phpunitMsg] for testing AppShell::_out().
	 *
	 * @return	array
	 */
	public function provideOutArgs() {
		return array(
			'empty-string' => array(
				'',
				'',
				Shell::NORMAL,
				'',
				'Empty string on normal verbosity with no format should echo nothing.',
			),
			'no-format' => array(
				'hello world',
				'',
				Shell::NORMAL,
				'hello world',
				'Input string on normal verbosity with no format should be echoed verbatim.',
			),
			'error-format' => array(
				'hello world',
				'error',
				Shell::NORMAL,
				'<error>hello world</error>',
				'Input string on normal verbosity with an error format should be echoed with surrounding tags.',
			),
		);
	}

	/**
	 * Test our custom output method with various inputs combinations.
	 *
	 * @dataProvider	provideOutArgs
	 * @param	string $message	The text message to echo.
	 * @param	string $type	The Shell display type (normally embeded in $message using `<$type>` format.)
	 * @param	string $verbosity	The Shell::{QUIET|NORMAL|VERBOSE} constant.
	 * @param	string $expected	The final formatted string expected to be sent to the terminal.
	 * @param	string $msg	Optional PHPUnit error message when the assertion fails.
	 * @return	void
	 */
	public function testOut($message, $type, $verbosity, $expected, $msg = null) {
		// Make our mocked `out()` method return the first arg passed to it for easier comparison.
		$this->Shell->expects($this->once())
			->method('out')
			->with($expected, 1, $verbosity)
			->will($this->returnArgument(0));

		$this->assertEquals(
			$expected,
			$this->Shell->_out($message, $type, $verbosity),
			$msg
		);
	}
}
