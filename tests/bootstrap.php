<?php
/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */
require dirname(__DIR__) . '/config/bootstrap.php';

// Wipe out any accumulated caches before running tests.
foreach(\Cake\Cache\Cache::configured() as $key) {
	\Cake\Cache\Cache::clear(false, $key);
	echo "Cleared cache: $key\n";
}
echo PHP_EOL;
