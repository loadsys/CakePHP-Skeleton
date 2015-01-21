<?php

/**
 * Email configuration class.
 *
 * All configurations are defined in Config/core.php and imported by
 * ::__construct() using Configure::read().
 */
class EmailConfig {

	// !! This app uses an extended AppEmail class. See Lib/Network/AppEmail.php for usage. !!

	/**
	 * Default configuration. Will be populated by `__construct()` using the
	 * value from `Configure::read('Email.Transports.default')`.
	 *
	 * @access	public
	 * @var	array
	 */
	public $default = array();

	/**
	 * Loads email transport information from
	 * `Configure::read('Email.Transports')`, which should be defined in
	 * `Config/core.php`.
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct() {
		$emailConfigs = Configure::read('Email.Transports');
		if (!is_array($emailConfigs)) {
			throw new Exception('No `Email.Transports` key defined in core.php.');
		}

		foreach ($emailConfigs as $key => $config) {
			$this->{$key} = $config;
		}

		if (!property_exists($this, 'default') || !is_array($this->default)) {
			throw new Exception('No `Email.Transports.default` array defined in core.php.');
		}
	}
}
