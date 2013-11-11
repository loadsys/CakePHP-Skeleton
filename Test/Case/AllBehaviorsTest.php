<?php
/*
 * Custom test suite to execute all behavior tests.
 */
class AllBehaviorsTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All Behavior Tests');
		$suite->addTestDirectory(dirname(__FILE__) . '/Model/Behavior/');
		return $suite;
	}
}
