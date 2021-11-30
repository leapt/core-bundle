<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Twig\Extension;

use Leapt\CoreBundle\Paginator\PaginatorInterface;
use Leapt\CoreBundle\Twig\TokenParser\PaginatorThemeTokenParser;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Template;
use Twig\TwigFunction;

class PaginatorExtension extends AbstractExtension
{
    private \SplObjectStorage $themes;

    public function __construct(private string $template, private RequestStack $requestStack)
    {
        $this->themes = new \SplObjectStorage();
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('paginator_widget', [$this, 'renderPaginatorWidget'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    public function getTokenParsers(): array
    {
        return [new PaginatorThemeTokenParser()];
    }

    /**
     * @throws \Exception
     */
    public function renderPaginatorWidget(Environment $env, PaginatorInterface $paginator): string
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

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function setTheme(PaginatorInterface $paginator, $ressources)
    {
        $this->themes[$paginator] = $ressources;
    }

    private function getTemplatesForPaginator(PaginatorInterface $paginator): array
    {
        if (isset($this->themes[$paginator])) {
            return $this->themes[$paginator];
        }

        return [$this->template];
    }

    /**
     * @throws \Exception
     * @throws \Twig\Error\LoaderError
     */
    private function renderBlock(Environment $env, PaginatorInterface $paginator, array $blockNames, array $context = []): string
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
