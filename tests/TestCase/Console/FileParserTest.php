<?php
/**
 * Tests for the FileParser composer library.
 */
namespace App\Test\TestCase\Console;

use App\Console\FileParser;
use Composer\Composer;
use Composer\Config;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use Composer\Package\RootPackageInterface;

/**
 * Subclass to expose protected methods and proeprties for direct testing.
 */
class TestFileParser extends FileParser
{
    public $dir = '';
    public function replaceInFile($search, $replace, $file)
    {
        return parent::replaceInFile($search, $replace, $file);
    }
    public function writeVerbose($string)
    {
        return parent::writeVerbose($string);
    }

    public function tokenExpression()
    {
        return $this->tokenExpression;
    }
}

/**
 * FileParserTest
 */
class FileParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Filesystem path to the Sample folder to operate on.
     *
     * Defined during ::setUp().
     *
     * @var string
     */
    protected $testDir = '';

    /**
     * Instance of the FileParser on which to run tests.
     *
     * @var \App\Console\FileParser
     */
    protected $parser;

    /**
     * Mocked IOInterface instance.
     *
     * @var IOInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $io;

    /**
     * Any calls to IOInterface->write() will append to this array.
     *
     * @var array
     */
    protected $ioWriteBuffer = [];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Replacements read top-to-bottom. TestCase -> Sample, FileParserTest.php -> FileParser
        $testDir = str_replace(
            ['TestCase', basename(__FILE__)],
            ['Sample', basename(__FILE__, 'Test.php')],
            __FILE__
        );
        $this->testDir = $this->createSampleDir($testDir) . DIRECTORY_SEPARATOR;

        $this->io = $this->getMock('Composer\IO\IOInterface', []);
        $this->io->expects($this->any())
            ->method('isVerbose')
            ->will($this->returnValue(true));
        $this->io->expects($this->any())
            ->method('write')
            ->will($this->returnCallback([$this, 'pushBuffer']));

        $event = $this->getMock('Composer\Script\Event', ['getIO'], [], '', false);
        $event->expects($this->once())
            ->method('getIO')
            ->will($this->returnValue($this->io));

        $this->parser = new TestFileParser($event, $this->testDir);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->io);
        unset($this->parser);
        $this->ioWriteBuffer = [];
        //$this->wipeSampleDir($this->testDir);

        parent::tearDown();
    }

    /**
     * Acts as a collector for mocked calls to IOInterface::write().
     *
     * `::setUp()` hooks `$this->parser->io->write($s);` into this method.
     * Any code that calls write() will append a new string to this array
     * for inspection. Is reset with each test case by ::tearDown().
     *
     * @param string $s The string to be written out via the I/O interface (or in our case, cached for later inspection.)
     * @return void
     */
    public function pushBuffer($s)
    {
        $this->ioWriteBuffer[] = $s;
    }

    /**
     * Recursively copies the provided $source dir to the $destination.
     *
     * Uses a subfolder of PHP's default tmp() directory if $destination is null.
     *
     * Ref: https://stackoverflow.com/a/7775949/70876
     *
     * @param string $source File system path to the dir to clone.
     * @param string $dest File system path to clone the dir TO.
     * @return string The destination folder path.
     */
    public function createSampleDir($source, $dest = null)
    {
        if(is_null($dest)) {
            $prefix = (new \ReflectionClass($this))->getShortName() . '_';
            $uniqueFolder = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid($prefix);
            $dest = $uniqueFolder;
        }

        mkdir($dest, 0755);
        foreach (
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            ) as $item
        ) {
            if ($item->isDir()) {
                mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }

        return $dest;
    }

    /**
     * Delete the entire directory structure starting at $path.
     *
     * Use cautiously.
     *
     * @param string $path File system path to the dir to delete.
     * @return void
     */
    public function wipeSampleDir($path)
    {
        foreach (
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            ) as $item
        ) {
            if ($item->isDir()) {
                rmdir($path . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                unlink($path . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
        rmdir($path);
    }

    /**
     * Test the __construct() method.
     */
    public function testConstruct()
    {
        $this->assertEquals(
            $this->testDir,
            $this->parser->dir,
            'Newly created parser\'s ::$dir must include trailing dir separator.'
        );
    }

    /**
     * Test the tokenize() method.
     *
     * @param string $token The token name to process.
     * @param string $default The default token value. `null` is treated differently from empty string "".
     * @param string $expected The expected processed string.
     * @param string $msg Optional PHPUnit assertion failure message.
     * @dataProvider provideTokenizeArgs
     */
    public function testTokenize($token, $default, $expected, $msg = '')
    {
        $this->assertEquals(
            $expected,
            $this->parser->tokenize($token, $default),
            $msg
        );
    }

    /**
     * Provide data sets to testTokenize().
     *
     * @return array Sets of [token, default, expected, assertion failure message].
     */
    public function provideTokenizeArgs()
    {
        return [
            [
                '', null, // token, default
                '{{}}', // expected
                'Empty string should produce empty output.', // message
            ],

            [
                'SIMPLE', null,
                '{{SIMPLE}}',
                'Null default value should produce simple token string.'
            ],

            [
                'EMPTY_DEFAULT', '',
                '{{EMPTY_DEFAULT:}}',
                'Blank default should produce a colon with nothing after it.'
            ],

            [
                'WITH_DEFAULT_VAL', 'fizz buzz',
                '{{WITH_DEFAULT_VAL:fizz buzz}}',
                'Non-empty default value should be included in output.'
            ],
        ];
    }

    /**
     * Test the getTokensInFiles() method.
     *
     * @param array $fileList Array of relative filenames to pass in.
     * @param array $expected The expected associative array of tokens and default values.
     * @param string $msg Optional PHPUnit assertion failure message.
     * @dataProvider provideGetTokensInFilesArgs
     */
    public function testGetTokensInFiles($fileList, $expected, $msg = '')
    {
        $this->assertEquals(
            $expected,
            $this->parser->getTokensInFiles($fileList),
            $msg
        );
    }

    /**
     * Provide data sets to testGetTokensInFiles().
     *
     * @return array Sets of [[fileList], [expected => tokens], assertion failure message].
     */
    public function provideGetTokensInFilesArgs()
    {
        return [
            [
                [], // file list
                [], // expected tokens
                'Empty file list should produce empty token list.', // message
            ],

            [
                ['bad-file'],
                [],
                'Produced token list must match those from the listed files.',
            ],

            [
                [
                    'not-a-template.php',
                    'README.md.template',
                    'subdir/empty-file',
                    'subdir/config.yaml.template',
                ],
                [
                    'TOKEN' => 'overlaps with token from ../README.md',
                    'NO_DEFAULT' => '',
                    'SECOND_ITEM' => 'The second thing in the list.',
                ],
                'Produced token list must match those from the listed files.',
            ],
        ];
    }

    /**
     * Test the findTemplates() method.
     */
    public function testFindTemplates()
    {
        $expected = [
            'README.md.template',
            'subdir/config.yaml.template',
        ];
        $this->assertEquals(
            $expected,
            $this->parser->findTemplates(),
            'Discovered templates should match the contents of our Sample/ directory.'
        );
    }

    /**
     * Test the parseTemplate() method.
     */
    public function testParseTemplate()
    {
        $file = 'README.md.template';
        $expectedFileName = 'README.md';
        $expectedMessages = [
            "Parsing template: $file",
            "Failed to replace `$expectedFileName` with `$file`",
        ];
        $tokens = [
            'TOKEN' => 'foo bar',
            'NO_DEFAULT' => 'tic tac toe',
            'SECOND_ITEM' => 'a quick brown fox',
            'UNUSED' => 'UNUSED',
            'USE_EMPTY_STR' => ' ',
       ];

        $this->assertTrue(
            $this->parser->parseTemplate($file, $tokens),
            'parseTemplate() should always return true.'
        );

        $this->assertFalse(
            file_exists($this->testDir . $file),
            'The source filename we expect should no longer exist.'
        );
        $this->assertTrue(
            file_exists($this->testDir . $expectedFileName),
            'The destination filename we expect should exist.'
        );

        $contents = file_get_contents($this->testDir . $expectedFileName);
        $this->assertContains(
            '## Don\'t change this line, we scan for it in tests.',
            $contents,
            'The contents of the pre-existing file should have been overwritten by our template.'
        );

        $matches = [];
        preg_match_all($this->parser->tokenExpression(), $contents, $matches);
        $this->assertEmpty(
            $matches,
            'The replaced file should not have any {{TOKEN}}s left in it.'
        );

        $this->assertEquals(
            $expectedMessages,
            $this->ioWriteBuffer,
            'The function should produce the expected output messages.'
        );
    }
}
