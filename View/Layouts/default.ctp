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
		echo $this->Html->meta(array('name' => 'env', 'content' => Configure::read('Defaults.env')));

		echo $this->element('Layouts/icons');
		echo $this->element('Layouts/social_meta_tags', array(
			'socialMetaTags' => $socialMetaTags,
			'description_for_layout' => $descriptionForLayout,
			'title_for_layout' => $title_for_layout
		));

		$localCssSources = array(
			'public',
		);
		echo $this->Html->css(array_merge(Configure::read('CDN.css'), $localCssSources));
	?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

	<?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->Html->envHint();
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
		$localScriptSources = array(
			'public',
		);
		echo $this->Html->script(
			array_merge(Configure::read('CDN.js'), $localScriptSources),
			array(
				'inline' => false
			)
		);

		echo $this->fetch('script');
		echo $this->Js->writeBuffer(array('onDomReady' => false, 'safe' => false));
		echo $this->element('Layouts/footer_scripts');
		//echo $this->element('sql_dump'); // Handled by DebugKit.
	?>
</body>
</html>
