<?php

namespace Skel;

class Path
{
    protected $skeletonPath = '';

    protected $userProjectPath = '';


    public function __construct(string $path, string $userProjectPath)
    {
        $this->skeletonPath = $path;
        $this->userProjectPath = $userProjectPath;
    }

    public function setSkeletonPath($path)
    {
        $this->skeletonPath = $path;
    }

    public function getSkeletonPath()
    {
        return $this->skeletonPath;
    }

    public function getSkeletonTemplatePath($subdir = '')
    {
        $templatePath = '/templates';
        if ($subdir) {
            $templatePath .= '/' . $subdir; 
        }
        return $this->skeletonPath . $templatePath;
    }

    {
    }

    {
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
