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
	 * Not specifying a page name for display() should fail.
	 *
	 * @return void
	 */
	public function testDisplayNoPage() {
		$this->markTestIncomplete('testDisplayNoPage not implemented.');
		$result = $this->testAction('/pages');
	}

	/**
	 * Not specifying a page name for admin_display() should fail.
	 *
	 * @return void
	 */
	public function testAdminDisplayNoPage() {
		$this->markTestIncomplete('testAdminDisplayNoPage not implemented.');
		$result = $this->testAction('/admin/pages');
	}

	/**
	 * A valid public page name should render the corresponding file.
	 *
	 * @return void
	 */
	public function testDisplayPublicPage() {
		$this->markTestIncomplete('testDisplayPublicPage not implemented.');
		$result = $this->testAction('/pages/test/test');
	}

	/**
	 * The public `::display()` action should **not** expose `admin_`
	 * prefixed files.
	 *
	 * @return void
	 */
	public function testDisplayNoAdminPrefixedPages() {
		$this->markTestIncomplete('testDisplayNoAdminPrefixedPages not implemented.');
		$result = $this->testAction('/pages/test/admin_test');
	}

	/**
	 * The `::admin_display()` method should auto-prefix `admin_` to the
	 * final component of the requested page name.
	 *
	 * @return void
	 */
	public function testAdminDisplayAutoPrefixedPage() {
		$this->markTestIncomplete('testAdminDisplayAutoPrefixedPage not implemented.');
		$result = $this->testAction('/admin/pages/test/test');
	}
}
