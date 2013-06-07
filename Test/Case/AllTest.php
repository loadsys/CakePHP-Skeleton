<?php
/*
 * Custom test suite to execute all model tests.
 */
class AllTest extends PHPUnit_Framework_TestSuite {

    public static function suite() {
        $path = APP . 'Test' . DS . 'Case' . DS;
        $suite = new CakeTestSuite('All Tests');

        $suite->addTestFile($path . 'AllModelsTest.php');
        $suite->addTestFile($path . 'AllControllersTest.php');

        return $suite;
    }
}