<?php
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('TwitterBootstrapHelper', 'View/Helper');

/**
 * TwitterBootstrapHelper Test Case
 *
 */
class TwitterBootstrapHelperTest extends CakeTestCase {

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->TB = new TwitterBootstrapHelper($View);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->TB);
		parent::tearDown();
	}


	/**
	 * Provide arguments to testButtonLink in the format:
	 * [text, destination, options, confirm, matcher, msg]
	 *
	 * @return void
	 */
	public function provideTestButtonLinkArgs() {
		return array(
			array(
				'hello world',
				'/users/view/1',
				array(),
				null,
				array(
					'tag' => 'a',
					'attributes' => array(
						'href' => '/users/view/1',
						'class' => 'btn',
					),
					'content' => 'hello world',
				),
				array(
					'tag' => 'form',
					'attributes' => array(
						'action' => '/users/view/1',
						'style' => 'display:none;',
						'method' => 'post',
					),
				),
				'Basic link functionality check.',
			),

			array(
				'hello world',
				'/users/view/2',
				'danger',  // Provide a text "style" string as the option.
				null,
				array(
					'tag' => 'a',
					'attributes' => array(
						'href' => '/users/view/2',
						'class' => 'btn btn-danger',  // The shortcut name should result in the correct class.
					),
					'content' => 'hello world',
				),
				array(
					'tag' => 'form',
					'attributes' => array(
						'action' => '/users/view/2',
						'style' => 'display:none;',
						'method' => 'post',
					),
				),
				'Providing string as sole "style" option.',
			),

			array(
				'hello world',
				'/users/view/3',
				array(
					'style' => 'warning',  // Pass an explicit style.
					'size' => 'lg',   // And size.
					'disabled' => true,  // And disabled state.
				),
				null,
				array(
					'tag' => 'a',
					'attributes' => array(
						'href' => '/users/view/3',
						'class' => 'btn btn-warning btn-lg disabled',  // All classes should be applied.
					),
					'content' => 'hello world',
				),
				array(
					'tag' => 'form',
					'attributes' => array(
						'action' => '/users/view/3',
						'style' => 'display:none;',
						'method' => 'post',
					),
				),
				'Check the additional buttonLink options.',
			),

			array(
				'hello world',
				'/users/view/4',
				array(
					'style' => 'notvalid',  // Pass an invalid style name.
					'size' => 'bad',  // And an invalid size.
					'active' => true,
				),
				null,
				array(
					'tag' => 'a',
					'attributes' => array(
						'href' => '/users/view/4',
						'class' => 'btn',  // Should result in a "plain" button.
					),
					'content' => 'hello world',
				),
				array(
					'tag' => 'form',
					'attributes' => array(
						'action' => '/users/view/4',
						'style' => 'display:none;',
						'method' => 'post',
					),
				),
				'Verify bad values are removed.',
			),

			array(
				'hello world',
				'/users/view/5',
				null,
				'Are you sure?',  // With a confirmation message.
				array(
					'tag' => 'a',
					'attributes' => array(
						'href' => '/users/view/5',
						'class' => 'btn',  // Should result in a "plain" button.
						'onclick' => 'regexp:/Are you sure?/',
					),
					'content' => 'hello world',
				),
				array(
					'tag' => 'form',
					'attributes' => array(
						'action' => '/users/view/5',
						'style' => 'display:none;',
						'method' => 'post',
					),
				),
				'Verify bad values are removed.',
			),
		);
	}


	/**
	 * Confirm that the expected add-on properties are included to convert
	 * a normal <a> tag into a Bootstrap button.
	 *
	 * @dataProvider provideTestButtonLinkArgs
	 *
	 * @param string		$text		The clickable text of the link.
	 * @param string|array	$target		A string URL or Cake routing array.
	 * @param string|array	$options	A string Bootstrap button "style" or Html->link style options array.
	 * @param array			$expectedLink	A "matcher" array to provide to `assertTag()`.
	 * @param array			$expectedButton	Not used in this test.
	 * @param string		$confirm	A confirmation message to display before following the link.
	 * @param string		$msg		The PHPUnit message to display if the assertion fails.
	 * @return void
	 */
	public function testButtonLink($text, $target, $options, $confirm, $expectedLink, $expectedButton, $msg) {
		$actual = $this->TB->buttonLink($text, $target, $options, $confirm);
		$this->assertTag($expectedLink, $actual, $msg);
	}

	/**
	 * Confirm that the expected add-on properties are included to convert
	 * a normal <a> tag into a Bootstrap button.
	 *
	 * @dataProvider provideTestButtonLinkArgs
	 *
	 * @param string		$text		The clickable text of the link.
	 * @param string|array	$target		A string URL or Cake routing array.
	 * @param string|array	$options	A string Bootstrap button "style" or Html->link style options array.
	 * @param array			$expectedLink	Not used in this test.
	 * @param array			$expectedButton	A "matcher" array to provide to `assertTag()`.
	 * @param string		$confirm	A confirmation message to display before following the link.
	 * @param string		$msg		The PHPUnit message to display if the assertion fails.
	 * @return void
	 */
	public function testButtonPost($text, $target, $options, $confirm, $expectedLink, $expectedButton, $msg) {
		$actual = $this->TB->buttonPost($text, $target, $options, $confirm);
		$this->assertTag($expectedButton, $actual, $msg);
	}

	/**
	 * Test the basic properties of the generated HTML for breadcrumbs.
	 *
	 * @return void
	 */
	public function testGetCrumbsSimple() {
		// Confirm we have a ul.breadcrumbs with 3 li's in it.
		$expectedUL = array(
			'tag' => 'ul',
			'attributes' => array(
				'class' => 'breadcrumb',
			),
			'child' => array(
				'tag' => 'li',
				'attributes' => array(
					'class' => 'active'
				),
			),
			'children' => array(
				'count' => 3,
				'only' => array('tag' => 'li'),
			),
		);

		// Confirm that the last <li>'s <a> tag is class=text-muted.
		$expectedLI = array(
			'tag' => 'li',
			'attributes' => array(
				'class' => 'active',
			),
			'child' => array(
				'tag' => 'a',
				'attributes' => array(
					'class' => 'text-muted'
				),
			),
		);

		$this->assertEmpty($this->TB->getCrumbs(null, false, array()), 'There should be no output yet since addCrumbs() has not been called.');

		$this->TB->addCrumb('home', '/', array('class' => 'canary'));
		$this->TB->addCrumb('users', array('controller' => 'users', 'action' => 'index'));
		$this->TB->addCrumb('bob', array('controller' => 'users', 'action' => 'view', 42), array('class' => 'text-danger')); 
		$output = $this->TB->getCrumbs(null, false, array());

		$this->assertTag($expectedUL, $output);
		$this->assertTag($expectedLI, $output);
	}
}
