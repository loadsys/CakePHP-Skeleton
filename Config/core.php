<?php
/**
 * This is the core configuration file.
 *
 * **ALL** configuration settings should be placed here, and overriden in
 * environment-sepcific files such as `core-vagrant.php`.
 *
 */

Configure::write('debug', 0);

Configure::write('Error', array(
	'handler' => 'ErrorHandler::handleError',
	'level' => E_ALL & ~E_DEPRECATED,
	'trace' => true
));

Configure::write('Exception', array(
	'handler' => 'ErrorHandler::handleException',
	'renderer' => 'ExceptionRenderer',
	'log' => true
));

//Configure::write('App', array(
	//'encoding' => 'UTF-8',
	//'baseUrl' => env('SCRIPT_NAME'),
	//'fullBaseUrl' => 'http://example.com',
	//'imageBaseUrl' => 'img/',
	//'cssBaseUrl' => 'css/',
	//'jsBaseUrl' => 'js/',
//));

Configure::write('Routing.prefixes', array('admin'));

//Configure::write('Cache', array(
	//'disable' => true,
	//'check' => true,
	//'viewPrefix' => 'prefix',
//));

Configure::write('Session', array(
	'defaults' => 'php',
));

Configure::write('Security.salt', 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi');

Configure::write('Security.cipherSeed', '76859309657453542496749683645');

//Configure::write('Asset', array(
	//'timestamp' => true,
	//'filter.css' => 'css.php',
	//'filter.js' => 'custom_javascript_output_filter.php',
//));

Configure::write('Acl', array(
	'classname' => 'DbAcl',
	'database' => 'default',
));

//date_default_timezone_set('UTC');

//Configure::write('Config.timezone', 'Europe/Paris');

$engine = 'File';

// In development mode, caches should expire quickly.
$duration = '+999 days';
if (Configure::read('debug') > 0) {
	$duration = '+10 seconds';
}

// Prefix each application on the same server with a different string, to avoid Memcache and APC conflicts.
$prefix = '_PROJECT_NAME__';  //@TODO: Set cache prefix for the app.

/**
 * Configure the cache used for general framework caching. Path information,
 * object listings, and translation cache files are stored with this configuration.
 */
Cache::config('_cake_core_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_core_',
	'path' => CACHE . 'persistent' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));

/**
 * Configure the cache for model and datasource caches. This cache configuration
 * is used to store schema descriptions, and table listings in connections.
 */
Cache::config('_cake_model_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_model_',
	'path' => CACHE . 'models' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));



/**
 * Application-specific configurations.
 */

//@TODO: Define app-specific vars here.

/**
 * CDN Configuration
 */
Configure::write('CDN', array(
	'enabled' => false,
));

/**
 * Google service settings. Leave any ID fields empty to disable the
 * associated Javascript.
 */
Configure::write('Google', array(
	'SiteSearch' => array(
		'engine_id' => '', // Production engine. Leave empty to disable.
	),
	'Analytics' => array(
		'tracking_id' => '', // Leave empty to disable.
	),
));

/**
 * Social networking accounts. Leave any `username` fields empty to
 * disable related OpenGraph meta tags.
 */
Configure::write('SocialNetworks', array(
	'Twitter' => array(
		'link' => 'https://twitter.com/',
		'username' => '',
	),
	'Facebook' => array(
		'link' => 'https://www.facebook.com/',
		'username' => '',
		'profile_id' => false,
	),
	'YouTube' => array(
		'link' => 'http://www.youtube.com/user/',
		'username' => '',
	),
));



/**
 * Load environment-specific overrides.
 *
 * **THIS SHOULD BE THE LAST STATEMENT IN YOUR core.php FILE.**
 *
 * File such as `Config/core-production.php` can be created to match the
 * `APP_ENV` environment variable and must contain a $config = array(...);
 * definition in them to override any values defined here in `core.php` or in
 * `bootstrap.php`. Any configuration changes that are environment-specific
 * should be made in the appropriate file. See also: `Config/database.php` for
 * allowed APP_ENV values.
 */
$env = getenv('APP_ENV');
if (is_readable(dirname(__FILE__) . "/core-{$env}.php")) {
	Configure::load("core-{$env}");
}

/**
 * Load developer-specific overrides. (Allows a developer to customize their
 * local config as needed for testing by placing their definitions in an
 * (untracked) `Config/core-local.php` file.)
 */
if (is_readable(dirname(__FILE__) . "/core-local.php")) {
	Configure::load("core-local");
}

/**
 * Include these singletons extremely early so they are available in the
 * routes file, etc.
 */
App::uses('UrlRef', 'Lib');
