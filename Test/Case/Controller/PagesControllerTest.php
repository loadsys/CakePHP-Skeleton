<?php
App::uses('PagesController', 'Controller');

/**
 * PagesController Test Case
 *
 */
class PagesControllerTest extends ControllerTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array();

	/**
	 * setUp method
	 *
	 * @ref https://github.com/cakephp/cakephp/blob/12cf82ba19117e8bd6c33f6a757d7a9638cd529a/lib/Cake/Test/Case/View/ViewTest.php#L255,L285
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$path = APP . "Test/Samples/View/"; // Will be suffixed by `/Pages` automatically.
		App::build(array(
			'View' => array($path)
		), App::RESET);
	}

	/**
	 * tearDown method
	 *
	 * @ref https://github.com/cakephp/cakephp/blob/12cf82ba19117e8bd6c33f6a757d7a9638cd529a/lib/Cake/Test/Case/View/ViewTest.php#L287,L301
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Not specifying a page name for display() should redirect to the site root.
	 *
	 * @return void
	 */
	public function testDisplayNoPage() {
		$result = $this->testAction('/pages');
		$this->assertEquals(
			Router::url('/', true),
			$this->headers['Location'],
			'Not requesting a page explicitly should redirect to the app\'s homepage.'
		);
	}

	/**
	 * Not specifying a page name for admin_display() should fail.
	 *
	 * @return void
	 */
	public function testAdminDisplayNoPage() {
		$result = $this->testAction('/admin/pages');
		$this->assertEquals(
			Router::url('/', true),
			$this->headers['Location'],
			'Not requesting a page explicitly should redirect to the app\'s homepage.'
		);
	}

	/**
	 * A valid public page name should render the corresponding file.
	 *
	 * @return void
	 */
	public function testDisplayPublicPage() {
		$page = 'public_page';
		$result = $this->testAction('/pages/' . 'nested/' . $page);
		$this->assertStringMatchesFormat(
			"%A{$page}%A",
			$result,
			'Resulting page should contain its own file name.'
		);
	}

	/**
	 * When no routing prefixes are configured, never check for prefixes on filenames.
	 *
	 * @return void
	 */
	public function testDisplayNoRoutingPrefixesConfigured() {
		Configure::write('Routing.prefixes', array());
		$page = 'admin_home';
		$result = $this->testAction('/pages/' . $page);
		$this->assertStringMatchesFormat(
			"%A{$page}%A",
			$result,
			'Normally a protected page, but no routing prefixes configured to check against so display it.'
		);
	}

	/**
	 * The public `::display()` action should **not** expose `admin_`
	 * prefixed files.
	 *
	 * @return void
	 */
	public function testDisplayNoAdminPrefixedPages() {
		$this->setExpectedException('NotFoundException', 'Invalid page');
		$result = $this->testAction('/pages/admin_home');
	}

	/**
	 * The `::admin_display()` method should auto-prefix `admin_` to the
	 * final component of the requested page name.
	 *
	 * @return void
	 */
	public function testAdminDisplayAutoPrefixedPage() {
		$page = 'protected_page';
		$result = $this->testAction('/admin/pages/' . 'nested/'. $page);
		$this->assertStringMatchesFormat(
			"%A{$page}%A",
			$result,
			'Resulting page should contain its own file name.'
		);
	}
}
