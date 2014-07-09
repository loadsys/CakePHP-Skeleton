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
	'trace' => true,
));

Configure::write('Exception', array(
	'handler' => 'ErrorHandler::handleException',
	'renderer' => 'ExceptionRenderer',
	'log' => true,
));

Configure::write('App.encoding', 'UTF-8');
// Configure::write('App.baseUrl', env('SCRIPT_NAME'));
// Configure::write('App.fullBaseUrl', 'http://example.com');
// Configure::write('App.imageBaseUrl', 'img/');
// Configure::write('App.cssBaseUrl', 'css/');
// Configure::write('App.jsBaseUrl', 'js/');

Configure::write('Routing.prefixes', array('admin'));

//Configure::write('Cache', array(
	//'disable' => true,
	//'check' => true,
	//'viewPrefix' => 'prefix',
//));

Configure::write('Session', array(
	'defaults' => 'php',
));

/**
 * Security settings.
 *
 * These must remain separate keys in the Skeleton so the `bake project`
 * Shell can rewrite them for us.
 */

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

/**
 * Configure the cache used for general framework caching. Path information,
 * object listings, and translation cache files are stored with this configuration.
 */
$engine = 'File';
$duration = (Configure::read('debug') > 0 ? '+10 seconds' : '+999 days');
$prefix = '_PROJECT_NAME__';  //@TODO: Set cache prefix for the app, leave the trailing underscore.
Cache::config('_cake_core_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_core_',
	'path' => CACHE . 'persistent' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration,
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
 * Default DB configuration. Should be suitable for production use when
 * no APP_ENV is set. Must at least define a `default` connection.
 */
Configure::write('Database', array(
	'default' => array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => '@TODO: Enter production DB host.',
		'login' => '@TODO: Enter production DB login.',
		'password' => '@TODO: Enter production DB password.',
		'database' => '@TODO: Enter production DB database.',
		//'prefix' => '',
		//'encoding' => 'utf8',
	),
));

/**
 * Default Site Configuration
 *
 * Any time you'd be tempted to type one of these strings directly into
 * a file, call this Configure var instead.
 */
Configure::write('Defaults', array(
	'env' => 'production', //APP_ENV
	'short_name' => 'APP', //@TODO: Set the app's short name.
	'long_name' => 'Loadsys CakePHP Skeleton', //@TODO: Set the app's long name.
	'domain' => 'loadsys.com', //@TODO: Set the app's default domain name (used for email addresses and suitable for generated docs, like PDFs.)
	'meta_description' => 'This is a fresh baked Loadsys CakePHP site skeleton.', //@TODO: Set the app's default meta description.
	'meta_keywords' => 'loadsys, cakephp, rapid web development', //@TODO: Set the app's default meta keywords.
	'EnvHint' => array(
		'format' => '<style> .navbar-fixed-top { %s } </style>', // Injected into your layout with `$this->Html->styleForEnv()`
		'snippet' => '', // Blank in production, and when debug=0.
	),
));

/**
 * Anonymous helper function for constructing consistent email addresses.
 *
 * Will still be available in environment-specific configs loaded later,
 * but overrides to `Defaults.shortname` and `Defaults.domain` defined IN
 * those files probably won't work correctly.
 */
$email = function($localAddress, $displayName = false) {
	$displayName = ($displayName ?: Configure::read('Defaults.short_name'));
	$address = sprintf('%s@%s', $localAddress, Configure::read('Defaults.domain'));
	return array($address => $displayName);
};

/**
 * Email Configuration
 *
 * `Transports` is used to populate the Config/email.php class properties.
 * The entries below will be used in production. Individual settings can
 * be overridden in core-vagrant, etc. At least a [default] key must be
 * defined.
 *
 * To use Address slugs, either pass the result straight to AppEmail, or
 * unpack a config key in your code like so:
 *
 * 		$address = Configure::read('Email.Address.slugname');
 * 		$email = key($address);
 * 		$display = current($address);
 */
Configure::write('Email', array(
	'Address' => array(
		'noreply' => $email('no-reply'),
		'support' => $email('support'),
		'contact' => $email('info'),
	),
	'Transports' => array(
		'default' => array(
			'transport' => 'Mail', // Use web server's local mail routing.
			'from' => $email('no-reply'),
			'charset' => 'utf-8',
			'headerCharset' => 'utf-8',
			'emailFormat' => 'html',
			'log' => false,

			//'sender' => null,
			//'to' => null,
			//'cc' => null,
			//'bcc' => null,
			//'replyTo' => null,
			//'readReceipt' => null,
			//'returnPath' => null,
			//'messageId' => true,
			//'subject' => null,
			//'message' => null,
			//'headers' => null,
			//'viewRender' => null,
			//'template' => false,
			//'layout' => false,
			//'viewVars' => null,
			//'attachments' => null,
			//'host' => 'localhost',
			//'port' => 25,
			//'timeout' => 30,
			//'username' => 'user',
			//'password' => 'secret',
			//'client' => null,
		),
	),
));

/**
 * Enumeration lists.
 *
 * These replace the legacy "id,name" lookup tables. Each key should consist
 * of a flat array of `[text-slug => __(Display Value)]` pairs. These "lists"
 * are made available to the front end via AppModel->getList(), and are
 * typically to populate <select> lists. The slug value is stored in the
 * database with a fieldtype of `VARCHAR(50)` and named `something_slug` to
 * identify it. The array keys from each list below (the slugs) will be saved
 * into that field to represent the selected option for a given record. Keys
 * must consist ONLY of lowercase letters, digits and dashes.
 */
Configure::write('Lists', array(
	// Enumerated type for Table field usage.
	'TableFieldSlug' => array( // `Table.field_slug`
		'option1' => __('Option One'), // meaning.
		'option2' => __('Option Two'), // meaning.
	),
));

/**
 * SSL
 */
Configure::write('SSL', array(
	'enabled' => false,
));

/**
 * CDN Asset Configuration.
 *
 * Allows you to define the source of asset files on a per-environment basis.
 * In production, you likely want to use concatenated and minified assets,
 * possible from a CDN server. In development and staging, likely local
 * un-compiled source files. Only define assets that have alternate sources.
 * Assets that are **always** provided from the repo should still be defined
 * directly in your layout.
 */
Configure::write('CDN', array(
	'css' => array(
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css',
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css',
	),
	'js' => array(
		'//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js',
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js',
	),
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
		'domain' => '',
	),
));

/**
 * Social networking accounts.
 *
 * Powers Elements/Layouts/social_meta_tags.ctp. Leave any [username]
 * fields empty to disable related OpenGraph meta tags.
 *
 * Also powers Elements/Layouts/social_icons.ctp. Leave [link] empty to
 * disable an icon. Place icons such as webroot/img/social-icons/Twitter.png,
 * or specify [image] keys for each service below. [width] and [height]
 * also optionally available (default 48x20 in the ctp.)
 */
Configure::write('SocialNetworks', array(
	'Twitter' => array(
		'link' => 'https://twitter.com/',
		'username' => '',
		// 'image' => '',
		// 'width' => '',
		// 'height' => '',
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
 * **THIS SHOULD BE THE LAST BLOCK IN YOUR core.php FILE.**
 */


/**
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

unset($email); // Clean up helper function.