<?php
/**
 * AppSessionComponent
 */

App::uses('Component', 'Controller');
App::uses('SessionComponent', 'Controller/Component');

/**
 * AppSessionComponent Component
 */
class AppSessionComponent extends SessionComponent {

	/**
	 * Used to set a session variable that can be used to output messages in the view.
	 *
	 * In your controller: $this->Session->setFlash('This has been saved', null, 'danger'); // or 'info', 'warning', 'success', 'primary'
	 *
	 * Additional params below can be passed to customize the output, or the Message.[key].
	 * You can also set additional parameters when rendering flash messages. See SessionHelper::flash()
	 * for more information on how to do that.
	 *
	 * @param string $message Message to be flashed
	 * @param string $element Element to wrap flash message in.
	 * @param string|array $params Parameters to be sent to layout as view variables. If string, will be used as a class name.
	 * @param string $key Message key, default is 'flash'
	 * @return void
	 */
	public function setFlash($message, $element = 'Layouts/flash_bootstrap', $params = array(), $key = 'flash') {
		// If element isn't defined set to the flash element
		if (!$element) {
			$element = 'Layouts/flash_bootstrap';
		}

		// Default params for the Session::setFlash function
		$defaultParams = array(
			'class' => 'alert-info',
		);

		if (is_string($params)) {
			if (strpos($params, 'alert-') === false) {
				$params = "alert-{$params}";
			}
			$params = array(
				'class' => $params,
			);
		}

		// Merge passed in params with default params
		$params = Hash::merge($defaultParams, $params);

		// If key isn't defined set to the default
		if (!$key) {
			$key = 'flash';
		}

		// Call the parent  method
		parent::setFlash($message, $element, $params, $key);
	}
}
