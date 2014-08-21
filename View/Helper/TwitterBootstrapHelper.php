<?php
/**
 * TwitterBootstrap Helper
 */
App::uses('AppHelper', 'View/Helper');
App::uses('HtmlHelper', 'View/Helper');

/**
 * TwitterBootstrapHelper helper
 *
 * Provides shortcut access to complicated Bootstrap mechanisms. Mostly nikked
 * from https://github.com/loadsys/twitter-bootstrap-helper
 *
 * For brevity, add to your AppController like so:
 *
 * var $helpers = array(
 *	'TB' => array('className' => 'TwitterBootstrap'),
 * )
 *
 * You will then be able to access methods via `$this->TB->method()` in your
 * views.
 *
 * @package       app.View.Helper
 */
class TwitterBootstrapHelper extends HtmlHelper {

	/**
	 * Helpers this helper depends on.
	 *
	 * @var array
	 */
	public $helpers = array(
		'Html',
		'Form',
	);

	/**
	 * Wraps the `Html->link()` method and applies the Bootstrap classes to the
	 * options array before passing it on.
	 *
	 * @param mixed $title
	 * @param mixed $url
	 * @param array $opt
	 * @param mixed $confirm
	 * @access public
	 * @return string
	 */
	public function buttonLink($title, $url, $opt = array(), $confirm = false) {
		return $this->link($title, $url, $this->_buttonOptions($opt), $confirm);
	}

	/**
	 * Wraps the `Html->postLink()` method to create a POST-style link that uses
	 * Bootstrap button styles.
	 *
	 * @param mixed $title
	 * @param mixed $url
	 * @param array $opt
	 * @param mixed $confirm
	 * @access public
	 * @return string
	 */
	public function buttonPost($title, $url, $opt = array(), $confirm = false) {
		return $this->Form->postLink($title, $url, $this->_buttonOptions($opt), $confirm);
	}

	/**
	 * Takes the array of options from `::buttonLink()` and `::postLink
	 * and returns the modified array with the bootstrap classes added. If
	 * $options is a string, it is assumed to be a short `btn-___` style name.
	 *
	 * @access protected
	 * @param mixed $options
	 * @return array
	 */
	protected function _buttonOptions($options) {
		$validStyles = array('default', 'primary', 'success', 'info', 'warning', 'danger', 'link');
		$validSizes = array('xs', 'sm', 'lg', 'block');
		if (is_string($options)) {
			$options = array('style' => $options);
		}

		$class = array('btn') + (isset($options['class']) ? explode(' ', $options['class']) : array());

		if (isset($options['style']) && in_array($options['style'], $validStyles)) {
			$class[] = "btn-{$options['style']}";
		}

		if (isset($options['size']) && in_array($options['size'], $validSizes)) {
			$class[] = "btn-{$options['size']}";
		}

		if (isset($options['disabled']) && (bool)$options['disabled']) {
			$class[] = 'disabled';
		}

		if (isset($options['active']) && (bool)$options['active']) {
			$class[] = 'active';
		}

		unset($options['style']);
		unset($options['size']);
		unset($options['disabled']);
		unset($options['active']);

		$options['class'] = implode(' ', $class);
		return $options;
	}

	/**
	 * Returns the breadcrumb trail as a sequence of <li>s containing links. The
	 * `$separator` argument is ignored since it is defined by Bootstrap in CSS:
	 * `.breadcrumb>li+li:before { content: '/'; }`. (The arg remains in place to
	 * maintain interface compatibility with the HtmlHelper.) The final breadcrumb
	 * is expected to be a link, but will be `text-muted` to maintain the style.
	 *
	 * If `$startText` is an array, the accepted keys are:
	 *
	 * - `text` Define the text/content for the link.
	 * - `url` Define the target of the created link.
	 *
	 * All other keys will be passed to HtmlHelper::link() as the `$options` parameter.
	 *
	 * @param string $separator Text to separate crumbs.
	 * @param string|array|boolean $startText This will be the first crumb, if
	 * false it defaults to first crumb in array. Can also be an array, see above for details.
	 * @param array $options Options to supply to `HtmlHelper->tag('ul')` when
	 * creating the final element. [class => breadcrumb] will be automatically added.
	 * @return string Composed bread crumbs
	 * @link  http://book.cakephp.org/2.0/en/core-libraries/helpers/html.html#creating-breadcrumb-trails-with-htmlhelper
	 */
	public function getCrumbs($separator = 'ignored', $startText = false, $options = array('class' => 'breadcrumbs')) {
		$options = $this->_setGetCrumbsOptions($options);
		$crumbs = $this->_prepareCrumbs($startText);
		if (empty($crumbs)) {
			return null;
		}

		$out = PHP_EOL;
		end($crumbs);
		$lastKey = key($crumbs);

		foreach ($crumbs as $i => $c) {
			$attrs = array();
			if ($i === $lastKey) {
				$attrs = array('class' => 'active');
				$c[2]['class'] = (isset($c[2]['class']) ? ($c[2]['class'] . ' text-muted') : 'text-muted');
			}
			$out .= $this->tag('li', $this->link($c[0], $c[1], $c[2]), $attrs) . PHP_EOL;
		}
		return $this->tag('ul', $out, $options);
	}

	/**
	 * Ensure that the mandatory options for ::getCrumbs are present and
	 * initialized. (Currently this is just [class => breadcrumb].)
	 *
	 * @access protected
	 * @param array $options Options provided to ::getCrumbs().
	 * @return array The provided $options with minimum values initialized.
	 */
	protected function _setGetCrumbsOptions($options) {
		$classes = explode(' ', (string)@$options['class']);
		$classes[] = 'breadcrumb';
		$classes = array_flip(array_flip($classes)); // Remove duplicate elements without changing order.
		$options['class'] = trim(implode(' ', $classes));
		return $options;
	}
}
