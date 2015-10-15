<?php
/**
 * Tests for the LoadsysInstaller composer library.
 */
namespace Skel\Test\TestCase;

use Skel\LoadsysInstaller;
use Skel\FileParser;
use Composer\Composer;
use Composer\Config;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use Composer\Package\RootPackageInterface;
use Composer\Script\Event;

/**
 * LoadsysInstallerTest
 */
class LoadsysInstallerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var IOInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $io;

    /**
     * @var InstallationManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $im;


    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->composer = new Composer();

        $config = new Config();
        $this->composer->setConfig($config);

        $this->im = $this->getMock('Composer\Installer\InstallationManager');
        $this->composer->setInstallationManager($this->im);

        $this->io = $this->getMock('Composer\IO\IOInterface', []);

        $this->event = $this->getMock('Composer\Script\Event', ['getIO'], [], '', false);
        $this->event->expects($this->any())
            ->method('getIO')
            ->will($this->returnValue($this->io));
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->composer);
        unset($this->im);
        unset($this->io);
        unset($this->event);
        $this->output = [];

        parent::tearDown();
    }

    /**
     * ioCollector
     *
     * Acts as a spy for calls to composer's io library. Collects any
     * strings passed to it for later review.
     *
     * @return void
     */
	public function ioCollector($str)
	{
		$this->output[] = $str;
		return true;
	}

    /**
     * Test the postInstall method.
     */
    public function testPostInstall()
    {
        $this->markTestIncomplete('@TODO: Write postInstall() tests.Probably hard to test as written.');
    }

    /**
     * Test the welcome method.
     */
    public function testWelcome()
    {
        $this->io->expects($this->atLeastOnce())
        	->method('write')
        	->with($this->callback([$this, 'ioCollector']))
        	->willReturn(true);

		LoadsysInstaller::welcome($this->io);
		$this->assertContains(
			'<info>Loadsys CakePHP 3 Project Skeleton Installer</info>',
			$this->output,
			'The method must put...something...on the screen.'
		);
    }

    /**
     * Test the parseTemplates method.
     *
     * This is not a very robust test.
     */
    public function testParseTemplates()
    {
        $templates = ['canary'];
        $tokens = [
        	'parrot' => 'want a cracker',
        	'__SALT__' => 'this side does not matter',
        ];

        $fileParser = $this->getMock('Skel\FileParser', [], [$this->event, '../Sample/FileParser']);
        $fileParser->expects($this->once())
        	->method('findTemplates')
        	->with()
        	->willReturn($templates);
        $fileParser->expects($this->once())
        	->method('getTokensInFiles')
        	->with($templates)
        	->willReturn($tokens);
        $fileParser->expects($this->any())
        	->method('parseTemplate')
        	->willReturn(null);

        LoadsysInstaller::parseTemplates($this->event, $fileParser);
    }
}
