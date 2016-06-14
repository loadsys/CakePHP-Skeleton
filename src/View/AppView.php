<?php
/**
 * AppView class used for display all normal view instances.
 */
namespace App\View;

use Cake\View\View;

/**
 * \App\View\AppView
 */
class AppView extends View {
	/**
	 * Initialization hook method.
	 *
	 * For e.g. use this method to load a helper for all views:
	 * `$this->loadHelper('Html');`
	 *
	 * @return void
	 */
	public function initialize() {
		$this->loadHelper('Html', []);
		$this->loadHelper('Form', [
			'errorClass' => 'error',
			'templates' => [
				'error' => '<small class="error">{{content}}</small>',
				'radioContainer' => '<div class="form-radio large-12 columns">{{content}}</div>',
				'dateWidget' => '
					<div class="row">
						<div class="large-4 medium-4 columns">{{month}}</div>
						<div class="large-4 medium-4 columns">{{day}}</div>
						<div class="large-4 medium-4 columns">{{year}}</div>
					</div>
				',
			],
		]);
		$this->loadHelper('Flash');
	}
}
