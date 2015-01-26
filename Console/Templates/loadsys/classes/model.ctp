<?php
/**
 * Model template file.
 *
 * Used by bake to create new Model files.
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
 * @package       Cake.Console.Templates.default.classes
 * @since         CakePHP(tm) v 1.3
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('BakeTemplateHelper', 'Lib/Loadsys');

echo "<?php\n";
?>
/**
 * <?php echo "$name\n" ?>
 */

<?php
echo "App::uses('{$plugin}AppModel', '{$pluginPath}Model');\n";
?>

/**
 * <?php echo $name ?> Model
 */
class <?php echo $name ?> extends <?php echo $plugin; ?>AppModel {

<?php if ($useDbConfig !== 'default'): ?>
	/**
	 * Use database config
	 *
	 * @var string
	 */
	public $useDbConfig = '<?php echo $useDbConfig; ?>';

<?php endif;

if ($useTable && $useTable !== Inflector::tableize($name)):
	$table = "'$useTable'";
	echo "/**\n * Use table\n *\n * @var mixed False or table name\n */\n";
	echo "\tpublic \$useTable = $table;\n\n";
endif;

if ($primaryKey !== 'id'): ?>
	/**
	 * Primary key field
	 *
	 * @var string
	 */
	public $primaryKey = '<?php echo $primaryKey; ?>';

<?php endif;

if ($displayField): ?>
	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = '<?php echo $displayField; ?>';

<?php endif;

if (!empty($actsAs)): ?>
	/**
	 * Behaviors
	 *
	 * @var array
	 */
	public $actsAs = array(<?php
foreach ($actsAs as $behavior => $settings):
echo "\n\t\t";
if (is_string($behavior) && is_array($settings)) {
	echo "'{$behavior}' => " . BakeTemplateHelper::arrayToString($settings, 2);
} else {
	echo var_export($settings);
}
echo ",\n";
endforeach;
?>
	);
<?php endif;

if (!empty($validate)):
	$firstRule = true;
	echo "\t/**\n\t * Validation rules\n\t *\n\t * @var array\n\t */\n";
	echo "\tpublic \$validate = array(\n";
	foreach ($validate as $field => $validations):
		echo "\t\t'$field' => array(\n";
		foreach ($validations as $key => $validator):
			echo "\t\t\t'$key' => array(\n";
			echo "\t\t\t\t'rule' => array('$validator'),\n";
			echo "\t\t\t\t'message' => '@TO" . "DO: Validation message for `$name.$field` ($key) not defined.',\n"; // Strategically break the line in this template so we don't list this in phpdocs as a pending task.
			if ($firstRule) {
				$firstRule = false;
				echo "\t\t\t\t//'allowEmpty' => false,\n";
				echo "\t\t\t\t//'required' => false,\n";
				echo "\t\t\t\t//'last' => false, // Stop validation after this rule\n";
				echo "\t\t\t\t//'on' => 'create', // Limit validation to 'create' or 'update' operations\n";
			}
			echo "\t\t\t),\n";
		endforeach;
		echo "\t\t),\n";
	endforeach;
	echo "\t);\n";
endif;

foreach (array('belongsTo', 'hasOne') as $assocType):
	echo "\n\t/**\n\t * $assocType associations\n\t *\n\t * @var array\n\t */";
	if (!empty($associations[$assocType])):
		echo "\n\tpublic \$$assocType = array(";
		foreach ($associations[$assocType] as $i => $relation):
			$out = "\n\t\t'{$relation['alias']}' => array(\n";
			$out .= "\t\t\t'className' => '{$relation['className']}',\n";
			$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
			if ($i == 0) {
				$out .= "\t\t\t'conditions' => '',\n";
				$out .= "\t\t\t'fields' => '',\n";
				$out .= "\t\t\t'order' => '',\n";
			}
			$out .= "\t\t),";
			echo $out;
		endforeach;
		echo "\n\t);\n";
	else:
		echo "\n\tpublic \$$assocType = array();\n";
	endif;
endforeach;

echo "\n\t/**\n\t * hasMany associations\n\t *\n\t * @var array\n\t */";
if (!empty($associations['hasMany'])):
	echo "\n\tpublic \$hasMany = array(";
	foreach ($associations['hasMany'] as $i => $relation):
		$out = "\n\t\t'{$relation['alias']}' => array(\n";
		$out .= "\t\t\t'className' => '{$relation['className']}',\n";
		$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
		if ($i == 0) {
			$out .= "\t\t\t'dependent' => false,\n";
			$out .= "\t\t\t'conditions' => '',\n";
			$out .= "\t\t\t'fields' => '',\n";
			$out .= "\t\t\t'order' => '',\n";
			$out .= "\t\t\t'limit' => '',\n";
			$out .= "\t\t\t'offset' => '',\n";
			$out .= "\t\t\t'exclusive' => '',\n";
			$out .= "\t\t\t'finderQuery' => '',\n";
			$out .= "\t\t\t'counterQuery' => '',\n";
		}
		$out .= "\t\t),";
		echo $out;
	endforeach;
	echo "\n\t);\n";
else:
	echo "\n\tpublic \$hasMany = array();\n";
endif;

echo "\n\t/**\n\t * hasAndBelongsToMany associations\n\t *\n\t * @var array\n\t */";
if (!empty($associations['hasAndBelongsToMany'])):
	echo "\n\tpublic \$hasAndBelongsToMany = array(";
	foreach ($associations['hasAndBelongsToMany'] as $i => $relation):
		$out = "\n\t\t'{$relation['alias']}' => array(\n";
		$out .= "\t\t\t'className' => '{$relation['className']}',\n";
		$out .= "\t\t\t'joinTable' => '{$relation['joinTable']}',\n";
		$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
		$out .= "\t\t\t'associationForeignKey' => '{$relation['associationForeignKey']}',\n";
		if ($i == 0) {
			$out .= "\t\t\t'unique' => 'keepExisting',\n";
			$out .= "\t\t\t'conditions' => '',\n";
			$out .= "\t\t\t'fields' => '',\n";
			$out .= "\t\t\t'order' => '',\n";
			$out .= "\t\t\t'limit' => '',\n";
			$out .= "\t\t\t'offset' => '',\n";
			$out .= "\t\t\t'finderQuery' => '',\n";
		}
		$out .= "\t\t),";
		echo $out;
	endforeach;
	echo "\n\t);\n";
else:
	echo "\n\tpublic \$hasAndBelongsToMany = array();\n";
endif;
?>
}
