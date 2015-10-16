<?php
/**
 * Expects:
 *   @param array $breadcrumbs An array of ['Title' => url] pairs. The URLs can be Cake routing arrays. Will be displayed **in order**.
 */

if (!isset($breadcrumbs) || !is_array($breadcrumbs) || count($breadcrumbs) === 0) {
	return '';
}

// Auto prepend "Home" if it doesn't look like one was included.
if (!isset($breadcrumbs['Home'])) {
	$this->Html->addCrumb(__('Home', true), '/');
}

foreach($breadcrumbs as $breadcrumbTitle => $breadcrumbUrl) {
	$this->Html->addCrumb(__($breadcrumbTitle, true), $breadcrumbUrl);
}

?>

<div class="row">
<?php echo $this->Html->getCrumbList(
	[
		'firstClass' => false,
		'lastClass' => 'current',
		'class' => 'breadcrumbs',
		'role' => 'menubar'
	]
);
?>
</div>
