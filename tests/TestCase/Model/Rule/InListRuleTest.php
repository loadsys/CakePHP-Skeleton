<?php
/**
 * Test class for the InListRule
 */
namespace App\Test\TestCase\Model\Rule;

use App\Model\Rule\InConfigureListRule;
use App\Model\Table\UsersTable;
use Cake\ORM\Entity as User;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * \App\Test\TestCase\Model\Rule\TestInConfigureListRule
 *
 * Class to override protected properties for InConfigureListRule
 */
class TestInListRule extends InListRule {
	public $field;
}

/**
 * \App\Test\TestCase\Model\Rule\InListRuleTest
 */
class InListRuleTest extends TestCase {
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [
		//'app.users',
	];

	/**
	 * Store the name of the field to operate on in tests.
	 *
	 * @var string
	 */
	public $field = 'role';

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->Rule = new TestInListRule($this->field);
		$this->User = new User();
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->Rule);
		unset($this->User);

		parent::tearDown();
	}

	/**
	 * test the __construct method
	 *
	 * @return void
	 */
	public function testConstruct() {
		$this->assertEquals(
			$this->field,
			$Rule->field,
			'The field value of the property should be equal to the passed in construct argument.'
		);
	}

	/**
	 * test the __invoke method on no repository set.
	 *
	 * @return void
	 */
	public function testInvokeOnNoRepositoryPassed() {
		$this->setExpectedException(
			'InvalidArgumentException',
			'Options requires a repository key to be passed to run this rule.'
		);
		$this->Rule($this->User, []);
	}

	/**
	 * test the __invoke method
	 *
	 * @param string $field The field name to test.
	 * @param array $config The config params for InConfigureListRule.
	 * @param string $value The value of the field to check against.
	 * @param bool $expected The expected output from __invoke.
	 * @param string $msg The PHPUnit error message.
	 * @return void
	 * @dataProvider providerInvoke
	 */
	public function testInvoke($field, $config, $value, $expected, $msg = '') {
		$options = [];
		$options['repository'] = $this->getMock('Cake\ORM\Table');

		$this->Rule = new InListRule($field, $config);
		$this->User->{$this->field} = $value;

		$this->assertEquals(
			$expected,
			$InConfigureListRule($this->User, $options),
			$msg
		);
	}

	/**
	 * DataProvider for testInvoke.
	 *
	 * @return array Data inputs for testInvoke.
	 */
	public function providerInvoke() {
		return [
			'Role that is available in Config' => [
				$this->field,
				[],
				'admin',
				true,
				'On a valid role, should return true.',
			],
			'Empty String role value' => [
				$this->field,
				[],
				'',
				false,
				'On an empty role value, should return false.',
			],
			'Role that is not available in Config' => [
				$this->field,
				[],
				'not-real-value',
				false,
				'One a role that is not available in Config, should return false.',
			],
			'Field that is not available in Config' => [
				'not-real-field',
				[],
				'not-real-value',
				false,
				'One a field that is not available in Config, should return false.',
			],
			'Custom Path that is not available in Config' => [
				$this->field,
				['configPath' => 'Custom.path.something'],
				'admin',
				false,
				'On an invalid path, should return false.',
			],
			'Allow null and pass null' => [
				$this->field,
				['allowNulls' => true],
				null,
				true,
				'On allowing nulls and setting null, should return true.',
			],
			'Disallow null and pass null' => [
				$this->field,
				[],
				null,
				false,
				'On disallowing nulls and setting null, should return false.',
			],
		];
	}

	/**
	 * test the __invoke method when dealing with the checkOnlyIfDirty config
	 * option.
	 *
	 * @return void
	 */
	public function testInvokeOnIgnoreIfDirty() {
		$config = [
			'checkOnlyIfDirty' => true,
		];
		$options = [];

		$tableConfig = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
		$this->Users = TableRegistry::get('Users', $tableConfig);
		$options['repository'] = $this->Users;

		$InConfigureListRule = new InConfigureListRule('role', $config);

		$this->User = new User();
		$this->User->role = 'not-real-value';
		$this->User->dirty('role', false);

		$output = $InConfigureListRule($this->User, $options);
		$this->assertEquals(
			true,
			$output,
			'On the Rule set to checkOnlyIfDirty and the field set to not be dirty, should return true, regardless of the fields value.'
		);
		unset($InConfigureListRule);
		unset($this->Users);

		$config = [
			'checkOnlyIfDirty' => true,
		];
		$options = [];

		$tableConfig = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
		$this->Users = TableRegistry::get('Users', $tableConfig);
		$options['repository'] = $this->Users;

		$InConfigureListRule = new InConfigureListRule('role', $config);

		$this->User = new User();
		$this->User->role = 'admin';
		$this->User->dirty('role', true);

		$output = $InConfigureListRule($this->User, $options);
		$this->assertEquals(
			true,
			$output,
			'On the Rule set to checkOnlyIfDirty and the field set to be dirty, should return true on a real value.'
		);
		unset($InConfigureListRule);
		unset($this->Users);

		$config = [
			'checkOnlyIfDirty' => true,
		];
		$options = [];

		$tableConfig = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
		$this->Users = TableRegistry::get('Users', $tableConfig);
		$options['repository'] = $this->Users;

		$InConfigureListRule = new InConfigureListRule('role', $config);

		$this->User = new User();
		$this->User->role = 'not-real-value';
		$this->User->dirty('role', true);

		$output = $InConfigureListRule($this->User, $options);
		$this->assertEquals(
			false,
			$output,
			'On the Rule set to checkOnlyIfDirty and the field set to be dirty, should return false on a not real value.'
		);
	}
}
