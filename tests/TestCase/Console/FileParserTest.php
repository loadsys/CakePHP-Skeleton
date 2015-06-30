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
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Replacements read top-to-bottom. TestCase -> Sample, FileParserTest.php -> FileParser
        $this->testDir = str_replace(
            ['TestCase', basename(__FILE__)],
            ['Sample', basename(__FILE__, 'Test.php')],
            __FILE__
        );

        $this->io = $this->getMock('Composer\IO\IOInterface', ['isVerbose', 'write']);

        $event = $this->getMock('Composer\Script\Event', ['getIO']);
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

        parent::tearDown();
    }

    /**
     * Test the __construct() method.
     */
    public function testConstruct()
    {
        $this->assertEquals(
            $this->testDir . DIRECTORY_SEPARATOR,
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
}

