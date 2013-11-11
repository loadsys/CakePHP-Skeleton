<?php
/*
 * Custom test suite to execute all controller tests.
 */
class AllControllersTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All Controller Tests');
		$suite->addTestDirectory(dirname(__FILE__) . '/Controller/');
		return $suite;
	}
}
