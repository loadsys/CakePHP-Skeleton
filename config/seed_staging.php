<?php
/**
 * BasicSeed plugin data seed file, STAGING data.
 */

namespace App\Config\BasicSeed;

// Staging should use the same data as vagrant.
$seed = $this->absolutePath('seed_vagrant.php');
if (file_exists($seed)) {
	$this->includeFile($seed);
}
