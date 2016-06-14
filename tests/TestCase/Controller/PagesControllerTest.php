<?php
/**
 * Tests for the Pages Controller
 */
namespace App\Test\TestCase\Controller;

use App\Controller\PagesController;
use Cake\Core\Configure;
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
		$this->debug = Configure::read('debug');
	}

	/**
	 * tear down our test setup
	 *
	 * @return void
	 */
	public function tearDown() {
		Configure::write('debug', $this->debug);
		parent::tearDown();
	}

	/**
	 * Test the home page display method for the pages controller, but
	 * only when debug is on.
	 *
	 * @return void
	 * @covers App\Controller\PagesController::display
	 */
	public function testDisplayHomeDevelopment() {
		Configure::write('debug', true);

		$this->get("/");

		$this->assertResponseOk();
		$this->assertTemplate(
			'home',
			'We should be rendering the home template'
		);
	}

	/**
	 * Verify that the default home page is suppressed when debug is off.
	 *
	 * @return void
	 * @covers App\Controller\PagesController::display
	 */
	public function testDisplayHomeProduction() {
		$this->markTestIncomplete('@TODO: Can not get this working properly.');
		Configure::write('debug', false);

		$r = $this->get('/');

		$this->assertResponseError();
		$this->assertResponseContains('Default Cake homepage is suppressed when debug is off.');
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
		$this->assertResponseError();
		$this->assertResponseContains('this/page/does/not/exist');
	}
}
