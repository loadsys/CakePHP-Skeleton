<?php

namespace App\Console;

use Composer\Script\Event;

/**
 *
 *
 */
class FileParser
{

    /**
     *
     */
    protected $tokenExpression = '/\{{2}([A-Z0-9_]+):?([^\}]*)?\}{2}/';

    /**
     *
     * @param Composer\Script\Event $event Composer's event object
     */
    public function __construct(Event $event, $dir)
    {
        $this->dir = $dir;
        $this->io = $event->getIO();
    }

    /**
     * Convenience function to get tokens from an array of files.
     * @param array Array of file name.
     * #return array Array of tokens found in all files
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
     * Gets a list of token found in $file
     *
     * @param string
     * @return array An array of tokens found in the file
     */
    public function getTokensInFile($file)
    {
        $file = $this->dir . $file;

        $this->writeVerbose('Reading tokens from `' . $file . '`...');
        $fileContents = file_get_contents($file);
        if (!$fileContents) {
            return [];
        }
        preg_match_all($this->tokenExpression, $fileContents, $matches);

        return array_combine($matches[1], $matches[2]);
    }

    /**
     * Recursively searches for files ending with .template in the skeleton directory
     * @return array List of template files
     */
    public function findTemplates()
    {
        $templates = [];
        $dirIterator = new \RecursiveDirectoryIterator($this->dir);
        $recIterIter = new \RecursiveIteratorIterator($dirIterator);
        $Regex = new \RegexIterator($recIterIter, '/^.+\.template$/', \RecursiveRegexIterator::GET_MATCH);

        foreach ($Regex as $file) {
            $template = str_replace($this->dir, '', $file[0]);
            $templates[] = $template;
            $this->writeVerbose('Found template: `' . $template . '`');
        }

        return $templates;
    }

    /**
     * Parse a given template replacing text matching a config key with the config value.
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
        if (!$fs->copyThenRemove($template, $file)) {
            $this->writeVerbose('Failed to replace `' . $file . '` with `' . $template . '`');
            return false;
        }

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

    protected function writeVerbose($string) {
        if ($this->io->isVerbose()) {
            $this->io->write('<comment>' . $string . '</comment>');
        }
    }

    /**
     * Ensure a string matches the token format
     */
    public function tokenize($string, $default = null) {
        $out = '{{' . $string . '}}';
        if ($default != null) {
            $out .= ':' . $default;
        }

        return $out;
    }

}
