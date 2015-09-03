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
		$this->loadHelper('Html');
		$this->loadHelper('Form');
		$this->loadHelper('Flash');
	}
}
