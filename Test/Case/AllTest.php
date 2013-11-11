<?php
/*
 * Custom test suite to execute all app/plugin tests.
 */
class AllTest extends PHPUnit_Framework_TestSuite {
	public static $suites = array(
		// Start with (typically) lower-level dependencies.
		'AllLibsTest.php',
		'AllVendorsTest.php',
		'AllPluginsTest.php',

		// Then data manipulation.
		'AllBehaviorsTest.php',
		'AllModelsTest.php',

		// Then business logic.
		'AllComponentsTest.php',
		'AllControllersTest.php',

		// Then command line apps that probably depend on all of the above.
		'AllCommandsTest.php',
		'AllTasksTest.php',

		// Then view helpers.
		'AllHelpersTest.php',
	);

	public static function suite() {
		$path = dirname(__FILE__) . '/';
		$suite = new CakeTestSuite('All Tests');

		foreach (self::$suites as $file) {
			if (is_readable($path . $file)) {
				$suite->addTestFile($path . $file);
			}
		}

		return $suite;
	}
}
