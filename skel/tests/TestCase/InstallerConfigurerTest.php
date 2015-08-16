<?php
/**
 * Tests for the InstallerConfigurer composer library.
 */
namespace Skel\Test\TestCase;

use Skel\InstallerConfigurer;
use Composer\Composer;
use Composer\Config;
use Composer\Script\Event;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use Composer\Package\RootPackageInterface;

/**
 * Subclass to expose protected methods and proeprties for direct testing.
 */
class TestInstallerConfigurer extends InstallerConfigurer
{
    public $config = [];
    public function formatPrompt($token, $defaultValue = '')
    {
        return parent::formatPrompt($token, $defaultValue);
    }
}

/**
 * InstallerConfigurerTest
 */
class InstallerConfigurerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \App\Console\InstallerConfigurer
     */
    protected $configurer;

    /**
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

        $this->io = $this->getMock('Composer\IO\IOInterface', []);

        $event = $this->getMock('Composer\Script\Event', ['getIO'], [], '', false);
        $event->expects($this->once())
            ->method('getIO')
            ->will($this->returnValue($this->io));

        $this->configurer = new TestInstallerConfigurer($event);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->io);
        unset($this->configurer);

        parent::tearDown();
    }

    /**
     * Test the __construct() method.
     */
    public function testConstruct()
    {
        $this->assertEmpty(
            $this->configurer->config,
            'Newly created configurer instance must not have any configs.'
        );
    }

    /**
     * Test the read() method.
     */
    public function testRead()
    {
        // Test bad lookup.
        $this->assertFalse(
            $this->configurer->read('non-existant'),
            'Requesting a non-existant key should return false.'
        );

        $data = [
            'str' => 'value1',
            'pi' => 3.1415,
            'stdClass' => new \StdClass(),
            'ary' => [ 'one' => 1, 'two' => 2, 'three' => 3 ],
        ];
        $this->configurer->config = $data;

        // Test individual lookup.
        foreach ($data as $key => $expected) {
            $this->assertEquals(
                $expected,
                $this->configurer->read($key),
                'Requesting a single valid key should return the expected value.'
            );
        }

        // Test group lookup.
        $this->assertEquals(
            $data,
            $this->configurer->read(),
            'Requesting the entire config set should return the expected array.'
        );
    }

    /**
     * Test the write() method.
     */
    public function testWrite()
    {
        $this->configurer->write('key', 'value');
        $this->assertEquals(
            'value',
            $this->configurer->config['key'],
            'Writing a value should store it in the expected config key.'
        );
    }

    /**
     * Test the humanize() method.
     *
     * @param string $input The raw token to process.
     * @param string $expected The expected processed string.
     * @param string $msg Optional PHPUnit assertion failure message.
     * @dataProvider provideHumanizeArgs
     */
    public function testHumanize($input, $expected, $msg = '')
    {
        $this->assertEquals(
            $expected,
            $this->configurer->humanize($input),
            $msg
        );
    }

    /**
     * Provide data sets to testHumanize().
     *
     * @return array Sets of [input, expected, assertion failure message].
     */
    public function provideHumanizeArgs()
    {
        return [
            [
                '', '', // input, expected
                'Empty string should produce empty output.', // message
            ],

            [
                'simple', 'Simple',
                'Simple string should produce first letter capitalized.'
            ],

            [
                'two_parT', 'Two Part',
                'Underscored string should produce capitalized, spaced string.'
            ],

            [
                '{{TOKEN_NAME}}', 'Token Name',
                'Example {{TOKEN_NAME}} should produce fully humanized string.'
            ],
        ];
    }

    /**
     * Test the prompt() method.
     *
     * The majority of the work is in ensuring we provide the correct
     * params to the IOInterface::askAndValidate() since we return the
     * result of that directly.
     *
     * @param string $token The raw token to prompt with.
     * @param string $expected The default value for the token, might be empty.
     * @param string $formatted The expected output from calling `::formatPrompt($token, $defaultValue)`.
     * @param string $msg Optional PHPUnit assertion failure message.
     * @dataProvider providePromptArgs
     */
    public function testPrompt($token, $defaultValue, $formatted, $msg = '')
    {
        $expected = 'canary'; // Currently this doesn't need to be a dataProvider param. Stubbed for easier replacement in the future if necessary.
        $this->io->expects($this->once())
            ->method('askAndValidate')
            ->with(
                $formatted, // The return value from ::formatPrompt($token, $defaultValue).
                $this->anything(), // The "accept" function, currently an identity method, so no impact.
                $this->anything(), // Retry count. We don't need to test what this is.
                ($defaultValue ?: $token) // Slippery, but we want to verify the initial logic in prompt().
            )
            ->will($this->returnValue($expected));

        $this->assertEquals(
            $expected,
            $this->configurer->prompt($token, $defaultValue),
            $msg
        );
    }

    /**
     * Provide data sets to testPrompt().
     *
     * @return array Sets of [token, default, formatted, assertion failure message].
     */
    public function providePromptArgs()
    {
        return [
            [
                '', '', // token, default
                '<info></info>: ', // formatted
                'Empty string should produce "empty" output.', // message
            ],

            [
                'TOKEN_NAME', '',
                '<info>Token Name</info>: ',
                'Token name with no default should display [].',
            ],

            [
                'AM_I_AWESOME', 'yes',
                '<info>Am I Awesome</info> [yes]: ',
                'Token name with default should display fully formatted string.',
            ],
        ];
    }
}
