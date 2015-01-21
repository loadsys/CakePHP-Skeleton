<?php
class BakeTemplateHelper {

	/**
	 * arrayToString
	 *
	 * Takes a PHP array and formats it into a string that is valid PHP
	 * array syntax. Numeric indices are stripped, and indenting for all
	 * lines except the first is formatted to the $depth specified. No
	 * semi-colon is appended to the end. The output will look like:
	 * "array( ... )"
	 *
	 * @access	public
	 * @param	array	$a		An array to be converted.
	 * @param	int		$depth	Positive integer. How many tab levels to indent all lines except the first in the final formatted string.
	 * @return	string			A string containing valid PHP array syntax, with no trailing semi-colon.
	 */
	public static function arrayToString($a, $depth = 1) {
		// Convert to a string that looks like valid PHP array syntax.
		$s = var_export($a, true);

		// Strip leading numeric indices like "0 =>" from array elements.
		$s = preg_replace('/^(\s*)\d+\s*\=\>\s*/m', '\1', $s);

		// Replace leading spaces with tabs for proper (relative) indenting.
		$replFunc = function ($matches) {
			return str_repeat("\t", strlen($matches[1]) / 2);
		};
		$s = preg_replace_callback('/^(\s{2,})/m', $replFunc, $s);

		// Set the overal indent depth properly.
		$s = join(explode(PHP_EOL, $s), PHP_EOL . str_repeat("\t", max(0, $depth)));

		return $s;
	}
}
