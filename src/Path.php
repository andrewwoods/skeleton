<?php

namespace Skel;

use XdgBaseDir\Xdg;

class Path
{

    /**
     * @var string Path to the directory where skeleton is installed.
     */
    protected $skeletonDir = '';

    /**
     * @var string The current directory where skel is begin run
     */
    protected $userProjectDir = '';

    /**
     * @var string The users $HOME directory
     */
    protected $userHomeDir = '';

    /**
     * @var string The users' configuration directory. Default: $HOME/.config
     * but can be overriden by the XDG_CONFIG_HOME environment variable.
     */
    protected $userXdgConfigDir = '';

    /**
     * @var string The users' data directory. Default: $HOME/.local/share
     * but can be overriden by the XDG_DATA_HOME environment variable.
     */
    protected $userXdgDataDir = '';

    public function __construct(string $dir, string $userProjectDir, Xdg $xdg)
    {
        $this->skeletonDir = $dir;
        $this->userProjectDir = $userProjectDir;
        $this->userHomeDir = $xdg->getHomeDir();
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
            $templateDir = "{$templateDir}/{$subdir}";
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

    public function getUserHomeDir()
    {
        return $this->userHomeDir;
    }

    public function getTemplateDirs($subDirectory = '')
    {
        $data = [];
        if ($subDirectory) {
            $data[] = "{$this->getSkelUserXdgDataDir()}/{$subDirectory}";
        }
        $data[] = $this->getSkelUserXdgDataDir();
        $data[] = $this->getUserXdgDataDir();
        $data[] = $this->getUserHomeDir();
        if ($subDirectory) {
            $data[] = $this->getSkeletonTemplateDir($subDirectory);
        }
        $data[] = $this->getSkeletonTemplateDir();

        return $data;
    }

    public function getConfigFiles()
    {
        $configFile = '/config.yaml';

        $data = [];
        $data[] = $this->getSkelUserXdgConfigDir() . $configFile;
        $data[] = $this->getUserXdgConfigDir() . $configFile;
        $data[] = $this->getUserHomeDir() . $configFile;
        $data[] = $this->getUserProjectDir() . $configFile;

        return $data;
    }

    /**
     * The user's XDG_CONFIG_HOME directory.
     *
     * @return string
     */
    public function getUserXdgConfigDir() {
        return $this->userXdgConfigDir;
    }

    /**
     * The user's XDG_DATA_HOME directory.
     *
     * @return string
     */
    public function getUserXdgDataDir() {
        return $this->userXdgDataDir;
    }

    /**
     * The skel subdirectory of XDG_CONFIG_HOME.
     *
     * @return string
     */
    public function getSkelUserXdgConfigDir() {
        return $this->userXdgConfigDir . '/skel';
    }

    /**
     * The skel subdirectory of XDG_DATA_HOME.
     *
     * @return string
     */
    public function getSkelUserXdgDataDir() {
        return $this->userXdgDataDir . '/skel';
    }
}
