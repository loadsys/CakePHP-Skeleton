<?php
/**
 * Default public page layout.
 */

if (empty($keywordsForLayout)) {
	$keywordsForLayout = __('default, keywords');
}
if (empty($descriptionForLayout)) {
	$descriptionForLayout = __('Default description.');
}
if (!isset($socialMetaTags)) {
	$socialMetaTags = array();
}

?><!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo Configure::read('Defaults.long_name'); ?> -
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta(array('http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge'));
		echo $this->Html->meta(array('name' => 'keywords', 'content' => $keywordsForLayout));
		echo $this->Html->meta(array('name' => 'description', 'content' => $descriptionForLayout));
		echo $this->Html->meta(array('name' => 'canonical', 'content' => Router::url(null, true)));
		echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'));

		echo $this->element('Layouts/icons');
		echo $this->element('Layouts/social_meta_tags', array(
			'socialMetaTags' => $socialMetaTags,
			'description_for_layout' => $descriptionForLayout,
			'title_for_layout' => $title_for_layout
		));

		if (Configure::read('CDN.enabled')) {
			$cssSources = array(
				'//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css',
				'//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css',
				'global',
				'public',
			);
		} else {
			$cssSources = array(
				'bootstrap-3.3.2/bootstrap.min',
				'bootstrap-3.3.2/bootstrap-theme.min',
				'global',
				'public',
			);
		}
		echo $this->Html->css($cssSources);
	?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

	<?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
		//echo $this->Blah->styleForEnv(Configure::read('Environment.APP_ENV'));
	?>
</head>

<body>
	<div class="container">

		<?php echo $this->element('Layouts/header'); ?>

		<main id="main" role="main">
			<?php echo $this->element('Layouts/breadcrumbs', array(
				'currentPage' => $title_for_layout
			)); ?>
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->Session->flash('auth'); ?>
			<?php echo $this->fetch('content'); ?>
		</main>

	</div> <!-- /container -->

	<?php echo $this->element('Layouts/footer'); ?>

	<?php
		if (Configure::read('CDN.enabled')) {
			$scriptSources = array(
				'//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js',
				'//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js',
				'public',
			);
		} else {
			$scriptSources = array(
				'jquery-1.11.2/jquery.min',
				'bootstrap-3.3.2/bootstrap.min',
				'public',
			);
		}
		echo $this->Html->script($scriptSources, array(
			'inline' => false
		));

		echo $this->fetch('script');
		echo $this->Js->writeBuffer(array('onDomReady' => false, 'safe' => false));
		echo $this->element('Layouts/footer_scripts');
		//echo $this->element('sql_dump'); // Handled by DebugKit.
	?>
</body>
</html>
