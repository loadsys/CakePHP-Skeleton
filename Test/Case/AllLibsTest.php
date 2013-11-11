<?php
/*
 * Custom test suite to execute all lib tests.
 */
class AllLibsTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All Lib Tests');
		$suite->addTestDirectory(dirname(__FILE__) . '/Lib/');
		return $suite;
	}
}
