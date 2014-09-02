<?php
/**
 * DumpDbConfigShell
 *
 * @codeCoverageIgnore	There's no reason to test a diagnostic utility like this.
 */

App::import('Core', 'Security');
App::uses('AppShell', 'Console/Command');
App::uses('ConnectionManager', 'Model');
include_once 'Config/database.php';

/**
 * Quick and dirty Shell to help ensure that the database configuration is
 * honoring the proper `APP_ENV` environment variable value.
 */
class DumpDbConfigShell extends Shell {

	/**
	 * Models to load.
	 *
	 * @var array
	 */
	public $uses = array('User');

	/**
	 * Dumps the active database configuration.
	 *
	 * @access  public
	 * @return  void.
	 */
	public function main() {
		// $dataSource = ConnectionManager::getDataSource('default');
		// print_r($dataSource->config);

		print_r(new DATABASE_CONFIG());
		print_r($_SERVER);
	}
}
