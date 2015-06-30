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
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Console;

use Composer\Script\Event;
use Exception;

/**
 * Provides installation hooks for when this application is installed via
 * composer. Customize this class to suit your needs.
 */
class LoadsysInstaller
{

    /**
     * Callback method called via composer post the installation of this application.
     *
     * @param \Composer\Script\Event $event The composer event object.
     * @return void
     */
    public static function postInstall(Event $event)
    {
        $io = $event->getIO();
        $rootDir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;

        static::welcome($io);
        $config = new InstallerConfigurer($event);
        static::parseTemplates($config, $event, $rootDir);

    }

    /**
     * Welcome message called from the postInstall method of this class.
     *
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @return void
     */
    protected static function welcome($io)
    {
        $io->write('<info>Thanks for using the Loadsys CakePHP 3 project skeleton!</info>');
        $io->write('');
        if ($io->isVerbose()) {
            $io->write('The installer will scan for filenames ending with `.template` and build a list of prompts.');
            $io->write('If you leave a prompt blank (no input) the token will not be replaced so that you may');
            $io->write('replace it manually. If you would like the token to be removed you may enter a single space.');
            $io->write('');
        }
    }

    /**
     * Finds *.template files and parses tokens strings within each.
     *
     * @param InstallerConfigurer $config InstallerConfigurer instance.
     * @param Composer\Script\Event $event Composer's Event instance
     * @param string $rootDir The application's root directory.
     * @return void
     */
    protected static function parseTemplates(InstallerConfigurer $config, Event $event, $rootDir)
    {
        $fileParser = new FileParser($event, $rootDir);
        $templates = $fileParser->findTemplates();

        $tokens = $fileParser->getTokensInFiles($templates);

        foreach ($tokens as $token => $default) {
            $value = $config->prompt($token, $default);
            $config->write($token, $value);
        }

        foreach ($templates as $template) {
            $fileParser->parseTemplate($template, $config->read());
        }
    }

    /**
     * Asks a question with a yes or no answer to the user and returns a boolean.
     *
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @param string $question Question to ask user with a yes or no answer.
     * @param string $default default value. Any of 'Y', 'y', 'N', or 'n'
     * @throws \Exception Exception raised by validator.
     * @return bool user's aster to $question
     */
    protected static function askBool($io, $question, $default = 'Y')
    {
        $validator = (function ($arg) {
            if (in_array($arg, ['Y', 'y', 'N', 'n'])) {
                return $arg;
            }
            throw new Exception('Please enter Y or n.');
        });
        $input = $io->askAndValidate($question, $validator, 10, $default);

        return in_array($input,['Y', 'y']);
    }

}
