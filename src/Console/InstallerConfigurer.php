<?php

namespace App\Console;

use Composer\Script\Event;
use Exception;

/**
 * Provides installation hooks for when this application is installed via
 * composer. Customize this class to suit your needs.
 */
class InstallerConfigurer
{

    /**
     *
     */
    protected $config = [];

    /**
     *
     * @param Composer\Script\Event $event Composer's event object
     */
    public function __construct(Event $event)
    {
        $this->dir = dirname(dirname(__DIR__));
        $this->io = $event->getIO();
    }

    public function write($key, $value)
    {
        $this->config[$key] = $value;
    }

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

    public function prompt($prompt, $default = '')
    {
        if (empty($default)) {
            $default = $prompt;
        }
        $value = $this->io->askAndValidate(
            $this->formatPrompt($prompt, $default),
            (function($input) {return $input;}),
            10,
            $default
        );

        return $value;
    }

    protected function formatPrompt($prompt, $default = '')
    {
        $out = '<info>' . $this->humanize($prompt) . '</info>';
        if ($prompt != $default) {
            $out .= ' [' . $default . ']';
        }
        $out .= ': ';

        return $out;
    }

    /**
     * Ensure a string matches the token format
     */
    public function humanize($string) {
        return ucwords(strtolower(str_replace(['{{', '}}', '_'], ['', '', ' '], $string)));
    }

}
