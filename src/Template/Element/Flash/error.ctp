<?php
$class = 'message ' . basename(__FILE__, '.ctp');
if (!empty($params['class'])) {
	$class .= ' ' . $params['class'];
}
?>

<div data-alert class="alert-box alert <?= h($class) ?>">
	<?= h($message) ?>
	<a href="#" class="close">&times;</a>
</div>
