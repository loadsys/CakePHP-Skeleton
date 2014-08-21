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
		$result = $this->testAction('/pages');
		$this->markTestIncomplete('testDisplayNoPage not implemented.');
	}

	/**
	 * Not specifying a page name for admin_display() should fail.
	 *
	 * @return void
	 */
	public function testAdminDisplayNoPage() {
		$result = $this->testAction('/admin/pages');
		$this->markTestIncomplete('testAdminDisplayNoPage not implemented.');
	}

	/**
	 * A valid public page name should render the corresponding file.
	 *
	 * @return void
	 */
	public function testDisplayPublicPage() {
		$result = $this->testAction('/pages/test/test');
		$this->markTestIncomplete('testDisplayPublicPage not implemented.');
	}

	/**
	 * The public `::display()` action should **not** expose `admin_`
	 * prefixed files.
	 *
	 * @return void
	 */
	public function testDisplayNoAdminPrefixedPages() {
		$result = $this->testAction('/pages/test/admin_test');
		$this->markTestIncomplete('testDisplayNoAdminPrefixedPages not implemented.');
	}

	/**
	 * The `::admin_display()` method should auto-prefix `admin_` to the
	 * final component of the requested page name.
	 *
	 * @return void
	 */
	public function testAdminDisplayAutoPrefixedPage() {
		$result = $this->testAction('/admin/pages/test/test');
		$this->markTestIncomplete('testAdminDisplayAutoPrefixedPage not implemented.');
	}
}
