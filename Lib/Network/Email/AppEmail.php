<?php
/**
 * AppEmail file. Extends the CakeEmail class to provide shortcut methods for
 * common use cases. Allows all email-sending-related code to be consolidated
 * here.
 *
 */
App::uses('CakeEmail', 'Network/Email');

/**
 * AppEmail class.
 *
 * Usage:
 *
 * App::uses('AppEmail', 'Lib/Network/Email');
 * $result = new AppEmail()->callShortcutSenderMethod($recipient, $viewVars);
 *
 */
class AppEmail extends CakeEmail {

	/**
	 * sendExample
	 *
	 * Shortcut method that sets all necessary configurations for sending an
	 * example email to the provided $recipients.
	 *
	 * @param  array  $recipients array of who to send the email to
	 * @param  array  $viewVars   any view vars for the email view
	 * @return boolean            true if email sent, false otherwise
	 */
	public function sendExample($recipients, $viewVars = array()) {
		return $this
			->config('default')
			->template('Users/example')
			->to($recipients)
			->subject(__('Example Email'))
			->viewVars($viewVars)
			->send();
	}
}
