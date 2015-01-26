<?php
App::uses('AppEmail', 'Lib/Network/Email');

/**
 * AppEmail Test Case
 *
 */
class AppEmailTest extends CakeTestCase {

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->Email = $this->getMock('AppEmail', array('send', 'deliver'));
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->Email);
		parent::tearDown();
	}

	/**
	 * Confirm that the convenience method for sending a password reminder
	 * email sets the proper values. (Config only! Not "view vars" such as
	 * to/from/subject which are equivelent to View .ctp's.)
	 *
	 * @return void
	 */
	public function testSendExample() {
		Configure::write('Email.Transports.default.from', 'phpunit@loadsys.com');
		$recipients = array();
		$vars = array();
		$this->Email->expects($this->once())
			->method('send')
			->will($this->returnValue('canary'));

		$result = $this->Email->sendExample($recipients, $vars);

		$this->assertEquals(
			'canary',
			$result,
			'Return value of sendExample() must be the result of underlying CakeEmial::send().'
		);
		$this->assertEquals(
			(new EmailConfig)->default,
			$this->Email->config(),
			'The loaded config must match the one defined in sendExample().'
		);
		$this->assertEquals(
			array('template' => 'Users/example', 'layout' => 'default'),
			$this->Email->template(),
			'The template must match the one defined in sendExample().'
		);
		$this->assertEquals(
			$recipients,
			$this->Email->to(),
			'The recipient list must match the one provided to sendExample().'
		);
		$this->assertEquals(
			'Example Email',
			$this->Email->subject(),
			'The subject must match the one defined in sendExample().'
		);
	}
}
