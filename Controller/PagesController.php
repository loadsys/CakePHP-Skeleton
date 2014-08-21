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
	 * @throws	NotFoundException
	 * @param	string	What page to display
	 */
	public function display() {
		$path = func_get_args();

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
			// Redirect any `admin_` prefixed requests to /.
			if (strpos($path[$count - 1], 'admin_') === 0) {
				throw new NotFoundException(__('Invalid page'));
			}
		}
		$this->set(compact('page', 'subpage'));
		$this->set('title_for_layout', $title);
		$this->render(implode('/', $path));
	}

	/**
	 * Displays an admin view
	 *
	 * @param	string	What page to display
	 */
	public function admin_display() {
		$path = func_get_args();

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
			// Auto-prepend "admin_" to the final component of the path.
			$path[$count - 1] = 'admin_' . $path[$count - 1];
		}
		$this->set(compact('page', 'subpage'));
		$this->set('title_for_layout', $title);
		$this->render(implode('/', $path));
	}
}
