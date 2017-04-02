<?php
/**
 * AppView class used for display all normal view instances.
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link http://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View {
	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading helpers.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->loadHelper('Html', [
		]);
		$this->loadHelper('Form', [
			'errorClass' => 'error',
			'templates' => [
				'error' => '<small class="error">{{content}}</small>',
			],
		]);
		$this->loadHelper('Flash');
	}
}
