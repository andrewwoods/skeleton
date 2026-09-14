<?php

namespace Skel;

class Path
{
    protected $skeletonPath = '';

    protected $userProjectPath = '';

    protected $templatePath = '';

    public function __construct(string $path, string $userProjectPath, string $templatePath)
    {
        $this->skeletonPath = $path;
        $this->userProjectPath = $userProjectPath;
        $this->templatePath = $templatePath;
    }

    public function setSkeletonPath($path)
    {
        $this->skeletonPath = $path;
    }

    public function getSkeletonPath()
    {
        return $this->skeletonPath;
    }

    public function setTemplatePath($path)
    {
        $this->templatePath = $path;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setUserPath($path)
    {
        $this->path = $path;
    }

    public function getUserPath()
    {
        return $this->userProjectPath;
    }
}
