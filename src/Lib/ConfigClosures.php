<?php
/**
 * ConfigClosures contains static methods used only for configuring the
 * application. This class should only be used in the Config loading
 * process. Add new static methods here for configuring stuff.
 */
namespace App\Lib;

use Cake\Core\Configure;
use Cake\Utility\Hash;

/**
 * \App\Lib\ConfigClosures
 */
class ConfigClosures {
	/**
	 * Define a helper function that merges default Cache configs together.
	 *
	 * The cacheMerge() function first joins the overrides provided with a
	 * standard set of defaults. Then if an env-override or local config is
	 * loaded on top of the production config, those values with again
	 * replace production values. The enclosed $defaults array is just a
	 * quick way to avoid duplication between multiple Cache configs. You
	 * can specify a Memcached host, username, password and prefix here,
	 * then only need to define the secodary prefix for each individual
	 * Cache bucket.
	 *
	 * @param array $overrides The possible overrides to merge.
	 * @return array
	 */
	public static function cacheMerge(array $overrides = []) {
		$defaults = [
			'className' => 'Memcached',
			'compress' => true,
			'duration' => '+1 years',
			'prefix' => '@TODO_',
			'servers' => '@TODO: Default (prod) Memcached server address',
			'username' => '@TODO: Default (prod) Memcached server username',
			'password' => '@TODO: Default (prod) Memcached server password',
		];

		// Special case prefixes to "prepend" the default prefix.
		if (!empty($overrides['prefix'])) {
			$overrides['prefix'] = $defaults['prefix'] . $overrides['prefix'];
		}

		return Hash::merge($defaults, $overrides);
	}

	/**
	 * Returns an array of 4 digit years for a dropdown list, starting from the
	 * current year and extending out 10+ years.
	 *
	 * @return array Array formated of [year => year].
	 */
	public static function creditCardYear() {
		$range = range(date('Y'), date('Y', strtotime('+10 years')));
		return array_combine($range, $range);
	}

	/**
	 * Returns a new instance of a User Entity class.
	 *
	 * Useful for setting the from, bcc, cc, etc. properties of an Email
	 * instance if you are using a custom AppEmail class that only accepts
	 * User Entities in order to verify whether each recipient is allowed
	 * to be sent email.
	 *
	 * @param string $email The email address for the User.
	 * @param string $firstname The firstname for the User, default is empty string.
	 * @param string $lastname The lastname for the User, default is empty string.
	 * @return \App\Model\Entity\User A new User instance with a subset of
	 * properties set.
	 */
	public static function userEntity($email, $firstname = '', $lastname = '') {
		$entity = new \App\Model\Entity\User();

		$entity->email = $email;
		$entity->firstname = $firstname;
		$entity->lastname = $lastname;
		$entity->ignore_invalid_email = true;

		return $entity;
	}

	/**
	 * Create "pretty" email address arrays using Configure knowledge.
	 *
	 * Returns a generator function that yields a single
	 * [Display Name => email@address.com] array pair when accessed.
	 *
	 * @TODO: This is a crazy idea. Need to test if it works in practice.
	 *
	 * @param string $localAddress The portion of the email address before the `@`.
	 * @param string $displayName The proper name to associate with this address. If blank, the `Defaults.short_name` for the app is used.
	 * @return mixed An anonymous generator function that yields an array as expected by Email.
	 */
	public static function email($localAddress, $displayName = false) {
		// The Configure::read()s need to happen at runtime,
		// not when called during Configure bootstrapping.
		return function () use ($localAddress, $displayName) {
			$displayName = ($displayName ?: Configure::read('Defaults.short_name'));
			$address = sprintf('%s@%s', $localAddress, Configure::read('Defaults.domain'));
			yield $address => $displayName;
		};
	}

	/**
	 * styleForEnv
	 *
	 * Spits out additional CSS <style> information for inclusion in a
	 * Layout's <head> to override default styles based on the current
	 * environment. (Changing the background color in staging, for example.)
	 * See Config/env_vars.txt and Config/bootstrap.php for more. Debug MUST
	 * be turned on for this method to return any results to prevent overrides
	 * from affecting production environments (where debug takes precedence
	 * over the environment variable.)
	 *
	 * @return string A pre-formatted <style></style> tag with the appropriate overrides for the $env.
	 */
	public static function styleForEnv() {
		$format = (string)Configure::read('Defaults.Env.Hint.Format');
		$snippet = (string)Configure::read('Defaults.Env.Hint.Snippet');

		if (!empty($snippet) && Configure::read('debug')) {
			return sprintf($format, $snippet);
		}

		return '';
	}
}
