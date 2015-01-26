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
	 * Staging environment hints.
	 *
	 * Sets a meta tag with the active env. When debug>0, sets the admin
	 * nav bar background color to hint the active APP_ENV. See:
	 * `LoadsysHtmlHelper::envHint()`.
	 */
	'Defaults' => array(
		'env' => 'staging',
		'EnvHint' => array(
			'snippet' => 'background: #ff9999;', // yellow-ish in staging
		),
	),

	/**
	 * On staging, use the web server's local mail config, but still log.
	 */
	'Email' => array(
		'Transports' => array(
			'default' => array(
				'transport' => 'Mail',
				'log' => true,
			),
		),
	),

	/**
	 * CDN Asset Configuration.
	 *
	 * Use local, in-repo, uncompiled files in staging.
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
