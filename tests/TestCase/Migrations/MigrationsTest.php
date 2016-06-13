<?php
/**
 * Migrations tests.
 *
 * Checks each migration file for specific characteristics. This is really
 * more of a code-sniff style check, but it's in place to help ensure the
 * quality of the DB schemas we design and deploy.
 */

namespace App\Test\TestCase\Migrations;

use Cake\TestSuite\TestCase;
use Cake\Utility\Inflector;
use Phinx\Db\Table;
use Phinx\Migration\AbstractMigration;
use \AppendIterator;
use \FilesystemIterator as FSI;
use \GlobIterator;

/**
 * Migrations folder iterator.
 *
 * Returns each file in the Migrations folder as an array of relevant
 * info. Used to seed the dataProvider for testMigrationFile().
 */
class MigrationFolderIterator extends GlobIterator {
	public function current() {
		$fileInfo = parent::current();
		$realpath = $fileInfo->getRealPath();
		$filename = $fileInfo->getBasename();
		list($version, $underscoredName) = explode('_', $filename, 2);
		$classname = Inflector::camelize(basename($underscoredName, '.php'));

		// Determine the plugin name the Migration file originates from.
		$segments = explode(DIRECTORY_SEPARATOR, $realpath);
		$isPlugin = array_search('plugins', $segments);
		$plugin = ($isPlugin ? $segments[$isPlugin + 1] : null);

		//@DEBUG: return compact('filename', 'realpath', 'classname', 'version', 'plugin');
		return [$realpath, $classname, $version, $plugin];
	}
}

/**
 * Migrations test cases
 */
class MigrationsTest extends TestCase {
	/**
	 * The Mocked Table instance being operated on.
	 *
	 * @var mixed
	 */
	public $table = null;

	/**
	 * The table name being operated on.
	 *
	 * @var string
	 */
	public $tableName = null;

