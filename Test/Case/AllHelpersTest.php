<?php
/*
 * Custom test suite to execute all helper tests.
 */
class AllHelpersTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All Helper Tests');
		$suite->addTestDirectory(dirname(__FILE__) . '/View/Helper/');
		return $suite;
	}
}
