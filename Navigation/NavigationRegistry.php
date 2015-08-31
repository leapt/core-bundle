<?php

namespace Leapt\CoreBundle\Navigation;

use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class NavigationRegistry
 * @package Leapt\CoreBundle\Navigation
 */
class NavigationRegistry extends ContainerAware
{
    /**
     * @var array
     */
    private $activePaths = array();

    /**
     * @var array
     */
    private $breadcrumbsPaths = array();

    /**
     * Set the paths to be considered as active (navigation-wise)
     *
     * @param array $paths an array of URI paths
     */
    public function setActivePaths(array $paths)
    {
        $this->activePaths = $paths;
    }

    /**
     * @param string $path
     */
    public function addActivePath($path)
    {
        $this->activePaths[]= $path;
    }

    /**
     * Get the active paths previously set
     *
     * @return array
     */
    public function getActivePaths()
    {
        return $this->activePaths;
    }

    /**
     * Checks if the provided path is to be considered as active
     *
     * @param string $path
     *
     * @return bool
     */
    public function isActivePath($path)
    {
        return in_array($path, $this->activePaths) || $path === $this->container->get('request')->getRequestUri();
    }

    /**
     * @param string $path
     * @param string $label
     */
    public function appendBreadcrumb($path, $label)
    {
        $pair = array($path, $label);
        if (!in_array($pair, $this->breadcrumbsPaths)) {
            array_push($this->breadcrumbsPaths, $pair);
        }
    }

    /**
     * @param string $path
     * @param string $label
     */
    public function prependBreadcrumb($path, $label)
    {
        $pair = array($path, $label);
        if (!in_array($pair, $this->breadcrumbsPaths)) {
            array_unshift($this->breadcrumbsPaths, $pair);
        }
    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        return $this->breadcrumbsPaths;
    }
}