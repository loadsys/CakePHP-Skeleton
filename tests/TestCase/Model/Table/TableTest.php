<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\Table Test Case
 */
class TableTest extends TestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [
	];

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		// Avoid naming conflicts, and make it explicit we're testing OUR Table class.
		$config = TableRegistry::exists('AppTable') ? [] : ['className' => 'App\Model\Table\Table'];
		$this->AppTable = TableRegistry::get('AppTable', $config);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->AppTable);

		parent::tearDown();
	}

	/**
	 * Test initialize method
	 *
	 * @return void
	 */
	public function testInitialize() {
		$this->markTestIncomplete('Not implemented yet.');
	}

	/**
	 * Test validationDefault method
	 *
	 * @return void
	 */
	public function testValidationDefault() {
		$this->markTestIncomplete('Not implemented yet.');
	}

	/**
	 * Test buildRules method
	 *
	 * @return void
	 */
	public function testBuildRules() {
		$this->markTestIncomplete('Not implemented yet.');
	}
}
