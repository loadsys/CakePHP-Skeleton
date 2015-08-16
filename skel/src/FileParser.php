<?php
/**
 * Provides an interface for parsing and processing *.template files
 * with `{{TOKEN}}`s in them.
 */

namespace Skel;

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
    protected $tokenMatchRegex = '/\{{2}([A-Z0-9_]+)(?::([^\}]*))?\}{2}/';

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
        return $tokens;
    }

    /**
     * Gets a list of tokens found in $file.
     *
     * @param string $file Relative filesystem path from ::$dir to the file.
     * @return array An array of [token => default value]s found in the file.
     */
    public function getTokensInFile($source)
    {
        $file = $this->dir . $source;

        // Skip unreadable files.
        if (!is_readable($file)) {
        	return [];
        }

        $this->writeVerbose('Reading tokens from `' . $source . '`...');
        $fileContents = file_get_contents($file);
        if (!$fileContents) {
            return [];
        }
        preg_match_all($this->tokenMatchRegex, $fileContents, $matches);

        return array_combine($matches[1], $matches[2]);
    }

    /**
     * Process a given template, replacing all recognized tokens with the
     * user-entered or default value.
     *
     * Once replacement is complete, the file is copied to the
     * non-`*.template` version of the file name (overwriting any existing
     * file) and the .template file is removed.
     *
     * @param string $template File system path to the file to read.
     * @param array $config Array of discovered [token => value] pairs.
     * @return true Always true.
     */
    public function parseTemplate($template, $config)
    {
        $this->writeVerbose('Parsing template: ' . $template);

        $source = $this->dir . $template;
        $destination = str_replace('.template', '', $source);

        $replacer = function(array $matches) use ($config) {
            $tokenName = $matches[1];
            if (!array_key_exists($tokenName, $config)) {
                return $matches[0]; // Can't replace-- no value available.
            }
            $replacement = $config[$tokenName];
            if ($tokenName === $replacement) {
                return $matches[0]; // Don't replace.
            }
            if ($replacement === ' ') {
                $replacement = '';
            }

            $this->writeVerbose("\tReplacing `{$tokenName}` with `{$replacement}`.");
            return $replacement;
        };
        $contents = file_get_contents($source);
        $contents = preg_replace_callback($this->tokenMatchRegex, $replacer, $contents);

        // Update the template file in-place.
        if (file_put_contents($source, $contents) === false) {
            $this->writeVerbose('Unable to write to file: `' . $source . '`');
            return false;
        }

        // Then replace the original with the updated template.
        $this->writeVerbose("Using `{$source}` to replace `{$destination}`.");
        $fs = new \Composer\Util\Filesystem;
        $fs->copyThenRemove($source, $destination);

        return true;
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
