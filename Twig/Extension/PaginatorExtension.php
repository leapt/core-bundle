<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Leapt\CoreBundle\Paginator\PaginatorInterface;
use Leapt\CoreBundle\Twig\TokenParser\PaginatorThemeTokenParser;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PaginatorExtension
 * @package Leapt\CoreBundle\Twig\Extension
 */
class PaginatorExtension extends \Twig_Extension implements ContainerAwareInterface
{
    /**
     * @var string
     */
    private $template;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var \SplObjectStorage
     */
    private $themes;

    /**
     * @param string $template
     */
    public function __construct($template)
    {
        $this->template = $template;
        $this->themes = new \SplObjectStorage();
    }

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
        return [
            new \Twig_SimpleFunction('paginator_widget', [$this, 'renderPaginatorWidget'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @return array
     */
    public function getTokenParsers()
    {
        return array(new PaginatorThemeTokenParser());
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

        return $this->renderblock($paginator, array($blockName), $context);
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
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @param PaginatorInterface $paginator
     * @return array
     */
    private function getTemplatesForPaginator(PaginatorInterface $paginator)
    {
        if(isset($this->themes[$paginator])){
            return $this->themes[$paginator];
        }

        return array($this->template);
    }

    /**
     * @param PaginatorInterface $paginator
     * @param $ressources
     */
    public function setTheme(PaginatorInterface $paginator, $ressources)
    {
        $this->themes[$paginator] = $ressources;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'leapt_core_paginator';
    }

    /**
     * @param \Leapt\AdminBundle\Datalist\DatalistInterface $datalist
     * @param array $blockNames
     * @param array $context
     * @return string
     * @throws \Exception
     */
    private function renderblock(PaginatorInterface $paginator, array $blockNames, array $context = array())
    {
        $paginatorTemplates = $this->getTemplatesForPaginator($paginator);
        foreach($paginatorTemplates as $template) {
            if (!$template instanceof \Twig_Template) {
                $template = $this->environment->loadTemplate($template);
            }
            do {
                foreach($blockNames as $blockName) {
                    if ($template->hasBlock($blockName)) {
                        return $template->renderBlock($blockName, $context);
                    }
                }
            }
            while(($template = $template->getParent($context)) !== false);
        }

        throw new \Exception(sprintf('No block found (tried to find %s)', implode(',', $blockNames)));
    }
}