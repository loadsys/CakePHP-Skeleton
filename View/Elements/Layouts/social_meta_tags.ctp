<?php
/**
 * Expects:
 *   @param array   $socialMetaTags          An array of metatag information
 *   @param string  $description_for_layout  A string description for this page/layout
 *   @param string  $title_for_layout        A string title for this page/layout
 *
 */

$metatags = array();

// Generic Open Graph Tags
$metatags['og:type'] = (!empty($socialMetaTags['type']) ? $socialMetaTags['type'] : 'website');
$metatags['og:title'] = $title_for_layout;
$metatags['og:url'] = Router::url(null, true);
$metatags['og:site_name'] = __('Education Unlimited');
$metatags['og:description'] = $description_for_layout;
$metatags['og:updated_time'] = (!empty($socialMetaTags['modified_time']) ? date('c', strtotime($socialMetaTags['modified_time'])) : null);
$metatags['og:locale'] = Configure::read('Config.language');

// Open Graph Image Tags
$metatags['og:image'] = (!empty($socialMetaTags['image']['url']) ? Router::url($socialMetaTags['image']['url'], true) : null);
$metatags['og:image:width'] = (!empty($socialMetaTags['image']['width']) ? Router::url($socialMetaTags['image']['width'], true) : null);
$metatags['og:image:height'] = (!empty($socialMetaTags['image']['height']) ? Router::url($socialMetaTags['image']['height'], true) : null);

// Article Open Graph Tags
$metatags['article:published_time'] = (!empty($socialMetaTags['published_time']) ? date('c', strtotime($socialMetaTags['published_time'])) : null);
$metatags['article:modified_time'] = (!empty($socialMetaTags['modified_time']) ? date('c', strtotime($socialMetaTags['modified_time'])) : null);
$metatags['article:expiration_time'] = (!empty($socialMetaTags['expiration_time']) ? date('c', strtotime($socialMetaTags['expiration_time'])) : null);

// Facebook Specific Tags

// Verify that we have a Facebook Profile ID Config Value Setup
if(Configure::check('SocialNetworks.Facebook.profile_id')) {
	$metatags['fb:profile_id'] = Configure::read('SocialNetworks.Facebook.profile_id');
}

// Twitter Specific Tags
$metatags['twitter:card'] = (!empty($socialMetaTags['twitter']['card']) ? $socialMetaTags['twitter']['card'] : 'summary');
$metatags['twitter:title'] = $title_for_layout;
$metatags['twitter:description'] = $description_for_layout;

// Twitter Image Tags
$metatags['twitter:image'] = (!empty($socialMetaTags['image']['url']) ? Router::url($socialMetaTags['image']['url'], true) : null);
$metatags['twitter:image:width'] = (!empty($socialMetaTags['image']['width']) ? Router::url($socialMetaTags['image']['width'], true) : null);
$metatags['twitter:image:height'] = (!empty($socialMetaTags['image']['height']) ? Router::url($socialMetaTags['image']['height'], true) : null);

// Verify that we have a Twitter Username Config Value Setup
if(Configure::check('SocialNetworks.Twitter.username')) {
	$metatags['twitter:site'] = Configure::read('SocialNetworks.Twitter.username');
}

// Loop through and display the MetaTag Content
foreach($metatags as $metatagProperty => $metatagContent) {
	if(isset($metatagContent) && !empty($metatagContent)){
		echo $this->Html->meta(array('property' => trim($metatagProperty), 'content' => $metatagContent));
	}
}
