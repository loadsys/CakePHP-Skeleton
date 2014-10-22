<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller. Will selectively load /admin/pages/filename
 * URLs for admin users.
 */
class PagesController extends AppController {

	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public $uses = array();

	/**
	 * __auth
	 *
	 * Allows controllers to change auth access without having to override
	 * the entire beforeFilter.
	 *
	 * @access	public
	 * @return	void
	 */
	public function __auth() {
		$this->Auth->allow(array('display'));
	}

	/**
	 * Displays a view
	 *
	 * @param	string	What page to display
	 */
	public function display() {
		return $this->_display(func_get_args(), '');
	}

	/**
	 * Displays an admin view
	 *
	 * @param	string	What page to display
	 */
	public function admin_display() {
		return $this->_display(func_get_args(), 'admin');
	}

	/**
	 * Does the heavy lifting for determing what file to view, if permitted.
	 *
	 * @throws	NotFoundException
	 * @param	string	$path	The partial URL path requested.
	 * @param	string	$prefix	The routing prefix (if any) in use for this request (to restrict access depending on role.)
	 */
	protected function _display($path, $prefix = '') {
		$count = count($path);
		if (!$count) {
			return $this->redirect('/');
		}
		$page = $subpage = $title = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title = Inflector::humanize($path[$count - 1]);

			// Block any prefixed page requests when no URL prefix present.
			// (Stops `/pages/admin_pagename` from working, but not `/admin/pages/pagename`.)
			if (empty($prefix) && $this->_nameIsPrefixed($path[$count - 1])) {
				throw new NotFoundException(__('Invalid page'));
			} elseif (!empty($prefix)) {
				// Auto-prepend the $prefix to the final component of the path.
				$path[$count - 1] = "{$prefix}_" . $path[$count - 1];
			}
		}
		$this->set(compact('page', 'subpage'));
		$this->set('title_for_layout', $title);
		$this->render(implode('/', $path));
	}

	/**
	 * Helper for determining if the provided filename begins with any of
	 * the app's Configured routing prefixes.
	 *
	 * @param	string	$ctpName	The final component of the requested path name.
	 * @return	bool				True if $ctpName starts with any of the app's
	 *								configured Routing.prefixes, false otherwise.
	 */
	protected function _nameIsPrefixed($ctpName) {
		// If there are no active routing prefixes, the file is not considered to be prefixed.
		if (!($allPrefixes = Configure::read('Routing.prefixes'))) {
			return false;
		}

		// If the name is determined to start with any of the prefixes, return true.
		$f = function($carry, $v) use ($ctpName) {
			return ($carry ?: strpos($ctpName, "{$v}_") === 0);
		};
		if (array_reduce($allPrefixes, $f, false)) {
			return true;
		}

		// If all other checks drop through, name is not prefixed.
		return false;
	}
}
