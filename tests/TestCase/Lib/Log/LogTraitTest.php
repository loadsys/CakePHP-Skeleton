<?php
/**
 * Tests for the LogTrait Class.
 */
namespace App\Test\TestCase\Lib\Log;

use App\Lib\Log\LogTrait;
use App\Model\Entity\Transaction;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * \App\Test\TestCase\Lib\Log\TestLogTrait
 *
 * Stub class to expose methods from LogTrait trait.
 */
class TestLogTrait {
	// Provide quick access to logging.
	use LogTrait;
}

/**
 * \App\Test\TestCase\Lib\Log\LogTraitTest
 *
 * @coversDefaultClass \App\Lib\Log\LogTrait
 */
class LogTraitTest extends TestCase {
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [
		'app.users',
	];

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->TestLogTrait = new TestLogTrait();

		$config = TableRegistry::exists('Users') ? [] : ['className' => '\App\Model\Table\UsersTable'];
		$this->Users = TableRegistry::get('Users', $config);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->TestLogTrait);
		unset($this->Users);
		Log::drop('trait_test');

		parent::tearDown();
	}
	/**
	 * Test log method.
	 *
	 * @return void
	 */
	public function testLog() {
		$mock = $this->getMock('Psr\Log\LoggerInterface');
		$mock->expects($this->at(0))
			->method('log')
			->with('error', 'Testing');
		$mock->expects($this->at(1))
			->method('log')
			->with('debug', [1, 2]);
		Log::config('trait_test', ['engine' => $mock]);
		$subject = $this->getObjectForTrait('App\Lib\Log\LogTrait');
		$subject->log('Testing');
		$subject->log([1, 2], 'debug');
	}

	/**
	 * test the ::logPerformance() method
	 *
	 * @return void
	 */
	public function testLogPerformance() {
		$message = 'foo';
		$Trait = $this->getMock(
			'\App\Test\TestCase\Lib\Log\TestLogTrait',
			['log']
		);
		$Trait->expects($this->once())
			->method('log')
			->with($this->anything(), 'info', ['scope' => ['performance']])
			->will($this->returnValue(null));

		$this->assertNull($Trait->logPerformance($message));
	}
}
