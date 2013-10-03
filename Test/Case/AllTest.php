<?php
/*
 * Custom test suite to execute all model tests.
 */
class AllTest extends PHPUnit_Framework_TestSuite {

    //@TODO: See also (http://us1.php.net/manual/en/class.directoryiterator.php#88385) for an automatic way of scanning the dirs and including files/classes.

    public static function suite() {
        $path = APP . 'Test' . DS . 'Case' . DS;
        $path = dirname(__FILE__) . DS;  // This works better for plugins. (Maybe everything.)
        $suite = new CakeTestSuite('All Tests');

        $suite->addTestFile($path . 'AllModelsTest.php');
        $suite->addTestFile($path . 'AllControllersTest.php');

        return $suite;
    }
}