<?php
/**
 * Default admin page layout.
 */

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
		echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'));
		echo $this->Html->meta(array('name' => 'env', 'content' => Configure::read('Defaults.env')));
		echo $this->element('Layouts/icons');

		$localCssSources = array(
			'admin',
		);
		echo $this->Html->css(array_merge(Configure::read('CDN.css'), $localCssSources));
	?>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->Html->envHint();
	?>
</head>

<body>
	<?php echo $this->element('Layouts/Admin/header'); ?>

	<?php echo $this->element('Layouts/Admin/breadcrumbs', compact('breadcrumbs')); ?>

	<div class="container">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->Session->flash('auth'); ?>
		<?php echo $this->fetch('content'); ?>
	</div>

	<?php //echo $this->element('Layouts/footer'); ?>

	<?php
		$localScriptSources = array(
			'admin',
		);
		echo $this->Html->script(
			array_merge(Configure::read('CDN.js'), $localScriptSources),
			array(
				'inline' => false
			)
		);
		echo $this->fetch('script');
	?>
	<?php echo $this->Js->writeBuffer(); ?>
</body>
</html>
