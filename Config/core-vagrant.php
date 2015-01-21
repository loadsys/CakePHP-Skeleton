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
	 * `puphpet/config.yaml` for the MySQL server that is set up in the
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

	/**
	 * Vagrant environment hints.
	 *
	 * Sets a meta tag with the active env. When debug>0, sets the admin
	 * nav bar background color to hint the active APP_ENV. See:
	 * `LoadsysHtmlHelper::envHint()`.
	 */
	'Defaults' => array(
		'env' => 'vagrant',
		'EnvHint' => array(
			'snippet' => 'background: #ff9999;', // red-ish in development
		),
	),

	/**
	 * CDN Asset Configuration.
	 *
	 * Use local, in-repo, uncompiled files in development.
	 */
	'CDN' => array(
		'css' => array(
			'bootstrap-3.3.2/bootstrap.min',
			'bootstrap-3.3.2/bootstrap-theme.min',
		),
		'js' => array(
			'jquery-1.11.2/jquery.min',
			'bootstrap-3.3.2/bootstrap.min',
		),
	),
);
