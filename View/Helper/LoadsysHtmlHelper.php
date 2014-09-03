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
	 * @param  string	$env	A string "environment" name. Typically 'prod', 'staging' or 'dev' as obtained from Configure::read('Environment.LOADSYS_ENVIRONMENT').
	 * @return string           A pre-formatted <style></style> tag with the appropriate overrides for the $env.
	 */
	public function styleForEnv($env = null) {
		$format = '<style> .navbar-fixed-top { %s } </style>';

		if (is_null($env) && isset($_SERVER['APP_ENV'])) {
			$env = $_SERVER['APP_ENV'];
		}

		switch ($env) {
			case 'vagrant':
			case 'dev':
				$css = 'background: #ff9999;';
				break;

			case 'staging':
				$css = 'background: #e5c627;';
				break;

			case 'prod':
			default:
				$css = '';
				break;
		}

		if (!empty($css) && Configure::read('debug') > 0) {
			return sprintf($format, $css);
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
