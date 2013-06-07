<?php
/*
 * Custom test suite to execute all model tests.
 */
class AllModelsTest extends PHPUnit_Framework_TestSuite {

    public static function suite() {
        $path = APP . 'Test' . DS . 'Case' . DS;
        $suite = new CakeTestSuite('All Model Tests');
        $suite->addTestDirectory($path . 'Model' . DS);
        return $suite;
    }
}