<?php
/**
 * Controller bake template file
 *
 * Allows templating of Controllers generated from bake.
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

echo "<?php\n";
?>
/**
 * <?php echo "$controllerName\n"; ?>
 */

<?php
echo "App::uses('{$plugin}AppController', '{$pluginPath}Controller');\n";
?>

/**
 * <?php echo $controllerName; ?> Controller
 */
class <?php echo $controllerName; ?>Controller extends <?php echo $plugin; ?>AppController {

<?php if ($isScaffold): ?>
	/**
	 * Scaffold
	 *
	 * @var	mixed
	 */
	public $scaffold;

<?php else: ?>
	/**
	 * Models
	 *
	 * @var	array
	 */
	//public $uses = array();

	/**
	 * Components
	 *
	 * @var	array
	 */
<?php
	if (count($components)):
		echo "\tpublic \$components = array(\n\t\t'";
		echo implode("',\n\t\t'", array_map('Inflector::camelize', $components));
		echo "',\n\t);\n";
	else:
		echo "\t//public \$components = array();\n";
	endif;
?>

	/**
	 * Helpers
	 *
	 * @var	array
	 */
<?php
	if (count($helpers)):
		echo "\tpublic \$helpers = array(\n\t\t'";
		echo implode("',\n\t\t'", array_map('Inflector::camelize', $helpers));
		echo "',\n\t);\n";
	else:
		echo "\t//public \$helpers = array();\n";
	endif;

	if (!empty($actions)) {
		echo "\n\t" . trim($actions) . "\n";
	}

endif; ?>
}
