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
	$this->TB->addCrumb(
		__('Home', true),
		array(
			'controller' => 'pages',
			'action' => 'display',
			'dashboard',
			'admin' => true,
		)
	);
}

foreach($breadcrumbs as $breadcrumbTitle => $breadcrumbUrl) {
	$this->TB->addCrumb(__($breadcrumbTitle, true), $breadcrumbUrl);
}

echo '<div class="container">';
echo $this->TB->getCrumbs(null, false);
echo '</div>';
