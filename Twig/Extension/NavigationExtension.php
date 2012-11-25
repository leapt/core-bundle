<?php

namespace Snowcap\CoreBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NavigationExtension extends \Twig_Extension implements ContainerAwareInterface
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
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param null|ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
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
        return array(
            'set_active_paths' => new \Twig_Function_Method($this, 'setActivePaths'),
            'is_active_path' => new \Twig_Function_Method($this, 'isActivePath'),
            'append_breadcrumb' => new \Twig_Function_Method($this, 'appendBreadcrumb'),
            'prepend_breadcrumb' => new \Twig_Function_Method($this, 'prependBreadcrumb'),
            'get_breadcrumbs' => new \Twig_Function_Method($this, 'getBreadCrumbs'),
        );
    }

    /**
     * Return the name of the extension
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return 'snowcap_navigation';
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