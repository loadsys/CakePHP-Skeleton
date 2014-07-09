<?php
/**
 * This file contains shared variables used in most of the loadsys/cakephp-shell-scripts repo. This file should always be accessed via the `bin/env-vars-echo` script, which can output a subset of these values in a variety of formats.
 *
 * The variable must always be named PROJECT_VARS and must be an array with keyed element matching expected values for the APP_ENV envrionment variable. In situations where that variable is not available, the **FIRST** element in this array will be used to return requests.
 */

$PROJECT_VARS = array(
	// Default values. When other keys are requested they will be merged on top of these. Keys should be set here and overriden elsewhere. These values will get replaced into a number of files during project creation using `spawn`.
	'global' => array(
		'PROJECT_TITLE'				=> 'Project Name',		// Proper project title. Used in: README.md, phpdoc.xml
		'PROJECT_NAME'				=> 'project-name',		// Composer-compatible lowercase-dashed name (no vendor!) Used in: composer.json, package.json, core.php, phpdoc.xml
		'PROJECT_DESCRIPTION'		=> 'App does something.', // Short description of app. Used in: composer.json
		'PROJECT_CLIENT_NAME'		=> '',  // Proper name of the app's owner. Used in: README.md
		'PROJECT_PRODUCTION_URL'	=> '',  // Full URL to production website. Used in: README.md
		'PROJECT_STAGING_URL'		=> '',  // Full URL to staging website. Used in: README.md
		'PROJECT_MANAGEMENT_URL'	=> '',  // Full URL to Basecamp project. Used in: README.md
		'PROJECT_DOCUMENT_URL'		=> '',  // Full URL to internal passwords document. Used in: README.md
		'PROJECT_REPO_URL'			=> '',  // Full URL to the git repo (NOT Github webpage). Used in: README.md
		'PROJECT_NOTIFY_EMAILS'		=> '',  // Space separated list of email addresses to "notify". Used in: `bin/update`
		'VAGRANT_HOSTNAME' 			=> 'loadsys-vagrant',	// Hostname for the vagrant VM. Used in: puphpet/config.yaml
		'VAGRANT_APACHE_PORT'		=> 8080,				// Port forward Apache will be exposed on. Used in: puphpet/config.yaml
		'VAGRANT_MYSQL_PORT'		=> 3307,				// Port forward MySQL will be exposed on. Used in: puphpet/config.yaml
	),

	// Will only be used when running tests under Travis. The 'cfg' key is set up to completely build the necessary .travis.yml file.
	'travis' => array(  
		'cfg' => array(
			'language' => 'php',
			'php' => array(
				'5.3',
			),
			'env' => array(
				'global' => array(
					'secure' => '',
					'APP_ENV=travis',
				),
			),
			'services' => array(),
			'branches' => array(
				'except' => array(
					'gh-pages',
				),
			),
			'before_install' => array(
				'git submodule update --init --recursive',
			),
			'install' => array(
				'sudo apt-get -y install pypy python-sphinx graphviz',
				'composer install',
			),
			//'before_script' => array(),
			'script' => array(
				'sh -c "./bin/travis-test-run;"',
			),
			//'after_success' => array(),
			//'after_failure' => array(),
			'after_script' => array(
				'vendor/bin/phpdoc.php -d app --configuration=Config/phpdoc.xml',
				'sh -c "./bin/woodhouse-publish;"',
			),
			'notifications' => array(
				'email' => false,
			),
		),
	),
);
