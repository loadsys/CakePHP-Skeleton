<?php
/**
 * Default footer element
 */
use Cake\Core\Configure;
?>
<footer>
	<div class="row">

		<div class="small-5 columns">
			&copy; <?= date('Y'); ?> <?= Configure::read('Defaults.long_name'); ?>.
		</div>
		<div class="small-7 columns">
		<ul class="inline-list">
			<?= \Cake\Core\Configure::read('Defaults.Env.Hint.AuxContent') ?>

			<li>
				<?php echo $this->Html->link(__('Home'), [
					'controller' => 'Pages',
					'action' => 'display',
					'home',
				]) ?>
			</li>

		</ul>
		</div>
	</div>
</footer>
