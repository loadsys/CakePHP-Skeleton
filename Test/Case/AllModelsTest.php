<?php
/*
 * Custom test suite to execute all model tests.
 */
class AllModelsTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All Model Tests');
		$suite->addTestDirectory(dirname(__FILE__) . '/Model/');
		return $suite;
	}
}
