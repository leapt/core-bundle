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
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('paginator_widget', [$this, 'renderPaginatorWidget'], ['is_safe' => ['html'], 'needs_environment' => true])
        ];
    }

    /**
     * @return array
     */
    public function getTokenParsers()
    {
        return [new PaginatorThemeTokenParser()];
    }

    /**
     * @param \Twig_Environment $env
     * @param PaginatorInterface $paginator
     * @return string
     * @throws \Exception
     */
    public function renderPaginatorWidget(\Twig_Environment $env, PaginatorInterface $paginator)
    {
        $blockName = 'paginator_widget';

        $request = $this->container->get('request');
        $route = $request->attributes->get('_route');
        $routeParams = $request->attributes->get('_route_params', []);
        $newRouteParams = array_merge($routeParams, $request->query->all());

        $context = [
            'paginator'    => $paginator,
            'route'        => $route,
            'route_params' => $newRouteParams
        ];

        return $this->renderBlock($env, $paginator, [$blockName], $context);
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface $container A ContainerInterface instance
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
        if (isset($this->themes[$paginator])){
            return $this->themes[$paginator];
        }

        return [$this->template];
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
     * @param \Twig_Environment $env
     * @param PaginatorInterface $paginator
     * @param array $blockNames
     * @param array $context
     * @return string
     * @throws \Exception
     * @throws \Twig_Error_Loader
     */
    private function renderblock(\Twig_Environment $env, PaginatorInterface $paginator, array $blockNames, array $context = [])
    {
        $paginatorTemplates = $this->getTemplatesForPaginator($paginator);
        foreach ($paginatorTemplates as $template) {
            if (!$template instanceof \Twig_Template) {
                $template = $env->loadTemplate($template);
            }
            do {
                foreach ($blockNames as $blockName) {
                    if ($template->hasBlock($blockName)) {
                        return $template->renderBlock($blockName, $context);
                    }
                }
            }
            while (($template = $template->getParent($context)) !== false);
        }

        throw new \Exception(sprintf('No block found (tried to find %s)', implode(',', $blockNames)));
    }
}