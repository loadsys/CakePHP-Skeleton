<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>
		<?php echo __('EU'); ?> -
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'));
		echo $this->element('Layouts/icons');

		if (Configure::read('CDN.enabled')) {
			$cssSources = array(
				'//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css',
				'//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css',
				'admin',
			);
		} else {
			$cssSources = array(
				'bootstrap-3.3.2/bootstrap.min',
				'bootstrap-3.3.2/bootstrap-theme.min',
				'admin',
			);
		}
		echo $this->Html->css($cssSources);
	?>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->Html->styleForEnv(Configure::read('Environment.APP_ENV'));
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
		if (Configure::read('CDN.enabled')) {
			$scriptSources = array(
				'//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js',
				'//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js',
				'admin',
			);
		} else {
			$scriptSources = array(
				'jquery-1.11.2/jquery.min',
				'bootstrap-3.3.2/bootstrap.min',
				'admin',
			);
		}
		echo $this->Html->script($scriptSources, array(
			'inline' => false
		));
		echo $this->fetch('script');
	?>
	<?php echo $this->Js->writeBuffer(); ?>
</body>
</html>
