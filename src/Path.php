<?php

namespace Skel;

use Symfony\Component\String\Exception\InvalidArgumentException;
use XdgBaseDir\Xdg;

class Path
{
    protected $skeletonDir = '';

    protected $userProjectDir = '';

    protected $userXdgConfigDir = '';

    protected $userXdgDataDir = '';

    public function __construct(string $dir, string $userProjectDir, Xdg $xdg)
    {
        $this->skeletonDir = $dir;
        $this->userProjectDir = $userProjectDir;
        $this->userXdgDataDir = $xdg->getHomeDataDir();
        $this->userXdgConfigDir = $xdg->getHomeConfigDir();
    }

    public function setSkeletonDir($dir)
    {
        $this->skeletonDir = $dir;
    }

    public function getSkeletonDir()
    {
        return $this->skeletonDir;
    }

    public function getSkeletonTemplateDir(string $subdir = ''): string
    {
        $templateDir = $this->getSkeletonDir() . '/templates';
        if ($subdir) {
            $templateDir = "{$templateDir}/" . $subdir;
        }
        if (! file_exists($templateDir)) {
            $message = sprintf("The directory '%s' does not exist", $templateDir);
            throw new InvalidArgumentException($message);
        } 
        return $templateDir;
    }

    public function setUserProjectDir($dir)
    {
        $this->userProjectDir = $dir;
    }

    public function getUserProjectDir()
    {
        return $this->userProjectDir;
    }

    public function getTemplateDirs($subDirectory = '')
    {
        $data = [];
        if ($subDirectory) {
            $data[] = $this->userXdgDataDir . '/skel/' . $subDirectory;
        }
        $data[] = $this->userXdgDataDir . '/skel';
        $data[] = $this->userXdgDataDir;
        $data[] = $this->getUserProjectDir();
        if ($subDirectory) {
            $data[] = $this->getSkeletonTemplateDir($subDirectory);
        }
        $data[] = $this->getSkeletonTemplateDir();

        return $data;
    }

    /**
     * The skel subdirectory of XDG_CONFIG_HOME.
     *
     * @return string
     */
    public function getSkelUserXdgConfigDir() {
        return $this->userXdgConfigDir . '/skel';
    }
}
