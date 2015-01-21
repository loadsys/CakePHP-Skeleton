<?php
/**
 * Eu helper
 */
App::uses('AppHelper', 'View/Helper');
App::uses('HtmlHelper', 'View/Helper');
App::uses('CakeNumber', 'Utility');

/**
 * LoadsysHtml helper
 *
 * Contains convenience helper methods to Loadsys projects.
 *
 * @package       app.View.Helper
 */
class LoadsysHtmlHelper extends HtmlHelper {

	/**
	 * Other helpers used by this one.
	 *
	 * @var	array
	 */
	public $helpers = array(
		'Time',
		'Html',
		'Js',
	);

	/**
	 * timeAgo
	 *
	 * Wrapper around TimeHelper::timeAgoInWords(), but with a standardized
	 * alternate ::wordFormat.
	 *
	 * @access	public
	 * @param	string	$datetime			Any date/time string that TimeHelper::timeAgoInWords() can handle.
	 * @param	array	$options			An array of options as normally provided as the second argument to TimeHelper::timeAgoInWords().
	 * @return	string						Formatted HTML for timeAgoInWords with a pre-configured output format.
	 */
	public function timeAgo($datetime, $options = array()) {
		$wordFormat = 'Y-m-d';
		$defaultOptions = array('format' => $wordFormat);
		return $this->Time->timeAgoInWords($datetime, array_merge($defaultOptions, $options));
	}

	/**
	 * modified
	 *
	 * Wrapper around ::timeAgo(), but with a standardized fallback case if
	 * the provided $datetime is null.
	 *
	 * @access	public
	 * @param	string	$datetime			Any date/time string that TimeHelper::timeAgoInWords() can handle.
	 * @param	array	$options			An array of options as normally provided as the second argument to TimeHelper::timeAgoInWords().
	 * @return	string						Formatted HTML for timeAgoInWords with a pre-configured output format, or the word 'Never' if the provided var was null.
	 */
	public function modified($datetime, $options = array()) {
		return (is_null($datetime) ? __('Never') : $this->timeAgo($datetime, $options));
	}

	/**
	 * yesNo
	 *
	 * returns Yes or No on a boolean input
	 *
	 * @param  boolean $boolean Boolean to return a value upon, defaults to false
	 * @return string           String either Yes or No
	 */
	public function yesNo($boolean = false) {
		return (($boolean) ? __('Yes') : __('No'));
	}

	/**
	 * envHint
	 *
	 * Spits out additional HTML or CSS information for inclusion in a
	 * Layout to override default styles based on the current environment.
	 * (Changing the background color in staging, for example.) See
	 * Config/core.php for more. Debug MUST be turned on for this method
	 * to return any results to prevent overrides from affecting production
	 * environments (where the debug level takes precedence over the
	 * environment setting.)
	 *
	 * @return string           A pre-formatted HTML snippet with the appropriate style overrides for the current APP_ENV.
	 */
	public function envHint() {
		$format = (string)Configure::read('Defaults.EnvHint.format');
		$snippet = (string)Configure::read('Defaults.EnvHint.snippet');

		if (!empty($snippet) && Configure::read('debug') > 0) {
			return sprintf($format, $snippet);
		} else {
			return '';
		}
	}

	/**
	 * formatSizeUnits
	 *
	 * Changes a filesize in bytes to GB, MB, or KB.
	 *
	 * @codeCoverageIgnore	Don't test a thin wrapper around a Cake core function.
	 * @access	public
	 * @param	int	$bytes				A size in bytes.
	 * @return	string					A formatted conversion of $bytes to GB, MB, KB.
	 */
	public function formatSizeUnits($bytes) {
		return CakeNumber::toReadableSize($bytes);
	}
}
