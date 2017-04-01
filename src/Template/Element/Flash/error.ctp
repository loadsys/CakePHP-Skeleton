<?php
$class = 'message';
if (!empty($params['class'])) {
	$class .= ' ' . $params['class'];
}
if (!isset($params['escape']) || $params['escape'] !== false) {
	$message = h($message);
}
?>

<div data-alert class="alert-box alert <?= h($class) ?>">
	<?= $message ?>
	<a href="#" class="close">&times;</a>
</div>
