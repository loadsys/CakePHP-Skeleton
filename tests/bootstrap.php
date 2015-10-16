<?php
/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */

use Cake\Core\Configure;

require dirname(__DIR__) . DS . 'config' . DS . 'bootstrap.php';

define('CORE_TESTS', CORE_PATH . 'tests' . DS);
define('CORE_TEST_CASES', CORE_TESTS . 'TestCase');
define('TEST_APP', CORE_TESTS . 'test_app' . DS);

Configure::write('App', [
	'namespace' => 'App',
	'encoding' => 'UTF-8',
	'base' => false,
	'baseUrl' => false,
	'dir' => TEST_APP . 'TestApp' . DS,
	'webroot' => 'webroot',
	'wwwRoot' => WWW_ROOT,
	'fullBaseUrl' => 'http://localhost',
	'imageBaseUrl' => 'img/',
	'jsBaseUrl' => 'js/',
	'cssBaseUrl' => 'css/',
	'paths' => [
		'plugins' => [TEST_APP . 'Plugin' . DS],
		'templates' => [APP . 'Template' . DS],
		'locales' => [APP . 'Locale' . DS],
	]
]);


// Wipe out any accumulated caches before running tests.
foreach (\Cake\Cache\Cache::configured() as $key) {
	\Cake\Cache\Cache::clear(false, $key);
	echo "Cleared cache: $key\n";
}

echo PHP_EOL;
