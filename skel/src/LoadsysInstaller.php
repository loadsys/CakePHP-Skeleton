<?php
/**
 *
 */
namespace Skel;

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
        static::parseTemplates($event, $rootDir);

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
     * @param Composer\Script\Event $event Composer's Event instance
     * @param string $rootDir The application's root directory.
     * @return void
     */
    protected static function parseTemplates(Event $event, $rootDir)
    {
        $config = new InstallerConfigurer($event);
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
}
