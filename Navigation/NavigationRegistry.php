<?php

namespace Leapt\CoreBundle\Navigation;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class NavigationRegistry
 * @package Leapt\CoreBundle\Navigation
 */
class NavigationRegistry
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var array
     */
    private $activePaths = [];

    /**
     * @var array
     */
    private $breadcrumbsPaths = [];

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

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
        $this->activePaths[] = $path;
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
     * @return bool
     */
    public function isActivePath($path)
    {
        return in_array($path, $this->activePaths) || $path === $this->requestStack->getCurrentRequest()->getRequestUri();
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