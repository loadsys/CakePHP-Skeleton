<?php
/*
 * Custom test suite to execute all model tests.
 */
class AllTest extends PHPUnit_Framework_TestSuite {

    public static function suite() {
        $path = APP . 'Test' . DS . 'Case' . DS;
        $path = dirname(__FILE__) . DS;  // This works better for plugins. (Maybe everything.)
        $suite = new CakeTestSuite('All Tests');

        $suite->addTestFile($path . 'AllModelsTest.php');
        $suite->addTestFile($path . 'AllControllersTest.php');

        return $suite;
    }
}