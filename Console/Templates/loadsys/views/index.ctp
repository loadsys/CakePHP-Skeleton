<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<?php
	$controllerUrl = Inflector::underscore($pluralVar);
	// Stub in some breadcrumbs.
	echo "<?php \$this->set('breadcrumbs', array(\n";
	echo "\t'{$pluralHumanName}' => array('controller' => '{$controllerUrl}', 'action' => 'index'),\n";
	echo ")); ?>\n";
?>

<div class="<?php echo $pluralVar; ?> index">
	<div class="pull-right">
		<?php
		echo "<?php echo \$this->TB->buttonLink(__('New {$singularHumanName}'),\n";
		echo "\t\t\tarray('controller' => '{$controllerUrl}', 'action' => 'add'),\n";
		echo "\t\t\t'info'\n";
		echo "\t\t); ?>\n";
		?>
	</div>

	<h2><?php echo "<?php echo __('{$pluralHumanName}'); ?>"; ?></h2>

	<table class="table table-striped table-hover table-condensed">
	<tr>
	<?php foreach ($fields as $field): ?>
	<th><?php echo "<?php echo \$this->Paginator->sort('{$field}'); ?>"; ?></th>
	<?php endforeach; ?>
	<th class="actions"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
	</tr>
	<?php
	echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
	echo "\t<tr>\n";
		foreach ($fields as $field) {
			$isKey = false;
			if (!empty($associations['belongsTo'])) {
				foreach ($associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
						echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
						break;
					}
				}
			}
			if ($isKey !== true) {
				echo "\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
			}
		}

		echo "\t\t<td class=\"actions\">\n";

		echo "\t\t\t<?php echo \$this->TB->buttonLink(__('View'),\n";
		echo "\t\t\t\tarray('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']),\n";
		echo "\t\t\t\tarray('style' => 'default', 'size' => 'xs')\n";
		echo "\t\t\t); ?>\n";

		echo "\t\t\t<?php echo \$this->TB->buttonLink(__('Edit'),\n";
		echo "\t\t\t\tarray('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']),\n";
		echo "\t\t\t\tarray('style' => 'info', 'size' => 'xs')\n";
		echo "\t\t\t); ?>\n";

		echo "\t\t\t<?php echo \$this->TB->buttonPost(__('Delete'),\n";
		echo "\t\t\t\tarray('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']),\n";
		echo "\t\t\t\tarray('style' => 'danger', 'size' => 'xs'),\n";
		echo "\t\t\t\t__('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])\n";
		echo "\t\t\t); ?>\n";

		echo "\t\t</td>\n";
	echo "\t</tr>\n";

	echo "\t<?php endforeach; ?>\n";
	?>
	</table>

	<?php echo "\t<?php echo \$this->element('Layouts/pagination'); ?>"; ?>
</div>
<?php if (count($associations)): ?>
<div class="actions">
	<h3><?php echo "<?php echo __('Actions'); ?>"; ?></h3>
	<ul>
<?php
	$done = array();
	foreach ($associations as $type => $data) {
		foreach ($data as $alias => $details) {
			if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
				echo "\t\t<li><?php echo \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "'), array('controller' => '{$details['controller']}', 'action' => 'index')); ?> </li>\n";
				echo "\t\t<li><?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add')); ?> </li>\n";
				$done[] = $details['controller'];
			}
		}
	}
?>
	</ul>
</div>
<?php endif; ?>
