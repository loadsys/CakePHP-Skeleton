<?php
/**
 * Provides a read/write interface to configuration values.
 *
 * Also provides an interface for obtaining values from console
 * prompts and user input. Used by the LoadsysInstaller to process
 * template file tokens and values.
 */

namespace App\Console;

use Composer\Script\Event;

/**
 * InstallerConfigurer class
 */
class InstallerConfigurer
{

    /**
     * Stores configuration values.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Stores an instance of Composer's I/O interface.
     *
     * @var IOInterface|\Composer\IO\IOInterface
     */
    protected $io = null;

    /**
     * Initialize a new Configurer instance.
     *
     * Saves a reference to Composer's I/O interface for later use.
     *
     * @param Composer\Script\Event $event Composer's event object
     */
    public function __construct(Event $event)
    {
        $this->io = $event->getIO();
    }

    /**
     * Read a config value (or all values) from the store.
     *
     * @param string $key The key to obtain. If null, all keys are returned as an array.
     * @return mixed The value for the named $key if present, all values when $key = null, or false on lookup failure.
     */
    public function read($key = null)
    {
        if (!$key) {
            return $this->config;
        }
        if (!array_key_exists($key, $this->config)) {
            return false;
        }
        return $this->config[$key];
    }

    /**
     * Write a new config value into the store.
     *
     * @param string $key The key in which to store the $value.
     * @param mixed $value The value to store under the key $key.
     * @return void
     */
    public function write($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * Convert a raw token name into a humanized string.
     *
     *    `{{TOKEN_NAME}}` --> `Token Name`
     *
     * @param string $string The raw token string obtained from a template file.
     * @return string A human-readable version of the token name.
     */
    public function humanize($string) {
        $replacements = [
            '{{' => '',
            '}}' => '',
            '_' => ' ',
        ];
        return ucwords(
            strtolower(
                str_replace(
                    array_keys($replacements),
                    array_values($replacements),
                    $string
                )
            )
        );
    }

    /**
     * Use the I/O interface to prompt the user and return the value obtained.
     *
     * When a blank $defaultValue value is provided, the name of the $token is
     * used instead. This has the side-effect of forcing  ::formatPrompt()
     * to suppress the ` [default]` part of its output.
     *
     * @param string $token The string to present to the user before receiving input.
     * @param string $defaultValue The default value to return if the user enters nothing.
     * @return string The user's input if non-empty, otherwise the $defaultValue value.
     */
    public function prompt($token, $defaultValue = '')
    {
        if (empty($defaultValue)) {
            $defaultValue = $token;
        }
        $value = $this->io->askAndValidate(
            $this->formatPrompt($token, $defaultValue),
            function($input) { return $input; },
            10,
            $defaultValue
        );

        return $value;
    }

    /**
     * Apply formatting to a user token.
     *
     * Presents the token and the default value that will be used in a
     * conventional format.
     *
     * @param string $token The token name to present to the user before receiving input.
     * @param string $defaultValue The default value to return if the user enters nothing.
     * @return string The formatted prompt string (including the default value that will be used).
     */
    protected function formatPrompt($token, $defaultValue = '')
    {
        $out = '<info>' . $this->humanize($token) . '</info>';
        if ($token != $defaultValue) {
            $out .= ' [' . $defaultValue . ']';
        }
        $out .= ': ';

        return $out;
    }
}
