<?php
/**
 * TreeCheckShell
 *
 * @codeCoverageIgnore This is a quick and dirty script for hand verification of CSV data, not integral to app operation.
 * @TODO: Write tests anyway.
 */

App::uses('AppShell', 'Console/Command');
App::uses('ConnectionManager', 'Model');
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');
App::uses('TreeCsvIterator', 'Lib/Iterators');

/**
 * TreeCheck Model. Used by the TreeCheckShell and the `DB_CONFIG::$memory`
 * connection to import a CSV file into a temporary (memory only) Model to
 * be checked by the TreeBehavior.
 */
class TreeCheck extends AppModel {

	/**
	 * Database config to use.
	 *
	 * @var string
	 */
	public $useDbConfig = 'memory';

	/**
	 * Database table name.
	 *
	 * @var string
	 */
	public $useTable = 'tree_checks';

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'name';

	/**
	 * Behaviors
	 *
	 * @var array
	 */
	public $actsAs = array(
		'Tree',
	);

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'id' => array(
			'rule' => array('numeric'),
			'message' => 'The primary ID must be a positive integer.',
		),
		'parent_id' => array(
			'rule' => array('numeric'),
			'message' => 'The parent ID must be a positive integer (if not null).',
			'allowEmpty' => true,
		),
		'name' => array(
			'rule' => array('notEmpty'),
			'message' => 'The name must not be empty.',
		),
	);
}

/**
 * TreeCheckShell. Consumes a csv file containing at least [id, parent_id, name] fields
 * and build a TreeBehavior "table" out of it, then prints it as a nested
 * list to help visualize the hierarchy.
 */
class TreeCheckShell extends Shell {

	/**
	 * Models to load.
	 *
	 * @var array
	 */
	public $uses = array(
		'TreeCheck', // Loaded inline above.
	);

	/**
	 * The name to use when creating the in-memory table and attaching the
	 * Model to it.
	 *
	 * @var string
	 */
	protected $_inMemoryTableName = 'tree_checks';

	/**
	 * The ID to use for the catch-all recovery record inserted into the table.
	 *
	 * @var string
	 */
	protected $_catchallRecordId = 10000;

	/**
	 * getOptionParser
	 *
	 * Define command line options for automatic processing and enforcement.
	 * Also provides documentation via the `-h` flag.
	 *
	 * @codeCoverageIgnore		This is Cake setup and doesn't need to be tested.
	 * @access	public
	 * @return	mixed
	 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser
			->description(__('Loads the provided CSV file and immediately dumps it as a nested list using the TreeBehavior.'))
			->addArgument('csv', array(
				'index' => 0,
				'help' => __('Specify the full path to the TreeBehavior formatted CSV file to process. Expected fields, in order: [id, parent_id, name]'),
				'required' => true,
			));

		return $parser;
	}

	/**
	 * Create the table in the sqlite memory DB before the model is
	 * instantiated against it.
	 *
	 * @access  public
	 * @return  void
	 */
	public function startup() {
		$this->dataSource = ConnectionManager::getDataSource('memory');
		$this->dataSource->cacheSources = false;

		$this->dataSource->query("
			DROP TABLE IF EXISTS {$this->_inMemoryTableName};
		");
		$this->dataSource->query("
			CREATE TABLE IF NOT EXISTS {$this->_inMemoryTableName}(
				id INT,
				parent_id INT DEFAULT NULL,
				lft INT DEFAULT NULL,
				rght INT DEFAULT NULL,
				name,
				PRIMARY KEY(id ASC)
			);
		") || $this->error('Unable to create temp table.');
		$this->dataSource->query("
			INSERT INTO {$this->_inMemoryTableName}
			VALUES ({$this->_catchallRecordId}, NULL, NULL, NULL, '(Reserved)');
		");
	}

	/**
	 * Eats the provided csv file and outputs a visual nested list.
	 *
	 * @access  public
	 * @return  void
	 */
	public function main() {
		list($csvPath) = $this->parseArgs();

		// Populate the temp table.
		$this->loadRecordsToModel($csvPath);

		// Repair the MPTT tree.
		$this->repairTree();

		// Build and dump the list.
		$this->printTree();

		$this->out("Tree is valid.", 1, Shell::NORMAL);
	}

	/**
	 * Process command line arguments and return them as an indexed array.
	 * Currently just $csvPath.
	 *
	 * @access  protected
	 * @return  array	An indexed array of options suitable for use with `list()`. **Order matters!**
	 */
	protected function parseArgs() {
		$csvPath = $this->args[0];
		return array(
			$csvPath,
		);
	}

	/**
	 * Load the records from the CSV file into the model. Will output progress
	 * to the terminal as a side-effect if shell verbosity is high enough.
	 *
	 * @access	protected
	 * @param	string	$csvPath The full filesystem path to the CSV fil to load.
	 * @return	void
	 */
	protected function loadRecordsToModel($csvPath) {
		foreach (new TreeCsvIterator($csvPath) as $row) {
			if ($row['id'] == 0) {
				continue;
			}
			$row['parent_id'] = (strtolower($row['parent_id']) === 'null' ? null : $row['parent_id']);
			$row = array('TreeCheck' => $row);
			if ($result = $this->TreeCheck->save($row)) {
				$this->out("Saved id `{$row['TreeCheck']['id']}`.", 1, Shell::VERBOSE);
			} else {
				$this->out("<warning>Failed to save id `{$row['TreeCheck']['id']}`.</warning>", 1, Shell::NORMAL);
			}
		}
	}

	/**
	 * Runs a `TreeBehavior::recover()` followed by `::verify()` on the
	 * Model to confirm it is fully complete and error free. Reports any
	 * errors and exits the shell prematurely if any are found.
	 *
	 * @access  protected
	 * @return  void
	 */
	protected function repairTree() {
		$this->TreeCheck->recover('parent', $this->_catchallRecordId) || $this->error('Failed to repair the MPTT tree before display.');
		$verify = $this->TreeCheck->verify();
		if (is_array($verify)) {
			$this->out(print_r($verify, true), 1, Shell::VERBOSE);
			$this->error('Verification of tree failed.');
		}
	}

	/**
	 * Generates and prints the full tree to the console.
	 *
	 * @access  protected
	 * @return  void
	 */
	protected function printTree() {
		$tree = $this->TreeCheck->generateTreeList(
			null,
			'{n}.TreeCheck.id',
			'{n}.TreeCheck.name',
			'|   ',
			null
		);
		array_walk($tree, array($this, 'printLine'));
	}

	/**
	 * Intended to be provided to `array_walk()` to output each line
	 * from `generareTreeList()`.
	 *
	 * @access  public
	 * @param	string	$name	The formatted Model.name field to print indented.
	 * @param	string	$id	The Model.id field to print in the leading column.
	 * @return  void
	 */
	public function printLine($name, $id) {
		$format = "%1$ 5d\t%2\$s";
		$msg = sprintf($format, $id, $name);
		$this->out($msg, 1, Shell::NORMAL);
	}
}
