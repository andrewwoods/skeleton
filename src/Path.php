<?php

namespace Skel;

use Symfony\Component\String\Exception\InvalidArgumentException;
use XdgBaseDir\Xdg;

class Path
{
    protected $skeletonPath = '';

    protected $userProjectDir = '';

    protected $userXdgConfigDir = '';

    protected $userXdgDataDir = '';

    public function __construct(string $path, string $userProjectPath, Xdg $xdg)
    {
        $this->skeletonPath = $path;
        $this->userProjectDir = $userProjectPath;
        $this->userXdgDataDir = $xdg->getHomeDataDir();
        $this->userXdgConfigDir = $xdg->getHomeConfigDir();
    }

    public function setSkeletonPath($path)
    {
        $this->skeletonPath = $path;
    }

    public function getSkeletonDir()
    {
        return $this->skeletonPath;
    }

    public function getSkeletonTemplateDir(string $subdir = ''): string
    {
        $templatePath = $this->getSkeletonDir() . '/templates';
        if ($subdir) {
            $templatePath = "{$templatePath}/" . $subdir;
        }
        if (! file_exists($templatePath)) {
            $message = sprintf("The directory '%s' does not exist", $templatePath);
            throw new InvalidArgumentException($message);
        } 
        return $templatePath;
    }

    public function setUserProjectDir($path)
    {
        $this->userProjectDir = $path;
    }

    public function getUserProjectDir()
    {
        return $this->userProjectDir;
    }

    public function getTemplateDirs($subDirectory = '')
    {
        $data = [];
        $data[] = $this->userXdgDataDir;
        $data[] = $this->userXdgDataDir . '/skel';
        $data[] = $this->getUserProjectDir();
        if ($subDirectory) {
            $data[] = $this->getSkeletonTemplateDir($subDirectory);
        }
        $data[] = $this->getSkeletonTemplateDir();

        return $data;
    }
}
