<?php
/**
 * Updates and changes for the Configuration for when running in the Staging env.
 */

use App\Lib\ConfigClosures;

return [
	/**
	 * Debug Level:
	 *
	 * Keep enabled in staging to reveal errors.
	 */
	'debug' => true,

	/**
	 * Configure the cache adapters.
	 *
	 * These settings will be merged with the keys from app.php. That
	 * means we don't have to redefine the [className => Memcached]
	 * key, only where to connect.
	 *
	 * In staging, we want to connect to the simple, unprotected daemon
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
			'duration' => '+1 day',
			'servers' => '127.0.0.1',
			'username' => null,
			'password' => null,
		],
	],

	/**
	 * Email configuration.
	 *
	 * Staging continues to use Mailcatcher (running locally on the same
	 * server) to intercept outbound email. This allows for easy testing
	 * both by Loadsys and the client without having to modify the app to
	 * "detect" this special cased environment and override To: addresses.
	 */
	'EmailTransport' => [
		'default' => [
			'className' => 'Smtp',
			'host' => '127.0.0.1',
			'port' => 1025,
			'timeout' => 5,
			'username' => null,
			'password' => null,
			'client' => null,
			'tls' => null,
		],
	],

	'Email' => [
		'default' => [
			'from' => 'staging@loadsys.com',
		],
	],

	/**
	 * Connection information used by the ORM to connect
	 * to your application's datastores.
	 *
	 * Connect to the an external MySQL instance, typically RDS.
	 */
	'Datasources' => [
		'default' => [
			'host' => 'localhost',
			'username' => 'staging',
			'password' => 'staging',
			'database' => 'staging',
		],

		/**
		 * The test connection is used during the test suite.
		 */
		'test' => [
			'host' => 'localhost',
			'username' => 'staging',
			'password' => 'staging',
			'database' => 'staging_test',
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
		/**
		 * We don't have to **force** ssl in staging because it should already
		 * enabled via the load balancer. Since config/bootstrap.php defines
		 * an updated Request::is('ssl') detector that recognizes AWS load
		 * balancer scenarios, asset URLs will still be created with https://
		 * even though Cake will think the request was over http:// from
		 * the ELB.
		 */
		'ssl_force' => false,

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
