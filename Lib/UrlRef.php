<?php
/**
 * URL Dereferencing library.
 */
App::uses('ClassRegistry', 'Utility');
App::uses('Router', 'Routing');
App::uses('Inflector', 'Core');


/**
 * Provides an app-wide interface for looking up URLs for any compatible
 * object in the app.
 *
 * Provides two methods: A direct PHP call for use in code, and a string parser
 * that will replace "marker" instances with the appropriate URL for use in
 * processing HTML content stored in the database (see examples immediately
 * below). This class should be included in AppController via
 *
 * 		`App::uses('UrlRef', 'Lib');`.
 *
 * Examples:
 * 		`UrlRef::get('Blog', $id, $optionsArray)`
 * 		`UrlRef::parse('{{Blog:id:option1|option2}}')`
 *
 * A couple things to keep in mind:
 *   - Whatever class is named in the call must be already be available (via
 *     `App::uses()`) and loadable via `ClassRegistry::init()`.
 *   - That class must implement a `::buildUrl($id, $options)` method.
 *   - You can reference controllers in the singular tense and without
 *     suffixing 'Controller', so '{{Person:42}}' maps to
 *    `PeopleController->buildUrl(42)`;
 *
 * The purpose of this class is to be able to do things like this in your Views:
 *
 * 		echo $this->Html->link('click here', UrlRef::get('Blog', 123));
 * or
 *		echo UrlRef::parse($post['Post']['body']);
 *
 * @package       app.Lib
 */
class UrlRef {

	/**
	 * Temporary storage for the currently-matching {{...}} marker string.
	 *
	 * @var	string	$_matched
	 */
	static protected $_matched = '';

	/**
	 * Temporary storage for the current `::parse()` run. Merged with the
	 * options included in each {{...}} match and provided to the object's
	 * `::buildUrl()` method.
	 *
	 * @var	string	$_options
	 */
	static protected $_options = array();

	/**
	 * Regex that matches the following example strings:
	 *   - `{{Post:1}}`
	 *   - `{{Post:123}}`
	 *   - `{{Comment:d111197c-83ab-11e3-a6f1-bfb7a9b2decb}}`
	 *   - `{{Post:123:option1}}`
	 *   - `{{Comment:d111197c-83ab-11e3-a6f1-bfb7a9b2decb|full|nobase}}`
	 *   - `{{Post:123:option1|option2|o3}}`
	 *
	 * Using the final example above, the capture groups are arranged as such:
	 *   1 = Post
	 *   2 = 123
	 *   3 = option1|option2|o3
	 *
	 * If index 3 is present, it will be converted to an array by `explode()`ing
	 * on the pipe (|) character.
	 *
	 * The object name must correspond to a class available in the app via
	 * `App::uses()`. The ID must be an integer or UUID. Options must consist
	 * only of lowercase alphabetic characters.
	 *
	 * @var	string	$_format
	 */
	static protected $_format = '/
			(?x)                                   # Ignore whitespace and (these) comments in this regex.
			\{\{                                   # Match two opening curly braces.
			([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)  # Capture the PHP class name,
			:                                      # Always followed by a colon.
			(?|                                    # Capture the object id,
				(\d+)                              # Either integer,
				|([0-9a-f]{8}-(?:[0-9a-f]{4}-){3}[0-9a-f]{12})  # Or UUID style.
			)
			(?:                                    # Non-capturing group where,
				:                                  # If there is another colon,
				(                                  # Capture all of the arguments after it,
					(?:[a-z]+\|)*(?:[a-z]+)        # Which consist of simple strings separated by pipes.
				)
			)?                                     # But a second colon and args are optional.
			\}\}                                   # Match two closing curly braces.
		/x';

	/**
	 * Searches for and calls the named $class's `::buildUrl($id, $options)`
	 * method. Returns the result if found. If all attempts to build a URL
	 * fail, false is returned.
	 *
	 * @access	public
	 * @param	string	$class		The name of the class to which the $id belongs.
	 * @param	string	$id			Primary key for the record to fetch from the $object's class.
	 * @param	string	$options	Array of options that control the output or lookup.
	 *								Will be provided as the second argument to `{$object}->buildUrl()` as well.
	 * @return	string|false		A local (or fully-qualified) URL to the $object identified by $id. False if we are unable to call the buildUrl() method.
	 */
	public static function get($class, $id, $options = array()) {
		$class = ucfirst($class);
		$classes = array(
			"{$class}Controller",	// people|People -> PeopleController
			Inflector::pluralize($class) . 'Controller',	// person|people|Person|People -> PeopleController
			$class,											// Unusual -> Unusual
		);

		foreach ($classes as $c) {
			App::uses($c, 'Controller');
			if (is_callable(array($c, 'buildUrl'))) {
				if (Configure::read('debug') > 0) {
					self::log("Calling method `{$c}::buildUrl()`.");
				}
				$object = Classregistry::init($c);
				return call_user_func(array($object, 'buildUrl'), $id, $options);
			}
		}

		// At this point we've failed to build a URL.
		self::log("get('{$class}', '{$id}'" . (count($options) ? (', [' . implode('|', $options) . ']') : '') . ' failed.');
		return false;
	}

	/**
	 * Scans the provided $str and replaces any occurances of markers such as
	 * {{object:id:option1|option2}} with the local (or fully qualified) URL.
	 *
	 * @access	public
	 * @param	string	$str		The string to scan.
	 * @param	string	$options	Array of options that control the output or lookup.
	 *								Will be provided as the second argument to `{$object}->buildUrl()` as well.
	 * @return	string				The input $str, but with all (recognized) {{...}} markers replaced with valid URLs. Unrecognized markers will be left in place.
	 */
	public static function parse($str, $options = array()) {
		self::$_options = $options;
		$result = preg_replace_callback(self::$_format, 'self::replacer', $str);

		// Clean up.
		self::$_matched = '';
		self::$_options = array();

		return $result;
	}

	/**
	 * Runs the provided $str through parse, and then ensures that no more {{...}} markers remain afterwards. Returns true if there are none, and false if any remain (indicating that some of the markers could not be replaced with valid URLs.)
	 *
	 * @access	public
	 * @param	string	$str		The string to scan.
	 * @param	string	$options	Array of options that currently don't do anything.
	 * @return	boolean				True if all markers an can be successfully replaced, false if any  can not be replaced.
	 */
	public static function validate($str, $options = array()) {
		$parsed = self::parse($str, $options);
		return !(preg_match_all(self::$_format, $parsed));
	}

	/**
	 * Callback to process each found token from `::parse()` by providing the
	 * correct args to ``::get()`.
	 *
	 * @access	protected
	 * @param	array	$matches	A single match from the `::$_format` regex.
	 * @return	string				The string URL version of the matched marker.
	 */
	public static function replacer($matches) {
		self::$_matched = $matches[0];
		$options = (isset($matches[3]) ? explode('|', $matches[3]) : array());
		$options = array_merge(self::$_options, $options);
		$result = self::get($matches[1], $matches[2], $options);
		return ($result ?: self::$_matched);
	}

	/**
	 * Unified interface to external logging.
	 *
	 * @access	protected
	 * @param	string	$msg		The message to write to the log.
	 * @return	void
	 */
	protected static function log($msg) {
		return CakeLog::write('UrlRef', $msg);
	}
}
