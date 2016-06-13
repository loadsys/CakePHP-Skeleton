<?php
/**
 * Class to wrap up Custom logging situations for quick/easy callability.
 *
 * Add additional convenience methods here to allow your classes to log
 * specific data using simple calls.
 */
namespace App\Lib\Log;

use Cake\Error\Debugger;
use Cake\Log\Log;
use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Psr\Log\LogLevel;

/**
 * \App\Lib\Log\LogTrait
 */
trait LogTrait {
	/**
	 * Convenience method to write a message to Log. See Log::write()
	 * for more information on writing to logs.
	 *
	 * @param mixed $msg Log message.
	 * @param int|string $level Error level.
	 * @param string|array $context Additional log data relevant to this message.
	 * @return bool Success of log write.
	 */
	public function log($msg, $level = LogLevel::ERROR, $context = []) {
		return Log::write($level, $msg, $context);
	}

	/**
	 * Log a Performance informational message, this both resets the default log
	 * level to info as well as adds the context scope of performance.
	 *
	 * This appends a Debugger trace with a depth of 10 to provide contextual
	 * information about where this performance message was called from.
	 *
	 * @param mixed $msg Log message.
	 * @param int|string $level Error level, standard level is `info`.
	 * @param string|array $context Additional log data relevant to this message.
	 * @return void
	 */
	public function logPerformance($msg, $level = LogLevel::INFO, $context = []) {
		$context['scope'] = ['performance'];
		$msg .= "\n" . Debugger::trace(['depth' => 10, 'format' => 'log']) . "\n";
		$this->log($msg, $level, $context);
	}
}
