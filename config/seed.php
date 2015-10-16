<?php
/**
 * BasicSeed plugin data seed file, PRODUCTION data.
 */

namespace App\Config\BasicSeed;

// Define records.
$data = [
	//@TODO: Add PRODUCTION seed data for each table as migrations are created.
];

// Run the import process using the BasicSeedShell's helper method.
$this->importTables($data);


// Load additional data based on APP_ENV value.
$env = getenv('APP_ENV');
$this->hr();
$this->out("<info>Loading environment-specific data for APP_ENV={$env}...</info>");
$seed = $this->absolutePath("seed_{$env}.php");
if (file_exists($seed)) {
	$this->includeFile($seed);
}
