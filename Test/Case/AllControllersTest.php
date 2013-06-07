<?php
/*
 * Custom test suite to execute all controller tests.
 */
class AllControllersTest extends PHPUnit_Framework_TestSuite {

    public static function suite() {
        $path = dirname(__FILE__) . DS;
        $suite = new CakeTestSuite('All Controller Tests');
        $suite->addTestDirectory($path . 'Controller' . DS);
        return $suite;
    }
}