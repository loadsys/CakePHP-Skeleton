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
	 * dataProvider for testStyleForEnv method
	 *
	 * @return array
	 */
	public function provideStyleForEnvArgs() {
		return array(
			array(
				'vagrant',
				'%a#ff9999%A',
				'Vagrant env should produce a red background.',
			),
			array(
				'dev',
				'%a#ff9999%A',
				'Dev env should produce a red background.',
			),
			array(
				'staging',
				'%a#e5c627%A',
				'Staging env should produce a yellow background.',
			),
			array(
				'production',
				'',
				'Production env should produce no special output.',
			),
			array(
				'other',
				'',
				'An unidentified environment should produce proudction output (none).',
			),
		);
	}

	/**
	 * testStyleForEnv
	 *
	 * @dataProvider provideStyleForEnvArgs
	 * @return void
	 */
	public function testStyleForEnv($input, $format, $msg = '') {
		$this->assertStringMatchesFormat(
			$format,
			$this->Helper->styleForEnv($input),
			$msg
		);
	}

	/**
	 * Verify that styleForEnv() attempts to use $_SERVER[APP_ENV] when
	 * non explicitly provided.
	 *
	 * @return void
	 */
	public function testStyleForEnvWithNullEnv() {
		$envBackup = $_SERVER['APP_ENV'];

		$_SERVER['APP_ENV'] = 'special';
		$this->assertEmpty(
			$this->Helper->styleForEnv(),
			'Passing no argument should force styleForEnv() to read $_SERVER[APP_ENV].'
		);

		$_SERVER['APP_ENV'] = $envBackup;
	}
}
