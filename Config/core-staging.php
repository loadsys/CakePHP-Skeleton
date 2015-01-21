<?php
/**
 * This is an environment-specific core configuration file.
 *
 * It contains staging-specific overrides for the common config settings
 * in `Config/core.php`. Only items that must truly be different from the
 * master core config should be added here.
 *
 */

$config = array(
	'debug' => 0,

	/**
	 * Staging DB configuration. These settings match those used for
	 * the staging site's MySQL server. Must at least define a `default`
	 * connection.
	 */
	'Database' => array(
		'default' => array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			'host' => '@TODO: Enter staging DB host.',
			'login' => '@TODO: Enter staging DB login.',
			'password' => '@TODO: Enter staging DB password.',
			'database' => '@TODO: Enter staging DB database.',
		),
	),

	/**
	 * Staging environment hint when debug>0. Sets the admin nav bar
	 * background color to hint the active APP_ENV. See:
	 * `LoadsysHtmlHelper::styleForEnv()`.
	 */
	'Defaults' => array(
		'EnvHint' => array(
			'snippet' => 'background: #ff9999;', // yellow-ish in staging
		),
	),
);
