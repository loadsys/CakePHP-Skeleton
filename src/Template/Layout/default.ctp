<?php
/**
 * Default layout template, used for the public facing portion of the application
 */

use App\Lib\ConfigClosures;
use Cake\Core\Configure;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?= $this->Html->charset() ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>
		<?= Configure::read('Defaults.long_name') ?> &ndash;
		<?= $this->fetch('title') ?>
	</title>

	<?= $this->Html->meta(
		'keywords',
		$this->fetch('keywords', Configure::read('Defaults.meta_keywords'))
	) ?>

	<?= $this->Html->meta(
		'description',
		$this->fetch('description', Configure::read('Defaults.meta_description'))
	) ?>

	<?= $this->Html->meta('icon') ?>

	<?= $this->Html->css([
		'build/app',
	]) ?>

	<?= $this->Html->script([
		'vendor/modernizr',
	]) ?>

	<?= $this->fetch('social_meta') ?>
	<?= $this->fetch('meta') ?>
	<?= $this->fetch('css') ?>
	<?= ConfigClosures::styleForEnv() ?>
</head>
<body>
	<?= $this->element('Layout/navigation') ?>

	<div id="container">

		<div id="content">
			<?= $this->element('Layout/breadcrumbs', [
				'currentPage' => null
			]) ?>

			<div class="row">
				<?= $this->Flash->render() ?>
				<?= $this->Flash->render('auth') ?>
			</div>

			<div class="row">
				<?= $this->fetch('content') ?>
			</div>
		</div>

		<?= $this->element('Layout/footer'); ?>
	</div>

	<?= $this->Html->script([
		'build/scripts.min',
	]) ?>

	<?= $this->fetch('script') ?>
</body>
</html>
