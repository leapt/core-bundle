<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Leapt\CoreBundle\Navigation\NavigationRegistry;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class NavigationExtension.
 */
class NavigationExtension extends AbstractExtension
{
    /**
     * @var \Leapt\CoreBundle\Navigation\NavigationRegistry
     */
    private $registry;

    public function __construct(NavigationRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Get all available functions.
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('set_active_paths', [$this, 'setActivePaths']),
            new TwigFunction('add_active_path', [$this, 'addActivePath']),
            new TwigFunction('get_active_paths', [$this, 'getActivePaths']),
            new TwigFunction('is_active_path', [$this, 'isActivePath']),
            new TwigFunction('append_breadcrumb', [$this, 'appendBreadcrumb']),
            new TwigFunction('prepend_breadcrumb', [$this, 'prependBreadcrumb']),
            new TwigFunction('get_breadcrumbs', [$this, 'getBreadCrumbs']),
        ];
    }

    /**
     * Set the paths to be considered as active (navigation-wise).
     *
     * @param array $paths an array of URI paths
     */
    public function setActivePaths(array $paths)
    {
        $this->registry->setActivePaths($paths);
    }

    /**
     * Add a path to be considered as active (navigation-wise).
     *
     * @param array $paths an array of URI paths
     */
    public function addActivePath($path)
    {
        $this->registry->addActivePath($path);
    }

    /**
     * Get the active paths previously set.
     *
     * @return array
     */
    public function getActivePaths()
    {
        return $this->registry->getActivePaths();
    }

    /**
     * Checks if the provided path is to be considered as active.
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
