<?php
/*
 * Custom test suite to execute all controller tests.
 */
class AllControllersTest extends PHPUnit_Framework_TestSuite {

    public static function suite() {
        $path = APP . 'Test' . DS . 'Case' . DS;
        $suite = new CakeTestSuite('All Controller Tests');
        $suite->addTestDirectory($path . 'Controller' . DS);
        return $suite;
    }
}