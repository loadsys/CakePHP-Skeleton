<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Lib\Log\LogTrait;
use Cake\Controller\Controller;
use Cake\Controller\Exception\SecurityException;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\Routing\Router;
use LibRegistry\LibRegistryTrait;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	// Allow access to shared/injected libraries, works like TableRegistry.
	use LibRegistryTrait;

	// Enable logging as a class method
	use LogTrait;

	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading components.
	 *
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		$this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');
		$this->loadComponent('Auth', [
			'className' => 'AuthUserEntity.UserEntityAuth',
			'entityClass' => '\Cake\ORM\Entity',
			'entityOptions' => [
				'associated' => [],
			],
		]);
		$this->loadComponent('Security', [
			'blackHoleCallback' => 'blackHole',
		]);
		$this->loadComponent('Csrf');
	}

	/**
	 * Allows controllers to change auth access without having to override
	 * the entire beforeFilter callback.
	 *
	 * @return void
	 */
	protected function auth() {
	}

	/**
	 * If SSL is enforced, use the Security Component to require SSL for all
	 * actions.
	 *
	 * @return void
	 */
	protected function ssl() {
		if (Configure::read('Defaults.ssl_force')) {
			$this->Security->requireSecure();
		}
	}

	/**
	 * Process a Security Component BlackHoled request.
	 *
	 * @param string $type Error type, this is either `secure` or `auth`.
	 * @param \Cake\Controller\Exception\SecurityException $exception Additional debug info describing the cause.
	 * @return void
	 * @throws \Cake\Controller\Exception\SecurityException in the advent of debug being true.
	 * @link http://book.cakephp.org/3.0/en/controllers/components/security.html#handling-blackhole-callbacks
	 */
	public function blackHole($type, SecurityException $exception) {
		$this->log(
			sprintf(
				'Security Component black-holed this request: Request URL: %s Exception Type: %s Exception Message: %s Exception Reason: %s',
				$this->request->here(),
				$exception->getType(),
				$exception->getMessage(),
				$exception->getReason()
			),
			'error',
			['scope' => ['security']]
		);

		if (Configure::read('debug')) {
			throw $exception;
		}

		switch ($exception->getType()) {
			case 'secure':
				$this->forceSsl();
				break;
			case 'auth':
			default:
				$this->authError();
				break;
		}
	}

	/**
	 * Handle the case of an `auth` type error for the Security Component. Which
	 * indicates a form validation error, or a controller/action mismatch error.
	 *
	 * @return void
	 * @throws \Cake\Network\Exception\BadRequestException Throws an BadRequestException
	 *         on this method being called.
	 */
	protected function authError() {
		throw new BadRequestException('There was an error with your request. Please, try again.');
	}

	/**
	 * Handles the case of the Security Component, having a secure type of blackhole.
	 * Indicates the current rquest should have been using SSL and was not.
	 * Redirect the current request to one using SSL.
	 *
	 * @return \Cake\Network\Response Redirects the current request to an SSL request.
	 */
	protected function forceSsl() {
		$secureUrl = Router::url($this->request->here(), true);
		$secureUrl = str_replace('http://', 'https://', $secureUrl);
		return $this->redirect($secureUrl, (Configure::read('debug') ? 302 : 301));
	}

	/**
	 * validate that a User's role matches the prefixed route
	 *
	 * @param array $user The array of User properties
	 * @return bool Returns true if the User's role matches the prefix
	 */
	public function isAuthorized($user = null) {
		// Default deny
		return false;
	}

	/**
	 * Called before the controller action. You can use this method to configure and
	 * customize components or perform logic that needs to happen before each
	 * controller action.
	 *
	 * @param Event $event An Event instance
	 * @return void
	 * @link http://book.cakephp.org/3.0/en/controllers.html#request-life-cycle-callbacks
	 */
	public function beforeFilter(Event $event) {
		$this->ssl();
		$this->auth();
	}

	/**
	 * Called after the controller action is run, but before the view is rendered.
	 * You can use this method to perform logic or set view variables that are
	 * required on every request.
	 *
	 * @param \Cake\Event\Event $event An Event instance
	 * @return void
	 * @link http://book.cakephp.org/3.0/en/controllers.html#request-life-cycle-callbacks
	 */
	public function beforeRender(Event $event) {
		$u = $this->Auth->userEntity(); // Will be null when not logged in.
		$this->set(compact('u'));
	}
}
