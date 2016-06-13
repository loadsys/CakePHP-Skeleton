<?php
/**
 * Tests for the JsonType class.
 */

namespace App\Test\TestCase\Database\Type;

use App\Database\Type\JsonType;
use Cake\Database\Driver;
use Cake\Database\Type;
use Cake\TestSuite\TestCase;
use PDO;

/**
 * \App\Database\Type\JsonTypeTest
 */
class JsonTypeTest extends TestCase {

	/**
	 * Array of representative data to be serialized and unserialized.
	 *
	 * @var array
	 */
	protected $dataArray = [
		'location_id' => '870b3210-e38f-4222-881c-46c0baaf2b0a',
	];

	/**
	 * A json-encoded version of ::$dataArray. Assigned during ::setUp().
	 *
	 * @var string
	 */
	protected $dataJson = '';

	/**
	 * setUp method.
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->Type = new JsonType();
		$this->driver = $this->getMock('Cake\Database\Driver');
		$this->dataJson = json_encode($this->dataArray);
	}

	/**
	 * tearDown method.
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->Type);
		unset($this->driver);
		unset($this->dataArray);
		unset($this->dataJson);

		parent::tearDown();
	}

	/**
	 * Test toPHP() with null values.
	 *
	 * @return void
	 */
	public function testToPHPWithEmpty() {
		$this->assertNull(
			$this->Type->toPHP(null, $this->driver),
			'Converting a null value should produce null output.'
		);
		$this->assertNull(
			$this->Type->toPHP('', $this->driver),
			'Converting an empty string should produce null output.'
		);
	}

	/**
	 * Test toPHP() with json values.
	 *
	 * @return void
	 */
	public function testToPHPWithJson() {
		$this->assertEquals(
			$this->dataArray,
			$this->Type->toPHP($this->dataJson, $this->driver),
			'Returned value should be a json_encode()d string.'
		);
	}

	/**
	 * Test converting to database format.
	 *
	 * @return void
	 */
	public function testToDatabase() {
		$this->assertEquals(
			$this->dataJson,
			$this->Type->toDatabase($this->dataArray, $this->driver),
			'The packed field value should be stored as json encoded string'
		);
	}

	/**
	 * Test marshalling data.
	 *
	 * @param mixed $input The value to provide to marshal().
	 * @param mixed $expected The expected output value.
	 * @param string $msg Optional PHPUnit assertion failure message.
	 * @return void
	 * @dataProvider provideMarshalArgs
	 */
	public function testMarshal($input, $expected, $msg = '') {
		$this->assertSame(
			$expected,
			$this->Type->marshal($input),
			$msg
		);
	}

	/**
	 * Data provider for marshal().
	 *
	 * @return array
	 */
	public function provideMarshalArgs() {
		return [
			[
				'{"foo":"bar"}', // input
				['foo' => 'bar'], // expected
				'A json string should be marshalled to an array.', // msg
			],
			[
				['foo' => 'bar'],
				['foo' => 'bar'],
				'An array should be marshalled to an array.',
			],
			[
				null,
				null,
				'A null value should be marshalled to null.',
			],
		];
	}

	/**
	 * Simple sanity check of toStatement().
	 *
	 * @param mixed $input The value to provide to toStatement().
	 * @param mixed $expected The expected output value.
	 * @param string $msg Optional PHPUnit assertion failure message.
	 * @return void
	 * @dataProvider provideToStatementArgs
	 */
	public function testToStatement($value, $expected, $msg = '') {
		$this->assertEquals(
			$expected,
			$this->Type->toStatement($value, $this->driver),
			$msg
		);
	}

	/**
	 * Provide input/output args to testToStatement().
	 *
	 * @return array Sets of [input, output, msg].
	 */
	public function provideToStatementArgs() {
		return [
			[
				null, // input
				PDO::PARAM_NULL, // expected
				'Null input should produce PDO `NULL` param type.', // msg
			],
			[
				'just a string',
				PDO::PARAM_STR,
				'Any non-null input should produce PDO `STR` param type.',
			],
		];
	}
}
