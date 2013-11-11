<?php
/*
 * Custom test suite to execute all command tests.
 */
class AllCommandsTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All Command Tests');
		$suite->addTestDirectory(dirname(__FILE__) . '/Console/Command/');
		return $suite;
	}
}
