<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Leapt\CoreBundle\Paginator\PaginatorInterface;
use Leapt\CoreBundle\Twig\TokenParser\PaginatorThemeTokenParser;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class PaginatorExtension
 * @package Leapt\CoreBundle\Twig\Extension
 */
class PaginatorExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $template;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var \SplObjectStorage
     */
    private $themes;

    /**
     * @param string $template
     * @param RequestStack $requestStack
     */
    public function __construct($template, RequestStack $requestStack)
    {
        $this->template = $template;
        $this->requestStack = $requestStack;
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

        $currentRequest = $this->requestStack->getCurrentRequest();
        $route = $currentRequest->attributes->get('_route');
        $routeParams = $currentRequest->attributes->get('_route_params', []);
        $newRouteParams = array_merge($routeParams, $currentRequest->query->all());

        $context = [
            'paginator'    => $paginator,
            'route'        => $route,
            'route_params' => $newRouteParams
        ];

        return $this->renderBlock($env, $paginator, [$blockName], $context);
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
     * @param \Twig_Environment $env
     * @param PaginatorInterface $paginator
     * @param array $blockNames
     * @param array $context
     * @return string
     * @throws \Exception
     * @throws \Twig_Error_Loader
     */
    private function renderBlock(\Twig_Environment $env, PaginatorInterface $paginator, array $blockNames, array $context = [])
    {
        $paginatorTemplates = $this->getTemplatesForPaginator($paginator);
        foreach ($paginatorTemplates as $template) {
            if (!$template instanceof \Twig_Template) {
                $template = $env->loadTemplate($template);
            }
            do {
                foreach ($blockNames as $blockName) {
                    if ($template->hasBlock($blockName, $context)) {
                        return $template->renderBlock($blockName, $context);
                    }
                }
            }
            while (($template = $template->getParent($context)) !== false);
        }

        throw new \Exception(sprintf('No block found (tried to find %s)', implode(',', $blockNames)));
    }
}