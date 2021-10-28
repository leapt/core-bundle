<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Leapt\CoreBundle\Paginator\PaginatorInterface;
use Leapt\CoreBundle\Twig\TokenParser\PaginatorThemeTokenParser;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Template;
use Twig\TwigFunction;

/**
 * Class PaginatorExtension.
 */
class PaginatorExtension extends AbstractExtension
{
    private string $template;

    private RequestStack $requestStack;

    private \SplObjectStorage $themes;

    public function __construct(string $template, RequestStack $requestStack)
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
            new TwigFunction('paginator_widget', [$this, 'renderPaginatorWidget'], ['is_safe' => ['html'], 'needs_environment' => true]),
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
     * @return string
     *
     * @throws \Exception
     */
    public function renderPaginatorWidget(Environment $env, PaginatorInterface $paginator)
    {
        $blockName = 'paginator_widget';

        $currentRequest = $this->requestStack->getCurrentRequest();
        $route = $currentRequest->attributes->get('_route');
        $routeParams = $currentRequest->attributes->get('_route_params', []);
        $newRouteParams = array_merge($routeParams, $currentRequest->query->all());

        $context = [
            'paginator'    => $paginator,
            'route'        => $route,
            'route_params' => $newRouteParams,
        ];

        return $this->renderBlock($env, $paginator, [$blockName], $context);
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * @param $ressources
     */
    public function setTheme(PaginatorInterface $paginator, $ressources)
    {
        $this->themes[$paginator] = $ressources;
    }

    /**
     * @return array
     */
    private function getTemplatesForPaginator(PaginatorInterface $paginator)
    {
        if (isset($this->themes[$paginator])) {
            return $this->themes[$paginator];
        }

        return [$this->template];
    }

    /**
     * @return string
     *
     * @throws \Exception
     * @throws \Twig\Error\LoaderError
     */
    private function renderBlock(Environment $env, PaginatorInterface $paginator, array $blockNames, array $context = [])
    {
        $paginatorTemplates = $this->getTemplatesForPaginator($paginator);
        foreach ($paginatorTemplates as $template) {
            if (!$template instanceof Template) {
                $template = $env->load($template);
            }
            do {
                foreach ($blockNames as $blockName) {
                    if ($template->hasBlock($blockName, $context)) {
                        return $template->renderBlock($blockName, $context);
                    }
                }
            } while (false !== ($template = $template->getParent($context)));
        }

        throw new \Exception(sprintf('No block found (tried to find %s)', implode(',', $blockNames)));
    }
}
