<?php
use Cake\Cache\Cache;

if (!empty($_GET['v'])) {
    $sanitized = preg_replace('/[^a-zA-Z0-9 _-]/', '', $_GET['v']);
	Cache::write('test_key', $sanitized);
}
?>

<h2>Current Cache Value</h2>
<p><?= Cache::read('test_key') ?></p>

<h2>Set New Value</h2>
<form>
    <input id="v">
</form>
