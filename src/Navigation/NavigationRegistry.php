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

    public function addActivePath(string $path): self
    {
        $this->activePaths[] = $path;

        return $this;
    }

    /**
     * Get the active paths previously set.
     */
    public function getActivePaths(): array
    {
        return $this->activePaths;
    }

    /**
     * Checks if the provided path is to be considered as active.
     */
    public function isActivePath(string $path): bool
    {
        return \in_array($path, $this->activePaths, true) || $this->requestStack->getCurrentRequest()->getRequestUri() === $path;
    }

    public function appendBreadcrumb(string $path, string $label): void
    {
        $pair = [$path, $label];
        if (!\in_array($pair, $this->breadcrumbsPaths, true)) {
            $this->breadcrumbsPaths[] = $pair;
        }
    }

    public function prependBreadcrumb(string $path, string $label): void
    {
        $pair = [$path, $label];
        if (!\in_array($pair, $this->breadcrumbsPaths, true)) {
            array_unshift($this->breadcrumbsPaths, $pair);
        }
    }

    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbsPaths;
    }
}
