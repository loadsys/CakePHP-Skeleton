<?php
/**
 * Travis-CI database config.
 *
 * This file will get copied into place by `./bootstrap.sh` (which runs
 * `bin/set-configs travis`) during Travis's `install` step.
 */
class DATABASE_CONFIG {
	public $default = array(
		'datasource' => 'Database/Mysql',
		'host' => '0.0.0.0',
		'persistent' => false,
		'login' => 'travis',
		'password' => '',
		'database' => 'travis_app',
		'prefix' => '',
	);
	public $test = array(
		'datasource' => 'Database/Mysql',
		'host' => '0.0.0.0',
		'persistent' => false,
		'login' => 'travis',
		'password' => '',
		'database' => 'travis_app',
		'prefix' => '',
	);
}