<?php

use App\Lib\ConfigClosures;

return [
	/**
	 * Debug Level:
	 *
	 * Vagrant dev env defaults to ON. (Override in `app-local` if necessary.)
	 */
	'debug' => true,

	/**
	 * Configure the cache adapters.
	 *
	 * These settings will be merged with the keys from app.php. That
	 * means we don't have to redefine the [className => Memcached]
	 * key, only where to connect.
	 *
	 * In a development Vagrant box, we want to connect to the simple,
	 * unprotected daemon running on localhost, and we want caches to
	 * expire quickly.
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
	 * The vagrant VM runs Mailcatcher internally, making email
	 * available in a web interface on port 1080.
	 */
	'EmailTransport' => [
		'default' => [
			'className' => 'Smtp',
			'host' => '127.0.0.1',
			'port' => 1025,
			'timeout' => 30,
			'username' => null,
			'password' => null,
			'client' => null,
			'tls' => null,
		],
	],

	'Email' => [
		'default' => [
			'from' => 'vagrant@loadsys.com',
		],
	],

	/**
	 * Connection information used by the ORM to connect
	 * to your application's datastores.
	 *
	 * Only define the keys that MUST be different from production:
	 * host, user, pass and db name.
	 */
	'Datasources' => [
		'default' => [
			'host' => 'localhost',
			'username' => 'vagrant',
			'password' => 'vagrant',
			'database' => 'vagrant',
		],

		/**
		 * The test connection is used during the test suite.
		 */
		'test' => [
			'host' => 'localhost',
			'username' => 'vagrant',
			'password' => 'vagrant',
			'database' => 'vagrant_test',
		],
	],

	/**
	 * Application-specific configurations.
	 */

	/**
	 * Development Site Configuration
	 *
	 * Any time you'd be tempted to type one of these strings directly into
	 * a file, call this Configure var instead.
	 */
	'Defaults' => [
		'Env' => [
			'Token' => 'vagrant',
			'Hint' => [
				'Snippet' => 'background: #8d1c1c;', // maroon
			],
		],
	],
];
