<?php

namespace Snowcap\CoreBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Snowcap\CoreBundle\Paginator\PaginatorInterface;

class PaginatorExtension extends \Twig_Extension implements ContainerAwareInterface {
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $templatePaths = array();

    /**
     * @param \Twig_Environment $environment
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'paginator_widget' => new \Twig_Function_Method($this, 'renderPaginatorWidget', array('is_safe' => array('html'))),
        );
    }

    public function renderPaginatorWidget(PaginatorInterface $paginator){
        $blockName = 'paginator_widget';

        $request = $this->container->get('request');
        $route = $request->attributes->get('_route');
        $routeParams = $request->attributes->get('_route_params', array());
        $newRouteParams = array_merge($routeParams, $request->query->all());

        $context = array(
            'paginator' => $paginator,
            'route' => $route,
            'route_params' => $newRouteParams
        );

        return $this->renderblock('paginator.html.twig', $blockName, $context);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'snowcap_core_paginator';
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param string $templateName
     * @param string $blockName
     * @param array $context
     * @return string
     * @throws \Exception
     */
    private function renderblock($templateName, $blockName, array $context = array())
    {
        $loader = $this->environment->getLoader();
        foreach($this->templatePaths as $templatePath) {
            $loader->prependPath($templatePath);
        }
        $template = $this->environment->loadTemplate($templateName);

        if (!$template->hasBlock($blockName)) {
            throw new \Exception(sprintf('The block "%s" could not be loaded ', $blockName));
        }

        return $template->renderBlock($blockName, $context);
    }

    /**
     * @param string $templatePath
     */
    public function addTemplatePath($templatePath)
    {
        $realTemplatePath = realpath($templatePath);
        if(false === $realTemplatePath) {
            throw new \InvalidArgumentException(sprintf('The path "%s" does not exist', $templatePath));
        }

        $this->templatePaths[]= realpath($realTemplatePath);
    }
}