<?php
/**
 * Database connection configuration.
 */
class DATABASE_CONFIG {

	/**
	 * Default configuration. Should be suitable for production use when
	 * no APP_ENV is set.
	 *
	 * @access	public
	 * @var	array	$default
	 */
	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => '@TODO: Enter production DB host.',
		'login' => '@TODO: Enter production DB login.',
		'password' => '@TODO: Enter production DB password.',
		'database' => '@TODO: Enter production DB database.',
		//'prefix' => '',
		//'encoding' => 'utf8',
	);

	/**
	 * Staging configuration. Used to connect to the staging AWS RDS instance.
	 *
	 * @access	public
	 * @var	array	$vagrant
	 */
	public $staging = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => '@TODO: Enter staging DB host.',
		'login' => '@TODO: Enter staging DB login.',
		'password' => '@TODO: Enter staging DB password.',
		'database' => '@TODO: Enter staging DB database.',
	);

	/**
	 * Vagrant configuration. These settings match those in
	 * `Lib/puphpet/config.yaml` for the MySQL server that is set up in the
	 * Vagrant VM.
	 *
	 * @access	public
	 * @var	array	$vagrant
	 */
	public $vagrant = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'vagrant',
		'password' => 'vagrant',
		'database' => 'vagrant',
	);

	/**
	 * Sample AWS Elastic Beanstalk configuration. Because it depends on
	 * environment variables, it must be set up in `__construct()` below.
	 * Ref: http://docs.aws.amazon.com/elasticbeanstalk/latest/dg/create_deploy_PHP.rds.html
	 *
	 * @access	public
	 * @var	array	$aws
	 */
	public $aws = null;

	/**
	 * Test configuration. In spite of the constructor below, the TestShell
	 * should still selectively load the `::$test` config.
	 *
	 * @access	public
	 * @var	array	$test
	 */
	public $test = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'vagrant',
		'password' => 'vagrant',
		'database' => 'vagrant_test',
	);

	/**
	 * Memory only config. Used by the TreeCheckShell to build a table
	 * dynamically with the TreeBehavior.
	 *
	 * @access	public
	 * @var	array	$test
	 */
	public $memory = array(
		'datasource' => 'Database/Sqlite',
		'database' => ':memory:', // Or something independently reviewable, like: 'tmp/treetest.sqlite3',
	);

	/**
	 * Set up dynmaic configs and set the appropriate configuration based on
	 * the APP_ENV environment variable.
	 *
	 * If the APP_ENV env var is not set, or set to something that does not
	 * match one of the available configurations above, 'default' will be used.
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct() {
		// Define any configs that depend on dynamic input, such as $_SERVER vars.
		// We also want to silently ignore if these vars aren't set in any other environment.
		$rds = array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			'host' => @$_SERVER['RDS_HOSTNAME'],
			'port' => @$_SERVER['RDS_PORT'],
			'login' => @$_SERVER['RDS_USERNAME'],
			'password' => @$_SERVER['RDS_PASSWORD'],
			'database' => @$_SERVER['RDS_DB_NAME'],
		);
		$this->aws = $rds;

		// Determine which config is the "default" for the given environment.
		$available = array_keys(get_class_vars('DATABASE_CONFIG'));
		$env = getenv('APP_ENV');
		$env = (in_array($env, $available) ? $env : 'default');
		$this->default = $this->{$env};
	}
}
