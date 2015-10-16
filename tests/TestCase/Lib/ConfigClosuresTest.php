<?php
/**
 * Tests for the ConfigClosures Lib Class.
 */
namespace App\Test\TestCase\Lib;

use App\Lib\ConfigClosures;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;

/**
 * \App\Test\TestCase\Lib\ConfigClosuresTest
 */
class ConfigClosuresTest extends TestCase {
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
		parent::tearDown();
	}

	/**
	 * Test the cacheMerge method.
	 *
	 * @param array $overrides Passed in overrides property.
	 * @param array $expected Passed in expected output values property.
	 * @return void
	 * @dataProvider providerCacheMerge
	 */
	public function testCacheMerge(array $overrides, array $expected) {
		$output = ConfigClosures::cacheMerge($overrides);
		$this->assertEquals(
			$output,
			$expected,
			'The output does not match what we expected to have generated.'
		);
	}

	public function providerCacheMerge() {
		return [
			'No Overrides' => [
				[],
				[
					'className' => 'Memcached',
					'compress' => true,
					'duration' => '+1 years',
					'prefix' => '@TODO_',
					'servers' => '@TODO: Default (prod) Memcached server address',
					'username' => '@TODO: Default (prod) Memcached server username',
					'password' => '@TODO: Default (prod) Memcached server password',
				],
			],
			'className Override' => [
				[
					'className' => 'RandomClass'
				],
				[
					'className' => 'RandomClass',
					'compress' => true,
					'duration' => '+1 years',
					'prefix' => '@TODO_',
					'servers' => '@TODO: Default (prod) Memcached server address',
					'username' => '@TODO: Default (prod) Memcached server username',
					'password' => '@TODO: Default (prod) Memcached server password',
				],
			],

			'Prefix and ClassName Overrides' => [
				[
					'className' => 'RandomClass',
					'prefix' => 'something_'
				],
				[
					'className' => 'RandomClass',
					'compress' => true,
					'duration' => '+1 years',
					'prefix' => '@TODO_something_',
					'servers' => '@TODO: Default (prod) Memcached server address',
					'username' => '@TODO: Default (prod) Memcached server username',
					'password' => '@TODO: Default (prod) Memcached server password',
				],
			],
		];
	}

	/**
	 * Test the creditCardYear method.
	 *
	 * @return void
	 */
	public function testCreditCardYear() {
		$result = ConfigClosures::creditCardYear();

		$iterator = 0;
		foreach ($result as $yearKey => $yearValue) {
			$expectedValue = date('Y', strtotime("+{$iterator} years"));
			$this->assertEquals(
				$yearKey,
				$expectedValue,
				'The expected value should match our yearKey'
			);
			$this->assertEquals(
				$yearValue,
				$expectedValue,
				'The expected value should match our yearValue'
			);
			$iterator++;
		}

		$this->assertEquals(11, $iterator, 'We should have gone up 10+ years (plus 1 for the last iteration addition)');
	}

	/**
	 * Test the userEntity method.
	 *
	 * @param string $email Passed in email field.
	 * @param string $firstname Passed in firstname field.
	 * @param string $lastname Passed in lastname field.
	 * @return void
	 * @dataProvider providerUserEntity
	 */
	public function testUserEntity($email, $firstname, $lastname) {
		if (!class_exists('App\Model\Entity\User')) {
			$this->markTestSkipped('No User entity available to test with.');
		}

		$entity = new \App\Model\Entity\User();
		$entity->email = $email;
		$entity->firstname = $firstname;
		$entity->lastname = $lastname;
		$entity->ignore_invalid_email = true;

		$output = ConfigClosures::userEntity($email, $firstname, $lastname);

		$this->assertEquals(
			$entity->email,
			$output->email,
			'The generated User->email Entity should match the output'
		);
		$this->assertEquals(
			$entity->firstname,
			$output->firstname,
			'The generated User->firstname Entity should match the output'
		);
		$this->assertEquals(
			$entity->lastname,
			$output->lastname,
			'The generated User->lastname Entity should match the output'
		);
		$this->assertEquals(
			$entity->ignore_invalid_email,
			$output->ignore_invalid_email,
			'The generated User->ignore_invalid_email Entity should match the output'
		);
	}

	/**
	 * dataProvider for testUserEntity
	 *
	 * @return array Data inputs for testUserEntity.
	 */
	public function providerUserEntity() {
		return [
			'All non required fields are empty' => [
				'testing@localhost.com',
				'',
				'',
			],
			'All fields are empty' => [
				'',
				'',
				'',
			],
			'All fields are set' => [
				'testing@localhost.com',
				'Test',
				'Last',
			],
		];
	}

	/**
	 * Test the `::styleForEnv` method. Verify that it returns the string
	 * that matches the format for this environment.
	 *
	 * @return void
	 */
	public function testStyleForEnv() {
		$expected = [
			'Format' => '<style> element { %1$s } </style>',
			'Snippet' => 'background: red !important;',
		];
		Configure::write('Defaults.Env.Hint', $expected);

		$result = ConfigClosures::styleForEnv();

		$this->assertStringMatchesFormat(
			"<style> element { background: red !important; } </style>",
			$result,
			'The test environments should produce a complete css rule.'
		);

		Configure::write('Defaults.Env.Hint.Snippet', '');
		$this->assertEquals(
			'',
			ConfigClosures::styleForEnv(),
			'An empty string for the snippet setting should return an empty string.'
		);
	}
}
