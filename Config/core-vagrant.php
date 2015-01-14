<?php
/**
 * This is an environment-specific core configuration file.
 *
 * It contains vagrant-specific overrides for the common config settings
 * in `Config/core.php`. Only items that must truly be different from the
 * master core config should be added here.
 *
 */

$config = array(
	'debug' => 2,

	/**
	 * Vagrant DB configuration. These settings match those in
	 * `Lib/puphpet/config.yaml` for the MySQL server that is set up in the
	 * Vagrant VM. Must at least define a `default` connection.
	 */
	'Database' => array(
		'default' => array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			'host' => 'localhost',
			'login' => 'vagrant',
			'password' => 'vagrant',
			'database' => 'vagrant',
		),
		'test' => array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			'host' => 'localhost',
			'login' => 'vagrant',
			'password' => 'vagrant',
			'database' => 'vagrant_test',
		),
		'memory' => array(
			'datasource' => 'Database/Sqlite',
			'database' => ':memory:', // Or something independently reviewable, like: 'tmp/treetest.sqlite3',
		),
	),
);
