<?php
/**
 * Test Classes for the Table Class.
 */

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
		$this->AppTable->initialize([]); // Have to call manually to get coverage.
		$this->assertEquals(
			'id',
			$this->AppTable->primaryKey(),
			'The [App]Table default primary key is expected to be `id`.'
		);

		$expectedAssociations = [
			'Creators',
			'Modifiers',
		];
		foreach ($expectedAssociations as $assoc) {
			$this->assertTrue(
				$this->AppTable->associations()->has($assoc),
				"Cursory sanity check. The [App]Table table is expected to be associated with $assoc."
			);
		}

		$expectedBehaviors = [
			'Timestamp',
			'CreatorModifier',
		];
		foreach ($expectedBehaviors as $behavior) {
			$this->assertTrue(
				$this->AppTable->behaviors()->has($behavior),
				"Cursory sanity check. The [App]Table table is expected to use the $behavior behavior."
			);
		}
	}

	/**
	 * Test validationDefault method
	 *
	 * @return void
	 */
	public function testValidationDefault() {
		$validator = new \Cake\Validation\Validator();
		$validator = $this->AppTable->validationDefault($validator);

		$this->assertTrue($validator->hasField('id'));
		$this->assertTrue($validator->hasField('created'));
		$this->assertTrue($validator->hasField('modified'));
		$this->assertTrue($validator->hasField('creator_id'));
		$this->assertTrue($validator->hasField('modifier_id'));
	}

	/**
	 * Test buildRules method
	 *
	 * @return void
	 */
	public function testBuildRules() {
		$this->assertInstanceOf(
			'\Cake\ORM\RulesChecker',
			$this->AppTable->buildRules(new \Cake\ORM\RulesChecker()),
			'Cursory sanity check. buildRules() should return a ruleChecker.'
		);
	}
}
