<?php
/**
 * Tests for the Pages Controller
 */
namespace App\Test\TestCase\Controller;

use App\Controller\PagesController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * \App\Test\TestCase\Controller\PagesControllerTest
 */
class PagesControllerTest extends IntegrationTestCase {
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [];

	/**
	 * setup things needed for our test running
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * tear down our test setup
	 *
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Test the home page display method for the pages controller
	 *
	 * @return void
	 * @covers App\Controller\PagesController::display
	 */
	public function testDisplayHome() {
		$this->get("/");

		$this->assertResponseOk();
		$this->assertTemplate(
			'home',
			'We should be rendering the home template'
		);
	}

	/**
	 * Test a sub page that does not exist.
	 *
	 * @return void
	 * @covers App\Controller\PagesController::display
	 */
	public function testDisplayInvalid() {
		$this->session([]);

		$this->get('/pages/');
		$this->assertRedirect('/', 'Attempting to access the PagesController::display() method directly should force a redirect to the homepage.');

		$this->get('/pages/this/page/does/not/exist');
		$this->assertResponseFailure();
		$this->assertResponseContains('this/page/does/not/exist.ctp');
	}
}
