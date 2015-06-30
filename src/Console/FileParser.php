<?php
/**
 * Provides an interface for parsing and processing *.template files
 * with `{{TOKEN}}`s in them.
 */

namespace App\Console;

use Composer\Script\Event;

/**
 * FileParser class
 */
class FileParser
{

    /**
     * Stores the project root path against which to read files.
     *
     * Should include a trailing DIRECTORY_SEPARATOR.
     *
     * @var string
     */
    protected $dir = null;

    /**
     * Stores an instance of Composer's I/O interface.
     *
     * @var IOInterface|\Composer\IO\IOInterface
     */
    protected $io = null;

    /**
     * Defines the regular expression used to match tokens.
     *
     * Allowed formats are:
     *    * `{{TOKEN}}` - Token only, no default.
     *    * `{{TOKEN_NAME:}}` - Token with blank default.
     *    * `{{AM_I_AWESOME:yes}}` - Token with provided default value.
     *
     * @var string
     */
    protected $tokenExpression = '/\{{2}([A-Z0-9_]+):?([^\}]*)?\}{2}/';

    /**
     * Builds a FileParser object.
     *
     * @param Composer\Script\Event $event Composer's event object
     */
    public function __construct(Event $event, $dir)
    {
        $this->dir = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR;
        $this->io = $event->getIO();
    }

    /**
     * Ensure a string matches the token format.
     *
     * Converts a token name and default value back into the tokenized format.
     *
     * @param string $string The token name.
     * @param string $default Default value for the token.
     * @return string The tokenized version of the provided token name and default value.
     */
    public function tokenize($string, $default = null) {
        $out = '{{' . $string;
        if (!is_null($default)) {
            $out .= ':' . $default;
        }

        return $out . '}}';
    }

    /**
     * Convenience function to get tokens from an array of files.
     *
     * @param array Array of file paths to read, each relative to ::$dir.
     * @return array Array of tokens found in all files.
     */
    public function getTokensInFiles(array $files)
    {
        $tokens = [];
        foreach ($files as $file) {
            $tokens = array_merge($tokens, $this->getTokensInFile($file));
        }
        return array_unique($tokens);
    }

    /**
     * Gets a list of tokens found in $file.
     *
     * @param string $file Relative filesystem path from ::$dir to the file.
     * @return array An array of [token => default value]s found in the file.
     */
    public function getTokensInFile($file)
    {
        $file = $this->dir . $file;

        // Skip unreadable files.
        if (!is_readable($file)) {
        	return [];
        }

        $this->writeVerbose('Reading tokens from `' . $file . '`...');
        $fileContents = file_get_contents($file);
        if (!$fileContents) {
            return [];
        }
        preg_match_all($this->tokenExpression, $fileContents, $matches);

        return array_combine($matches[1], $matches[2]);
    }

    /**
     * Recursively searches for files ending with .template in the skeleton directory.
     *
     * @return array List of *.template files.
     */
    public function findTemplates()
    {
        $templates = [];
        $Regex = new \RegexIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->dir)
            ),
            '/^.+\.template$/',
            \RecursiveRegexIterator::GET_MATCH
        );

        foreach ($Regex as $file) {
            $template = str_replace($this->dir, '', $file[0]);
            $templates[] = $template;
            $this->writeVerbose('Found template: `' . $template . '`');
        }

        return $templates;
    }

    /**
     * Parse a given template, replacing text matching a config key with the config value.
     *
     * @param string $template File system path to the file to read.
     * @param array $config Array of discovered [token => value] pairs.
     * @return true Always true.
     */
    public function parseTemplate($template, $config)
    {
        $fs = new \Composer\Util\Filesystem;
        $this->writeVerbose('Parsing template: ' . $template);
        $template = $this->dir . $template;
        $file = str_replace('.template', '', $template);

        foreach ($config as $token => $value) {
            if ($token == $value) {
                continue;
            }
            if ($value === ' ') {
                $value = '';
            }
            $this->replaceInFile($this->tokenize($token, $value), $value, $template);
        }

        $this->writeVerbose('Replacing `' . $file . '` with `' . $template . '`');
        $fs->copyThenRemove($template, $file);
        return true;
    }

    /**
     * Replaces all occourences of $search with $replace in $file.
     *
     * @param string $search The text to be replaced.
     * @param string $replace The text to replace $search with.
     * @param string $file Path to the file to perform the search and replace.
     * @return mixed Number of occurences of $search replaced, false on failure.
     */
    protected function replaceInFile($search, $replace, $file)
    {
        $this->writeVerbose('Replacing "' . $search . '" with "' . $replace . '" in `' . $file . '`');
        $contents = file_get_contents($file);
        if ($contents === false) {
            $this->writeVerbose('Unable to read from file: `' . $file . '`');
            return false;
        }
        $contents = str_replace($search, $replace, $contents, $count);
        if (file_put_contents($file, $contents) === false) {
            $this->writeVerbose('Unable to write to file: `' . $file . '`');
            return false;
        }

        return $count;
    }

    /**
     * Output a <comment> string only if verbose output is enabled.
     *
     * @param string $string The text echo.
     * @return void
     */
    protected function writeVerbose($string) {
        if ($this->io->isVerbose()) {
            $this->io->write('<comment>' . $string . '</comment>');
        }
    }
}