	/**
	 * Array of properties for each table being processed.
	 *
	 * @var array
	 */
	public $tableProperties = [];

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->migration, $this->table, $this->tableName, $this->tableProperties);
		parent::tearDown();
	}

	/**
	 * Test an individual migration file.
	 *
	 * Migrations must conform to a set of constraints:
	 *
	 *    - All tables and columns must include a non-empty [comment] key.
	 *
	 * @param string $filename The full filesystem path to the Migration file to load.
	 * @param string $classname The inferred (inflected) name of the Migration class.
	 * @param string $version The inferred version number of the Migration, taken
	 *    from the timestamp in the filename.
	 * @param string $plugin The inferred plugin name from which the Migration
	 *    file originates, if any.
	 * @return void
	 * @dataProvider provideMigrationFiles
	 */
	public function testMigrationFile($filename, $classname, $version, $plugin = null) {
		require_once $filename;
		$this->mockMigration($classname, $version);

		// Trigger the up() and/or change() methods. (The mocks provide the assertions.)
		$this->migration->up();
		if (is_callable([$this->migration, 'change'])) {
			$this->migration->change();
		}
	}

	/**
	 * Scan the Migrations folder and return arrays of file and class names to test.
	 *
	 * @return void
	 */
	public function provideMigrationFiles() {
		$basePaths = [
			ROOT . '/config/Migrations/*.php', // main app
			ROOT . '/plugins/*/config/Migrations/*.php', // plugins
		];

		$it = new AppendIterator();
		foreach ($basePaths as $path) {
			$it->append(new MigrationFolderIterator($path, FSI::KEY_AS_FILENAME));
		}

		return $it;
	}

	/**
	 * Return an array of method names available in $class, except for those in $excluded.
	 *
	 * Used when mocking the individual migration classes to mock "everything
	 * except up(), down() and change()".
	 *
	 * @return array
	 */
	protected function getClassMethodsExcept($class, array $excluded) {
		return array_diff(get_class_methods($class), $excluded);
	}

	/**
	 * Mock the migration class to spy on schema change methods.
	 *
	 * @return mixed
	 */
	protected function mockMigration($class, $version) {
		// The mocked Table object must enforce some constraints its columns.
		$this->table = $this->getMock('Phinx\Db\Table', [], [], '', false);
		$this->table->expects($this->any())
			->method('create')
			->will($this->returnCallback([$this, 'assertTableOptions']));
		$this->table->expects($this->any())
			->method($this->anything())
			->will($this->returnCallback([$this, 'assertOptions']));

		// The Migration just needs to return an instance of the mocked Table.
		$methods = $this->getClassMethodsExcept($class, ['up', 'down', 'change']);
		$this->migration = $this->getMock($class, $methods, [$version]);
		$this->migration->expects($this->any())
			->method('table')
			->will($this->returnCallback([$this, 'collectTableProperties']));
	}

	/**
	 * Collect the Table name and other properties.
	 *
	 * @return mixed Returns the table instance for the fluid interface.
	 */
	public function collectTableProperties() {
		$args = func_get_args();
		$this->tableName = $args[0];
		$this->tableProperties = (count($args) > 1 ? $args[1] : []);
		return $this->table; // Preserve the fluent interface of the mocked Table object.
	}

	/**
	 * Callback method to assert that a table Migration call when called from
	 * create has a subset of options. It is used to provide the correct
	 * parameters to assertOptions.
	 *
	 * @return void
	 */
	public function assertTableOptions() {
		$this->assertOptions($this->tableName, $this->tableProperties);
	}

	/**
	 * returnCallback() function used by mocked methods.
	 *
	 * Used as the return value callback function in the mocked
	 * AbstractMigration::table() and Table::addColumn() methods to verify
	 * the arguments passed to those methods.
	 *
	 * @return mixed
	 */
	public function assertOptions() {
		$args = func_get_args();

		// @codingStandardsIgnoreStart
		$calledFunctionName = @debug_backtrace(null)[5]['function'];
		// @codingStandardsIgnoreEnd

		// Handle the pass-thru from ::assertTableOptions() specially.
		if ($calledFunctionName == 'invoke') {
			$calledFunctionName = 'table';
		}

		switch ($calledFunctionName) {
			case 'table':
				$options = $this->tableProperties;
				$this->assertKeyNotEmpty('comment', $options, $this->tableName, $calledFunctionName, null);
				break;

			case 'addColumn':
				list($fieldName, $fieldType, $options) = $args;
				$this->assertKeyNotEmpty('comment', $options, $this->tableName, $calledFunctionName, $fieldName);
				$this->assertHasKey('default', $options, $this->tableName, $calledFunctionName, $fieldName);
				$this->assertHasKey('null', $options, $this->tableName, $calledFunctionName, $fieldName);
				$this->assertHasKey('limit', $options, $this->tableName, $calledFunctionName, $fieldName);

				if (is_callable([$this, "assert{$fieldType}"])) {
					call_user_func(
						[$this, "assert{$fieldType}"],
						$options,
						$this->tableName,
						$calledFunctionName,
						$fieldName
					);
				}
				break;

			default:
				//$this->markTestSkipped('Nothing found to test.'); // Useful for debugging the spies.
				break;
		}

		return $this->table; // Preserve the fluent interface of the mocked Table object.
	}

	/**
	 * Custom assertion to ensure the given array has a [key] element.
	 *
	 * @param string $key The key to assert exists.
	 * @param array $array An array, typically options for the migration method,
	 *    obtained from the mocked call to table() or addColumn(), etc. in the
	 *    Migration file.
	 * @param string $table The name of the "current" table the Migration file
	 *    is operating on. This is stateful given the fluent nature of phinx
	 *    migrations. Set by ::assertOptions(). Used to provide more accurate
	 *    assertion failure messages.
	 * @param string $method The name of the current method being called in the
	 *    migration file. Used to provide more accurate assertion failure messages.
	 * @param string $field The name of the field being modified by the migration.
	 *    Context-sensitive. Used to provide more accurate assertion failure
	 *    messages.
	 * @return void
	 */
	public function assertHasKey($key, $array, $table, $method, $field) {
		$signature = "table({$table}" . ($method == 'table' ? '' : ")->{$method}({$field}") . ', [options])';
		$this->assertArrayHasKey(
			$key,
			$array,
			"{$signature}: must contain a [$key] element."
		);
	}

	/**
	 * Custom assertion to ensure the given array has a non-empty [key] element.
	 *
	 * @param string $key The key to assert exists and is not empty.
	 * @param array $array An array, typically options for the migration method,
	 *    obtained from the mocked call to table() or addColumn(), etc. in the
	 *    Migration file.
	 * @param string $table The name of the "current" table the Migration file
	 *    is operating on. This is stateful given the fluent nature of phinx
	 *    migrations. Set by ::assertOptions(). Used to provide more accurate
	 *    assertion failure messages.
	 * @param string $method The name of the current method being called in the
	 *    migration file. Used to provide more accurate assertion failure messages.
	 * @param string $field The name of the field being modified by the migration.
	 *    Context-sensitive. Used to provide more accurate assertion failure
	 *    messages.
	 * @return void
	 */
	public function assertKeyNotEmpty($key, $array, $table, $method, $field) {
		$signature = "table({$table}" . ($method == 'table' ? '' : ")->{$method}({$field}") . ', [options])';
		$this->assertHasKey($key, $array, $table, $method, $field);
		$this->assertNotEmpty(
			$array[$key],
			"{$signature}: [$key] must be non-empty."
		);
	}

	/**
	 * Custom assertion to ensure that DECIMAL field types defined both a [precision] and a [scale].
	 *
	 * @param array $options An array of options obtained from the mocked call
	 *    to table() or addColumn(), etc. in the Migration file.
	 * @param string $table The name of the "current" table the Migration file
	 *    is operating on. This is stateful given the fluent nature of phinx
	 *    migrations. Set by ::assertOptions(). Used to provide more accurate
	 *    assertion failure messages.
	 * @param string $method The name of the current method being called in the
	 *    migration file. Used to provide more accurate assertion failure messages.
	 * @param string $field The name of the field being modified by the migration.
	 *    Context-sensitive. Used to provide more accurate assertion failure
	 *    messages.
	 * @return void
	 */
	public function assertDecimal($options, $table, $method, $field) {
		$signature = "table({$table}" . ($method == 'table' ? '' : ")->{$method}({$field}") . ', [options])';
		$this->assertArrayHasKey(
			'precision',
			$options,
			"{$signature}: must contain a [precision] element."
		);
		$this->assertArrayHasKey(
			'scale',
			$options,
			"{$signature}: must contain a [scale] element."
		);
		if (array_key_exists('limit', $options)) {
			$this->assertEmpty(
				$options['limit'],
				"{$signature}: [limit] element on DECIMAL fields must be null."
			);
		}
	}

	/**
	 * Custom assertion to ensure that TINYINT(1) (boolean) field types define an [unsigned => true] option.
	 *
	 * @param array $options An array of options obtained from the mocked call
	 *    to table() or addColumn(), etc. in the Migration file.
	 * @param string $table The name of the "current" table the Migration file
	 *    is operating on. This is stateful given the fluent nature of phinx
	 *    migrations. Set by ::assertOptions(). Used to provide more accurate
	 *    assertion failure messages.
	 * @param string $method The name of the current method being called in the
	 *    migration file. Used to provide more accurate assertion failure messages.
	 * @param string $field The name of the field being modified by the migration.
	 *    Context-sensitive. Used to provide more accurate assertion failure
	 *    messages.
	 * @return void
	 */
	public function assertBoolean($options, $table, $method, $field) {
		$signature = "table({$table}" . ($method == 'table' ? '' : ")->{$method}({$field}") . ', [options])';
		$this->assertArrayHasKey(
			'signed',
			$options,
			"{$signature}: must contain a [signed] element."
		);
		$this->assertFalse(
			$options['signed'],
			"{$signature}: [signed] must be false for boolean fields."
		);
	}
}
