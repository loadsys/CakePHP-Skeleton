<?php
/**
 * Tests for the LoadsysInstaller composer library.
 */
namespace App\Test\TestCase\Console;

use App\Console\LoadsysInstaller;
use Composer\Composer;
use Composer\Config;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use Composer\Package\RootPackageInterface;

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

        $this->io = $this->getMock('Composer\IO\IOInterface');
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

        parent::tearDown();
    }

    /**
     * Test the xyz method.
     */
    public function testXyz()
    {
        $this->markTestIncomplete('@TODO: Write LoadsysInstaller tests.');
    }
}
