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
	 * running on localhost, and we want caches to expire moderately quickly.
	 */
	'Cache' => [
		'default' => [
			'compress' => false,
			'duration' => 120,
			'servers' => '127.0.0.1',
			'username' => null,
			'password' => null,
		],
		'_cake_core_' => [
			'duration' => 120,
			'servers' => '127.0.0.1',
			'username' => null,
			'password' => null,
		],
		'_cake_model_' => [
			'duration' => 120,
			'servers' => '127.0.0.1',
			'username' => null,
			'password' => null,
		],
		'sessions' => [
			'compress' => false,
			'duration' => 120,
			'servers' => '127.0.0.1',
			'username' => null,
			'password' => null,
		],
	],

	/**
	 * Email configuration.
	 *
	 * When running on Travis, never try to generate actual emails.
	 * Only log them.
	 */
	'EmailTransport' => [
		'default' => [
			'className' => 'Debug',
		],
	],

	'Email' => [
		'default' => [
			'from' => 'travis@loadsys.com',
		],
	],

	/**
	 * Connection information used by the ORM to connect
	 * to your application's datastores.
	 *
	 * Connect to the local MySQL instance running on the travis build box.
	 */
	'Datasources' => [
		'default' => [
			'host' => '0.0.0.0',
			'username' => 'travis',
			'password' => '',
			'database' => 'travis_app',
		],

		/**
		 * The test connection is used during the test suite.
		 */
		'test' => [
			'host' => '0.0.0.0',
			'username' => 'travis',
			'password' => '',
			'database' => 'travis_app',
		],
	],

	/**
	 * Application-specific configurations.
	 */

	/**
	 * Testing Site Configuration
	 *
	 * Any time you'd be tempted to type one of these strings directly into
	 * a file, call this Configure var instead.
	 */
	'Defaults' => [
		'Env' => [
			'Token' => 'travis',
			'Hint' => [
				'Snippet' => 'background: #9999ff;', // light blue
			],
		],
	],
];
