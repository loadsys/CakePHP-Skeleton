<?php
/**
 * BasicSeed plugin data seed file, DEVELOPMENT data.
 */

namespace App\Config\BasicSeed;

// Define records. Use "evergreen" dates like `new \DateTime('+1 year 2 month')`
// to make refreshing dev data as easy as re-seeding.
$data = [

	//@TODO: Add DEVELOPMENT seed data for each table as migrations are created. In the default configuration, PRODUCTION data will also be loaded. Additionally, DEVELOPMENT + PRODUCTION will also be used for STAGING.
	/*
	'Users' => [
		'_truncate' => true,
		'_entityOptions' => [
			'validate' => false,
		],
		'_saveOptions' => [
			'checkRules' => false,
		],
		'_defaults' => [
			'password' => '', // "@TODO: Manually encrypt a password to save here."
			'is_active' => 1,
			'creator_id' => null,
			'modifier_id' => null,
		],
		[
			'id' => '799763fd-32bc-11e4-9e39-080027506c76',
			'email' => 'admin@localhost.com',
			'role' => 'admin',
		],
	],
	*/

];

$this->importTables($data);
