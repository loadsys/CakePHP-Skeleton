<?php
/**
 * Database connection configuration loader.
 *
 * Database connection details will be read from the Configure class.
 * Production database information should be placed in `app/config/core.php`.
 * Overrides for staging or vagrant environments should be placed in the
 * corresponding `app/config/core-*.php` files.
 */
class DATABASE_CONFIG {

	/**
	 * Default configuration. Should be suitable for production use when
	 * no APP_ENV is set. Will be populated by `__construct()` using the
	 * value from `Configure::read('Database.default')`.
	 *
	 * @access	public
	 * @var	array
	 */
	public $default = null;

	/**
	 * Loads catabase connection information from
	 * `Configure::read('Database')`, which should be defined in
	 * `Config/core.php`.
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct() {
		$dbConfigs = Configure::read('Database');
		if (!is_array($dbConfigs)) {
			throw new Exception('No `Database` connections defined in core.php.');
		}

		foreach ($dbConfigs as $key => $config) {
			$this->{$key} = $config;
		}

		if (!property_exists($this, 'default') || !is_array($this->default)) {
			throw new Exception('No `Database.default` connection defined in core.php.');
		}
	}
}
