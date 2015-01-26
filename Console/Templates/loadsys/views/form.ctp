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
	if (strpos($action, 'add') !== false) {
		$actionName = "'add'";
	} else {
		$actionName = "'edit', \$this->request->data['{$modelClass}']['{$primaryKey}']";
	}
	echo "<?php \$this->set('breadcrumbs', array(\n";
	echo "\t'{$pluralHumanName}' => array('controller' => '{$controllerUrl}', 'action' => 'index'),\n";
	printf("\t'%s %s' => array('controller' => '%s', 'action' => %s),\n", Inflector::humanize($action), $singularHumanName, $controllerUrl, $actionName);
	echo ")); ?>\n";
?>

<div class="<?php echo $pluralVar; ?> form">
<?php if (strpos($action, 'add') === false || count($associations)): ?>
	<div class="pull-right">
<?php endif; ?>
<?php if (count($associations)): ?>
	<div class="btn-group actions">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			<?php echo "<?php echo __('Actions'); ?>"; ?> <span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu">
<?php
			$done = array();
			foreach ($associations as $type => $data) {
				foreach ($data as $alias => $details) {
					if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
						echo "\t\t\t<li><?php echo \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "'), array('controller' => '{$details['controller']}', 'action' => 'index')); ?> </li>\n";
						echo "\t\t\t<li><?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add')); ?> </li>\n";
						$done[] = $details['controller'];
					}
				}
			}
?>
		</ul>
	</div>
<?php endif; ?>
<?php if (strpos($action, 'add') === false): ?>
		<?php
		echo "<?php echo \$this->TB->buttonPost(__('Delete'),\n";
		echo "\t\t\tarray('controller' => '{$controllerUrl}', 'action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')),\n";
		echo "\t\t\t'danger',\n";
		echo "\t\t\t__('Are you sure you want to delete # %s?', \$this->Form->value('{$modelClass}.{$primaryKey}'))\n";
		echo "\t\t); ?>\n";
		?>
<?php endif; ?>
<?php if (strpos($action, 'add') === false || count($associations)): ?>
	</div>
<?php endif; ?>
	<h2><?php printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></h2>
<?php
	echo "\t<?php echo \$this->Form->create('{$singularHumanName}', array(\n";
	echo "\t\t'class' => 'form-horizontal',\n";
	echo "\t\t'inputDefaults' => array(\n";
	echo "\t\t\t'div' => array(\n";
	echo "\t\t\t\t'class' => 'form-group',\n";
	echo "\t\t\t),\n";
	echo "\t\t\t'label' => array(\n";
	echo "\t\t\t\t'class' => 'col-sm-2 control-label',\n";
	echo "\t\t\t),\n";
	echo "\t\t\t'class' => 'form-control',\n";
	echo "\t\t\t'between' => '<div class=\"col-sm-10\">',\n";
	echo "\t\t\t'after' => '</div>',\n";
	echo "\t\t\t'error' => array(\n";
	echo "\t\t\t\t'class' => 'text-danger',\n";
	echo "\t\t\t),\n";
	echo "\t\t),\n";
	echo "\t)); ?>\n";
?>
<?php
		echo "\t<?php\n";

		echo "\t\t// Use the following for checkboxes and radios:\n";
		echo "\t\t\t// 'format' => array('before', 'between', 'input', 'label', 'after', 'error'),\n";
		echo "\t\t\t// 'between' => '<div class=\"col-sm-offset-2 col-sm-10\"><div class=\"checkbox\"><label>',\n";
		echo "\t\t\t// 'after' => '</label></div></div>',\n";
		echo "\t\t\t// 'label' => array('class' => false),\n";
		echo "\t\t\t// 'class' => false,\n";
		echo "\n";

		foreach ($fields as $field) {
			if (strpos($action, 'add') !== false && $field == $primaryKey) {
				continue;
			} elseif (!in_array($field, array('created', 'modified', 'updated', 'creator_id', 'modifier_id'))) {
				echo "\t\techo \$this->Form->input('{$field}', array(\n";
				echo "\t\t\t//'type' => 'text',\n";
				echo "\t\t\t//'label' => '',\n";
				echo "\t\t));\n";
			}
		}
		if (!empty($associations['hasAndBelongsToMany'])) {
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
				echo "\t\techo \$this->Form->input('{$assocName}', array(\n";
				echo "\t\t\t//'type' => 'text',\n";
				echo "\t\t\t//'label' => '',\n";
				echo "\t\t));\n";
			}
		}
		echo "\t?>\n";
?>
	<?php
		$verb = (strpos($action, 'add') !== false ? 'Create' : 'Save');
		echo "<?php echo \$this->Form->end(array(\n";
		echo "\t\t'label' => __('{$verb}'),\n";
		echo "\t\t'class' => 'btn btn-default',\n";
		echo "\t\t'div' => array(\n";
		echo "\t\t\t'class' => 'control-group col-sm-offset-2 col-sm-10',\n";
		echo "\t\t),\n";
		echo "\t)); ?>\n";
	?>
</div>
