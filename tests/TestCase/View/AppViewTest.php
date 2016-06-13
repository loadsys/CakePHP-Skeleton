<?php
/**
 * Tests for the AppView Class.
 */
namespace App\Test\TestCase\View;

use App\View\AppView;
use Cake\TestSuite\TestCase;

/**
 * \App\Test\TestCase\View\AppViewTest
 */
class AppViewTest extends TestCase {
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [];

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
	 * test the initialize method
	 *
	 * @return void
	 */
	public function testInitialize() {
		$AppView = $this->getMock(
			'\App\View\AppView',
			['loadHelper']
		);

		$AppView->expects($this->at(0))
			->method('loadHelper')
			->with('Html');
		$AppView->expects($this->at(1))
			->method('loadHelper')
			->with(
				'Form',
				$this->callback(function ($options) {
					$this->assertArrayHasKey('errorClass', $options);
					$this->assertArrayHasKey('templates', $options);
					return true;
				})
			);
		$AppView->expects($this->at(2))
			->method('loadHelper')
			->with('Flash');

		$AppView->initialize();
	}
}
