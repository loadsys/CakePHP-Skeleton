<?php
/**
 * Additional TestCase to provide common setup for testing Cake Shells.
 */

/**
 * Provides a setup method for mocking a Shell with all the normal
 * (necessary!) bits. Your test class should call
 * `App::uses('ShellTestCase', 'Test');` and should `extend ShellTestCase`.
 * In your `setUp()` method, or the beginning of your test method itself,
 * call `$this->initSUT()` with an array of Shell method names you wish to
 * mock.
 */
class ShellTestCase extends CakeTestCase {

	/**
	 * Helper for setting up an instance of the target Shell with proper
	 * mocked methods.
	 *
	 * The Shell that will be mocked is taken from the test class name
	 * automatically. Example: `SomeShellTest extends CakeTestCase` will
	 * create a mocked copy of `SomeShell`. Will check for a subclassed
	 * `TestSomeShell` and instantiate that instead, if available, to
	 * allow for overriding protected methods.
	 *
	 * All of the fixtures defined in the test class will be "installed"
	 * into the mocked Shell.
	 *
	 * Typically called in ::setUp() or at the beginning
	 * of a test method (if additional mocked methods are necessary.)
	 *
	 * @return mixed	A partially mocked copy of the Shell matching the test class's name.
	 */
	protected function initSUT($additionalMocks = array()) {
		$defaultMocks = array(
			'in', 'out', 'hr', 'help', 'error', 'err', '_stop', 'initialize', '_run', 'clear',
		);
		$this->out = $this->getMock('ConsoleOutput', array(), array(), '', false);
		$this->in = $this->getMock('ConsoleInput', array(), array(), '', false);

		$class = preg_replace('/(.*)Test$/', '\1', get_class($this));
		$class = (class_exists("Test{$class}") ? "Test{$class}" : $class);
		$shell = $this->getMock(
			$class,
			array_merge($defaultMocks, $additionalMocks),
			array($this->out, $this->out, $this->in)
		);

		$shell->OptionParser = $this->getMock('ConsoleOptionParser', array(), array(null, false));
		// Load and attach all fixtures defined in this test case.
		foreach ($this->fixtures as $fixture) {
			$modelName = str_replace('App.', '', implode('.', array_map('Inflector::classify', explode('.', $fixture))));
			$propName = str_replace('.', '', $modelName);
			$shell->{$propName} = ClassRegistry::init($modelName);
		}
		return $shell;
	}
}