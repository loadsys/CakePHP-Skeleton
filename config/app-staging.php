<?php

use App\Lib\ConfigClosures;

return [
	/**
	 * Debug Level:
	 *
	 * Always enabled for Travis builds.
	 */
	'debug' => true,

	/**
	 * Configure the cache adapters.
	 *
	 * These settings will be merged with the keys from app.php. That
	 * means we don't have to redefine the [className => Memcached]
	 * key, only where to connect.
	 *
	 * On Travis, we want to connect to the simple, unprotected daemon
	 * running on localhost, and we want caches to expire quickly.
	 */
	'Cache' => [
		'default' => [
			'compress' => false,
			'duration' => 120,
			'servers' => 'localhost',
			'username' => null,
			'password' => null,
		],
		'_cake_core_' => [
			'duration' => 120,
			'servers' => 'localhost',
			'username' => null,
			'password' => null,
		],
		'_cake_model_' => [
			'duration' => 120,
			'servers' => 'localhost',
			'username' => null,
			'password' => null,
		],
		'sessions' =>[
			'compress' => false,
			'duration' => 120,
			'servers' => 'localhost',
			'username' => null,
			'password' => null,
		],
	],

	/**
	 * Email configuration.
	 *
	 * @TODO: When running in staging, actually try to generate and deliver emails, **but override destinations**.
	 */
	'EmailTransport' => [
		'default' => [
			'className' => 'Stmp',
			'host' => 'localhost',
			'port' => 25,
			'timeout' => 30,
			'username' => null,
			'password' => null,
		],
	],

	'Email' => [
		'default' => [
			'from' => 'travis@staging',
		],
	],

	/**
	 * Connection information used by the ORM to connect
	 * to your application's datastores.
	 *
	 * Connect to the local MySQL instance running on the staging box.
	 */
	'Datasources' => [
		'default' => [
			'host' => '',
			'username' => '',
			'password' => '',
			'database' => '',
		],

		/**
		 * The test connection is used during the test suite.
		 */
		'test' => [
			'host' => '',
			'username' => '',
			'password' => '',
			'database' => '',
		],
	],

	/**
	 * Application-specific configurations.
	 */

	/**
	 * Staging Site Configuration
	 *
	 * Any time you'd be tempted to type one of these strings directly into
	 * a file, call this Configure var instead.
	 */
	'Defaults' => [
		'Env' => [
			'Token' => 'staging',
			'Hint' => [
				'Snippet' => 'background: #cc7000;', // orange
				'AuxContent' => '
					<script language="JavaScript">
					document.write(
						\'<li><a target="_blank" href="//\'
						+ window.location.hostname
						+ \':1080/">Mailcatcher</a></li>\'
					);
					</script>
				',
			],
		],
	],
];
