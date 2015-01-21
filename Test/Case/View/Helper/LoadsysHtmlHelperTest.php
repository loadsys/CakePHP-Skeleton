<?php
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('LoadsysHtmlHelper', 'View/Helper');

/**
 * LoadsysHtmlHelper Test Case
 *
 */
class LoadsysHtmlHelperTest extends CakeTestCase {

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Helper = new LoadsysHtmlHelper($View);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->Helper);
		parent::tearDown();
	}

	/**
	 * dataProvider for testTimeAgo
	 *
	 * @return array
	 */
	public function provideTimeAgoArgs() {
		return array(
			'Empty String' => array(
				'',
				'on '. date('Y-m-d', 0),
				'Empty string should use "on yyyy-mm-dd" format.',
			),
			'Null String' => array(
				null,
				'on '. date('Y-m-d', 0),
				'Null should use "on yyyy-mm-dd" format.',
			),
			'0 String' => array(
				0,
				'on '. date('Y-m-d', 0),
				'0 should use "on yyyy-mm-dd" format.',
			),
			'Unix Timestamp' => array(
				'1096009200',
				"on 2004-09-24",
				'Unix timestamp should use "on yyyy-mm-dd" format.',
			),
			'DateTime String' => array(
				'10 Oct 2000',
				"on 2000-10-10",
				'Date and time string should use "on yyyy-mm-dd" format.',
			),
			'Recent Timestamp' => array(
				strtotime("-3 weeks"),
				"3 weeks ago",
				'Recent time should use "X units ago" format.',
			),
		);
	}

	/**
	 * test timeAgo method
	 *
	 * @dataProvider provideTimeAgoArgs
	 * @return void
	 */
	public function testTimeAgo($input, $expected, $msg = '') {
		$this->assertEquals(
			$expected,
			$this->Helper->timeAgo($input),
			$msg
		);
	}

	/**
	 * dataProvider for testModified
	 *
	 * @return array
	 */
	public function provideModifiedArgs() {
		return array(
			'Empty String' => array(
				'',
				'on '. date('Y-m-d', 0),
				'',
			),
			'Null String' => array(
				null,
				'Never',
				'',
			),
			'0 String' => array(
				0,
				'on '. date('Y-m-d', 0),
				'',
			),
			'Unix Timestamp' => array(
				'1096009200',
				"on 2004-09-24",
				'',
			),
			'dateTime String' => array(
				'10 Oct 2000',
				"on 2000-10-10",
				'',
			),
		);
	}

	/**
	 * test modified method
	 *
	 * @dataProvider provideModifiedArgs
	 * @return void
	 */
	public function testModified($input, $expected, $msg = '') {
		$this->assertEquals(
			$expected,
			$this->Helper->modified($input),
			$msg
		);
	}

	/**
	 * dataProvider for testYesNo method
	 *
	 * @return array
	 */
	public function provideYesNoArgs() {
		return array(
			array(
				true,
				'Yes',
				'',
			),
			array(
				false,
				'No',
				'',
			),
			array(
				null,
				'No',
				'',
			),
		);
	}

	/**
	 * testYesNo
	 *
	 * tests the yesNo method
	 *
	 * @dataProvider provideYesNoArgs
	 * @return void
	 */
	public function testYesNo($input, $expected, $msg = '') {
		$this->assertEquals(
			$expected,
			$this->Helper->yesNo($input),
			$msg
		);
	}

	/**
	 * Provide a variety of [debug level, sprintf format string, snippet,
	 * expected string, assertion message] sets to testEnvHint().
	 *
	 * @return array
	 */
	public function provideEnvHintArgs() {
		return array(
			array(
				0, 'test(%s)', 'vagrant', // debug, format, snippet
				'', // expected
				'When debug is disabled, expect empty string.', // msg
			),
			array(
				2, 'test(%s)', 'vagrant',
				'test(vagrant)',
				'When debug is enabled, expect a formatted output string.',
			),
			array(
				2, 'test(%s)', '',
				'',
				'When snippet string is empty, expect empty string.',
			),
			array(
				2, '', 'vagrant',
				'',
				'When fomat string is empty, expect empty string.',
			),
		);
	}

	/**
	 * testEnvHint
	 *
	 * @dataProvider provideEnvHintArgs
	 * @param int $debug The Configure(debug) level to set before the test.
	 * @param string $format The Configure(Default.EnvHint.format) to set before the test.
	 * @param string $snippet The Configure(Default.EnvHint.style) to set before the test.
	 * @param string $expected The expected output string.
	 * @param string $msg Optional phpunit assertion failure message.
	 * @return void
	 */
	public function testEnvHint($debug, $format, $snippet, $expected, $msg = '') {
		Configure::write('debug', $debug);
		Configure::write('Defaults.EnvHint.format', $format);
		Configure::write('Defaults.EnvHint.snippet', $snippet);
		$this->assertEquals(
			$expected,
			$this->Helper->envHint(),
			$msg
		);
	}
}
