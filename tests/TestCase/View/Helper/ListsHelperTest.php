<?php
/**
 * Tests for the ListsHelper Class.
 */
namespace App\Test\TestCase\View\Helper;

use App\View\Helper\ListsHelper;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * \App\Test\TestCase\View\Helper\ListsHelperTest
 */
class ListsHelperTest extends TestCase {
	/**
	 * An array of "fake" values to load into Configure(Lists) for testing.
	 *
	 * @var array
	 */
	protected $Lists = [
		'foo' => 'bar',
		'sub' => [
			'one' => 1,
			'two' => 2,
			'three' => 3,
		],
	];

	/**
	 * Temporarily stores the existing Configure(Lists) value during testing.
	 * (Restored in tearDown().)
	 *
	 * @var array
	 */
	protected $ListsBackup = null;

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$view = new View();
		$this->Helper = new ListsHelper($view);

		$this->ListsBackup = Configure::read('Lists');
		Configure::write('Lists', $this->Lists);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->Helper);

		Configure::write('Lists', $this->ListsBackup);

		parent::tearDown();
	}

	/**
	 * Test get().
	 *
	 * @param string $path The Configure path to start in.
	 * @param string $key The key to fetch from $path.
	 * @param null|string $default Value to return when $path/$key not found or empty.
	 * @param mixed $expected The expected return value.
	 * @param string $msg Optional PHPUnit assertion failure message.
	 * @return void
	 * @dataProvider provideGetArgs
	 */
	public function testGet($path, $key, $default, $expected, $msg = '') {
		$this->assertEquals(
			$expected,
			$this->Helper->get($path, $key, $default),
			$msg
		);
	}

	/**
	 * Provide I/O pairs for testGet().
	 *
	 * @return array
	 */
	public function provideGetArgs() {
		return [
			[
				'doesnotexist',
				null,
				null,
				null,
				'Invalid path should return null output.',
			],

			[
				'foo',
				null,
				null,
				'bar',
				'Valid path should return string value at that index.',
			],

			[
				'sub.one',
				null,
				null,
				1,
				'Valid dotted path should return scalar value at that index.',
			],

			[
				'sub',
				null,
				null,
				$this->Lists['sub'],
				'Valid partial path should return entire sub-array at that index.',
			],

			[
				'sub',
				'one',
				null,
				$this->Lists['sub']['one'],
				'Valid path + key should return scalar value at that index.',
			],

			[
				'sub',
				'doesnotexist',
				'default text',
				'default text',
				'Invalid path should return the provided default value when present.',
			],
		];
	}
}
