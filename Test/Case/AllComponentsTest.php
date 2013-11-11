<?php
/*
 * Custom test suite to execute all component tests.
 */
class AllComponentsTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All Component Tests');
		$suite->addTestDirectory(dirname(__FILE__) . '/Controller/Component/');
		return $suite;
	}
}
