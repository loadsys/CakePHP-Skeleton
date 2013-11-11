<?php
/*
 * Custom test suite to execute all task tests.
 */
class AllTasksTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All Task Tests');
		$suite->addTestDirectory($path . '/Console/Command/Task/');
		return $suite;
	}
}
