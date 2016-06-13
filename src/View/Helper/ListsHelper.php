<?php
/**
 * Provide quick access to Configure('Lists').
 *
 * This Helper also removes the need to set the "global" $lists view var in
 * AppController::beforeRender(), which saves a bit of memory.
 *
 * There is no need to include this in AppView-- it will be lazy-loaded by
 * Cake on use.
 */
namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * \App\View\Helper\ListsHelper
 */
class ListsHelper extends Helper {
	/**
	 * Other helpers used by HtmlHelper
	 *
	 * @var array
	 */
	public $helpers = [];

	/**
	 * Wrapper around Configure::read('Lists.{$path}').
	 *
	 * Fetches the array identified by $path from Configure, then looks up
	 * the corresponding $key and returns the associated value. Useful for
	 * converting a db slug value into its "display" counterpart.
	 *
	 * The alternate (simpler) form that's useful for returning an entire
	 * associative array of [slug => Display] pairs is to only pass the
	 * first argument: `$this->Lists->get('Students.school_grade')`.
	 *
	 * When called without any arguments, the method acts as a View-level
	 * shortcut to `\Cake\Core\Configure::read('Lists')` and returns the
	 * entire sub-array of available lists.
	 *
	 * @param string $path Configure path to the array to search **excluding**
	 *    the leading `Lists.`
	 * @param string $key The final component in a path, typically stored as
	 *    a slug in a DB field.
	 * @param null|string $default The value to use if the path or key can
	 *    not be found. The default $default is to pass the $key value
	 *    through, if present, otherwise the empty string is used.
	 * @return string The found Configure value, or $default if path was not
	 *    found/empty.
	 */
	public function get($path = '', $key = null, $default = null) {
		$path = rtrim("Lists.{$path}.{$key}", '.');
		$default = (!is_null($default) ? $default : $key);
		$val = Configure::read($path);
		return (!is_null($val) ? $val : $default);
	}
}
