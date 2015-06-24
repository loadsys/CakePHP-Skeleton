<?php
use Cake\Cache\Cache;

if (!empty($_POST['v'])) {
    $sanitized = preg_replace('/[^a-zA-Z0-9 _-]/', '', $_POST['v']);
	Cache::write('test_key', $sanitized);
}
?>

<h2>Current Cache Value</h2>
<div class="row">
  <div class="large-6 columns">
    <p><?= Cache::read('test_key') ?> <a class="round success label" href="?">Reload</a></p>
  </div>
</div>

<h2>Set New Value</h2>
<form method="post">
  <div class="row collapse">
    <div class="row">
      <div class="large-6 columns">
        <div class="row collapse">
          <div class="small-10 columns">
            <input type="text" placeholder="New Value" name="v">
          </div>
          <div class="small-2 columns">
            <button type="submit" class="button postfix">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
