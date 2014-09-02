<?php
/**
 * AppModelFixture
 *
 * Used for testing the construct*() methods in AppModel.
 *
 */
class AppModelFixture extends CakeTestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'firstname' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lastname' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'role' => array('type' => 'string', 'null' => false, 'default' => 'user', 'length' => 50, 'collate' => 'utf8_general_ci', 'comment' => 'Roles field, mocked enum options are (admin, customer).', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = array(
		array(
			'id' => '52e00e3e-0210-41ce-b2ec-3e95b368309d',
			'username' => 'test@localhost.com',
			'password' => '$2a$10$XlTC8eH6X2DddLgM2RKm/eZZiILmIBTB0Zm3lJEzzbD/ZKLhGmdpW',
			'firstname' => 'Test',
			'lastname' => 'User',
			'email' => 'test@localhost.com',
			'active' => 1,
			'role' => 'admin',
		),
		array(
			'id' => '52f409e4-9120-43b7-a0db-7bf10a00020f',
			'username' => 'asdf@localhost.com',
			'password' => '$2a$10$XlTC8eH6X2DddLgM2RKm/eZZiILmIBTB0Zm3lJEzzbD/ZKLhGmdpW',
			'firstname' => 'Test',
			'lastname' => 'User',
			'email' => 'asdf@localhost.com',
			'active' => 1,
			'role' => 'admin',
		),
		array(
			'id' => '52f409e4-9120-41ce-a0bd-7bf10a00020f',
			'username' => 'deleteme@localhost.com',
			'password' => '$2a$10$XlTC8eH6X2DddLgM2RKm/eZZiILmIBTB0Zm3lJEzzbD/ZKLhGmdpW',
			'firstname' => 'Test',
			'lastname' => 'Delete',
			'email' => 'deleteme@localhost.com',
			'active' => 1,
			'role' => 'admin',
		),
		array(
			'id' => '14103dd1-18ff-11e4-ac33-000c299b1c12',
			'username' => 'expiredresetcode@localhost.com',
			'password' => '$2a$10$XlTC8eH6X2DddLgM2RKm/eZZiILmIBTB0Zm3lJEzzbD/ZKLhGmdpW',
			'firstname' => 'Expired',
			'lastname' => 'ResetCode',
			'email' => 'deleteme@localhost.com',
			'active' => 1,
			'role' => 'admin',
		),
		array(
			'id' => '4bd5686d-18ff-11e4-ac33-000c299b1c12',
			'username' => 'customerexpiredresetcode@localhost.com',
			'password' => '$2a$10$XlTC8eH6X2DddLgM2RKm/eZZiILmIBTB0Zm3lJEzzbD/ZKLhGmdpW',
			'firstname' => 'Customer',
			'lastname' => 'ExpiredResetCode',
			'email' => 'deleteme@localhost.com',
			'active' => 1,
			'role' => 'customer',
		),
		array(
			'id' => '7a1e963c-1904-11e4-ac33-000c299b1c12',
			'username' => 'futureresetcode@localhost.com',
			'password' => '$2a$10$XlTC8eH6X2DddLgM2RKm/eZZiILmIBTB0Zm3lJEzzbD/ZKLhGmdpW',
			'firstname' => 'Admin',
			'lastname' => 'FutureResetCode',
			'email' => 'futureresetcode@localhost.com',
			'active' => 1,
			'role' => 'admin',
		),
		array(
			'id' => '53d7e888-18fc-47b3-99ae-7e18c0a8d985',
			'username' => 'customer@localhost.com',
			'password' => '$2a$10$XlTC8eH6X2DddLgM2RKm/eZZiILmIBTB0Zm3lJEzzbD/ZKLhGmdpW',
			'firstname' => 'Testing',
			'lastname' => 'Customer',
			'email' => 'customer@localhost.com',
			'active' => 1,
			'role' => 'customer',
		),
		array(
			'id' => '4e18235e-181f-11e4-85e9-000c299b1c12',
			'username' => 'customer@localhost.com',
			'password' => '$2a$10$XlTC8eH6X2DddLgM2RKm/eZZiILmIBTB0Zm3lJEzzbD/ZKLhGmdpW',
			'firstname' => 'Searchretyhdfgh',
			'lastname' => 'Customer',
			'email' => 'emailsaearasdfasdf@localhost.com',
			'active' => 1,
			'role' => 'customer',
		),
	);

}
