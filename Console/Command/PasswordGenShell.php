<?php
/**
 * PasswordGenShell
 */

App::uses('AppShell', 'Console/Command');
App::uses('Security', 'Utility');

/**
 * Provides a command line interface to generate new passwords and UUIDs
 * for manually inserting User records into the database. Designed to hook
 * into the User model for consistent hashing, if the proper methods are
 * available.
 */
class PasswordGenShell extends Shell {

	/**
	 * Models to load.
	 *
	 * @var array
	 */
	public $uses = array();

	/**
	 * Generate a UUID and a new random password for use when creating a new
	 * User model account by hand.
	 *
	 * @access	public
	 * @return	void.
	 */
	public function main() {
		$plaintext = $this->generatePassword(14);
		if (isset($this->args[0])) {
			$plaintext = $this->args[0];
		} elseif (!$this->params['random']) {
			$input = $this->in('Please enter a new password (leave blank for random): ');
			if (strlen($input) !== 0) {
				$plaintext = $input;
			}
		}

		$this->out('                       New UUID: ' . String::uuid());
		$this->out('    New password (send to user): ' . $plaintext);
		$this->out('Encrypted password (save to DB): ' . $this->hashPassword($plaintext));
	}

	/**
	 * Check if the host app has a User->hashPassword($len) method defined,
	 * and use that if present. Otherwise call our own implementation.
	 *
	 * @access	public
	 * @param	string	$plaintext	The plaintext to hash.
	 * @return	string				Returns the result of User->hashPassword() if available, otherwise Security::hash('blowfish').
	 */
	public function hashPassword($plaintext) {
		$User = ClassRegistry::init('User');
		if ($this->isActualClassMethod('hashPassword', $User)) {
			return $User->hashPassword($plaintext);
		}
		return Security::hash($plaintext, 'blowfish');
	}

	/**
	 * Check if the host app has a User->randomPassword($len) method defined,
	 * and use that if present. Otherwise call our own implementation.
	 *
	 * @access	public
	 * @param	integer	$len	The length of the requested string. Default = 12.
	 * @return	string			Returns the result of User->randomPassword() if available, otherwise ::randomPassword().
	 */
	public function generatePassword($len = 12) {
		$User = ClassRegistry::init('User');
		if ($this->isActualClassMethod('randomPassword', $User)) {
			echo 'using User->random';
			return $User->randomPassword($len);
		}
		return $this->randomPassword($len);
	}

	/**
	 * Generate a new pseudo-random password of given $len and return it as
	 * a string.
	 *
	 * @access	public
	 * @param	integer	$len	The length of the requested string. Default = 12.
	 * @return	string			Returns a PSEUDO-random (NOT cryptogeraphically secure!) string of length = $len (max 64 chars).
	 */
	public function randomPassword($len = 12) {
		return substr(base64_encode(uniqid(mt_rand(), true)), 0, min($len, 64));
	}

	/**
	 * Confirm that a method is actually defined (and not shadowed by
	 * `__call()` for the given object.)
	 *
	 * @access	protected
	 * @param	string	$method	The name of the method to check.
	 * @param	object	$obj	The instantiated object to check.
	 * @return	boolean			True if the named method exists (not via __call()), false otherwise.
	 */
	protected function isActualClassMethod($method, $obj) {
		return (
			in_array($method, get_class_methods($obj))
			&& is_callable(array($obj, $method))
		);
	}

	/**
	 * getOptionParser
	 *
	 * Processing command line options.
	 *
	 * @access	public
	 * @return	void
	 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser
			->addArgument('plaintext', array(
				'help' => 'Optional plaintext for the new password. INSECURE. Takes precedence over the -r option.',
				'required' => false,
			))
			->addOption('random', array(
				'short' => 'r',
				'boolean' => true,
				'help' => __('Generate a random password automatically. (Do not prompt.)')
			))
			->description(__('Generates a UUID and a new random password for use when creating a new User model account by hand.'));
		return $parser;
	}
}
