<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('AppEmail', 'Lib/Network/Email');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	/**
	 * Components to load for all controllers.
	 *
	 * @var array
	 */
	public $components = array(
		'Session', // => array('className' => 'AppSession'),
		'DebugKit.Toolbar',
		'Paginator',
		'Auth' => array(
		//	'authError' => 'You must be logged in to access this page.',
		//	'loginRedirect' => '/',
		//	'logoutRedirect' => '/',
		//	'loginAction' => array(
		//		'controller' => 'users',
		//		'action' => 'login',
		//		'plugin' => false,
		//	),
		//	'authenticate' => array(
		//		'Form' => array(
		//			'passwordHasher' => 'Blowfish',
		//			'fields' => array(
		//				'username' => 'email',
		//				'password' => 'password',
		//			),
		//			'scope' => array(
		//				'User.active' => 1,
		//			),
		//			'contain' => array(),
		//		),
		//	),
		//	'authorize' => array(
		//		'Controller'
		//	),
		),
	);

	/**
	 * Helpers list to load for all controllers
	 *
	 * @var array
	 */
	public $helpers = array(
		'Session',
		'Html',
		'TB' => array('className' => 'TwitterBootstrap'),
	);

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
	 * beforeFilter
	 *
	 * @access	public
	 * @return	void
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (isset($this->request->params['admin'])) {
			$this->layout = 'admin';
		}

		$this->__auth();
	}

	/**
	 * Load data used by all views, such the current logged in user (to
	 * avoid the use of AuthComponent::user() in views.) Conditionals
	 * in views should look like this:
	 *
	 * if ($u && $u['role'] == 'student') {
	 * 	//do something
	 * }
	 *
	 * @access	public
	 * @return	void
	 */
	public function beforeRender() {
		parent::beforeRender();
		$this->set('u', $this->Auth->user()); // Will be null when not logged in.
	}

	/**
	 * isAuthorized
	 *
	 * When the URL does not contain any routing prefixes (a public page
	 * like /pages/about), always allow access. Otherwise require the
	 * logged-in user's role to match the prefix used in the URL, otherwise
	 * block access.
	 *
	 * @access	public
	 * @return	boolean		True if the User.role matches the routing prefix or if there is no prefix. False otherwise.
	 */
	public function isAuthorized() {
		// None of the configured routing prefixes are present in the URL params, so allow public access.
		$prefixesPresent = array_intersect(Configure::read('Routing.prefixes'), array_keys($this->request->params));
		if (empty($prefixesPresent)) {
			return true;
		}

		// Otherwise the user's role must match the URL's admin prefix requested.
		if (array_key_exists($this->Auth->user('role'), $this->request->params)) {
			return true;
		}

		return false;
	}

	/**
	 * Instantiates and returns an instance of the application's email
	 * handler class, AppEmail.
	 *
	 * @access	public
	 * @param	string	$config	The name of the CakeEmail config class to use.
	 * @return	AppEmail		Instance of the subclassed CakeEmail class.
	 */
	public function emailFactory($config = null) {
		return new AppEmail($config);
	}
}
