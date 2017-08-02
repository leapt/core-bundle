<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Leapt\CoreBundle\Navigation\NavigationRegistry;

/**
 * Class NavigationExtension
 * @package Leapt\CoreBundle\Twig\Extension
 */
class NavigationExtension extends \Twig_Extension
{
    /**
     * @var \Leapt\CoreBundle\Navigation\NavigationRegistry
     */
    private $registry;

    /**
     * @param \Leapt\CoreBundle\Navigation\NavigationRegistry $registry
     */
    public function __construct(NavigationRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Get all available functions
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('set_active_paths', [$this, 'setActivePaths']),
            new \Twig_SimpleFunction('add_active_path', [$this, 'addActivePath']),
            new \Twig_SimpleFunction('get_active_paths', [$this, 'getActivePaths']),
            new \Twig_SimpleFunction('is_active_path', [$this, 'isActivePath']),
            new \Twig_SimpleFunction('append_breadcrumb', [$this, 'appendBreadcrumb']),
            new \Twig_SimpleFunction('prepend_breadcrumb', [$this, 'prependBreadcrumb']),
            new \Twig_SimpleFunction('get_breadcrumbs', [$this, 'getBreadCrumbs']),
        ];
    }

    /**
     * Set the paths to be considered as active (navigation-wise)
     *
     * @param array $paths an array of URI paths
     */
    public function setActivePaths(array $paths)
    {
        $this->registry->setActivePaths($paths);
    }

    /**
     * Add a path to be considered as active (navigation-wise)
     *
     * @param array $paths an array of URI paths
     */
    public function addActivePath($path)
    {
        $this->registry->addActivePath($path);
    }

    /**
     * Get the active paths previously set
     *
     * @return array
     */
    public function getActivePaths()
    {
        return $this->registry->getActivePaths();
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
        return $this->registry->isActivePath($path);
    }

    /**
     * @param string $path
     * @param string $label
     */
    public function appendBreadcrumb($path, $label)
    {
        $this->registry->appendBreadcrumb($path, $label);
    }

    /**
     * @param string $path
     * @param string $label
     */
    public function prependBreadcrumb($path, $label)
    {
        $this->registry->prependBreadcrumb($path, $label);
    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        return $this->registry->getBreadcrumbs();
    }
}