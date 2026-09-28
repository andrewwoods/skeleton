<?php

use PHPUnit\Framework\TestCase;
use Skel\PAth;
use XdgBaseDir\Xdg;


class PathTest extends TestCase
{
    public function setUp(): void {
        // These values are set in the phpunit.xml file.
       $_ENV['HOME'] = '/Users/account-name';
       $_ENV['XDG_CONFIG_HOME'] = '/Users/account-name/.config';
       $_ENV['XDG_DATA_HOME'] = '/Users/account-name/.local/share';
    }

    public function testBasicSetup()
    {
        $expectedXdgConfigDir = '/Users/account-name/.config';
        $expectedXdgDataDir = '/Users/account-name/.local/share';
        $projectDir = '/project';
        $skelInstallDir = '/skel-install';
        $xdg = new Xdg();
        $path = new Path($skelInstallDir, $projectDir, $xdg);

        $this->assertEquals("{$skelInstallDir}", $path->getSkeletonDir());
        $this->assertEquals("{$projectDir}", $path->getUserProjectDir());
        $this->assertEquals("{$expectedXdgConfigDir}", $path->getUserXdgConfigDir());
        $this->assertEquals("{$expectedXdgDataDir}", $path->getUserXdgDataDir());
    }
    public function testGetUserHomeDir()
    {
        $expectedHomeDir = '/Users/account-name';
        $projectDir = '/project';
        $skelInstallDir = '/skel-install';
        $xdg = new Xdg();
        $path = new Path($skelInstallDir, $projectDir, $xdg);

        $this->assertEquals($expectedHomeDir, $path->getUserHomeDir());
    }

    public function testGetSkelXdgConfigDir()
    {
        $expectedXdgConfigDir = '/Users/account-name/.config';
        $projectDir = '/project';
        $skelInstallDir = '/skel-install';
        $xdg = new Xdg();
        $path = new Path($skelInstallDir, $projectDir, $xdg);

        $this->assertEquals($expectedXdgConfigDir, $path->getUserXdgConfigDir());
        $this->assertEquals("{$expectedXdgConfigDir}/skel", $path->getSkelUserXdgConfigDir());
    }

    public function testGetSkelXdgDataDir()
    {
        $expectedXdgDataDir = '/Users/account-name/.local/share';
        $projectDir = '/project';
        $skelInstallDir = '/skel-install';
        $xdg = new Xdg();
        $path = new Path($skelInstallDir, $projectDir, $xdg);

        $this->assertEquals("{$expectedXdgDataDir}", $path->getUserXdgDataDir());
        $this->assertEquals("{$expectedXdgDataDir}/skel", $path->getSkelUserXdgDataDir());
    }

    public function testGetTemplateDirs()
    {
        $expectedDirCount = 3;
        $expectedSubDirCount = 5;

        $projectDir = '/project';
        $skelInstallDir = '/skel-install';
        $xdg = new Xdg();
        $path = new Path($skelInstallDir, $projectDir, $xdg);

        $this->assertEquals("{$expectedDirCount}", count($path->getTemplateDirs()));
        $this->assertEquals("{$expectedSubDirCount}", count($path->getTemplateDirs('php')));
    }

}
