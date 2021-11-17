<?php

namespace Leapt\CoreBundle\Navigation;

use Symfony\Component\HttpFoundation\RequestStack;

class NavigationRegistry
{
    private array $activePaths = [];

    private array $breadcrumbsPaths = [];

    public function __construct(private RequestStack $requestStack)
    {
    }

    /**
     * Set the paths to be considered as active (navigation-wise).
     *
     * @param array $paths an array of URI paths
     */
    public function setActivePaths(array $paths)
    {
        $this->activePaths = $paths;
    }

    public function addActivePath(string $path)
    {
        $this->activePaths[] = $path;
    }

    /**
     * Get the active paths previously set.
     *
     * @return array
     */
    public function getActivePaths()
    {
        return $this->activePaths;
    }

    /**
     * Checks if the provided path is to be considered as active.
     *
     * @return bool
     */
    public function isActivePath(string $path)
    {
        return \in_array($path, $this->activePaths, true) || $this->requestStack->getCurrentRequest()->getRequestUri() === $path;
    }

    public function appendBreadcrumb(string $path, string $label)
    {
        $pair = [$path, $label];
        if (!\in_array($pair, $this->breadcrumbsPaths, true)) {
            $this->breadcrumbsPaths[] = $pair;
        }
    }

    public function prependBreadcrumb(string $path, string $label)
    {
        $pair = [$path, $label];
        if (!\in_array($pair, $this->breadcrumbsPaths, true)) {
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
