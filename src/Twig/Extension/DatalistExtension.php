<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Leapt\CoreBundle\Datalist\Action\DatalistActionInterface;
use Leapt\CoreBundle\Datalist\DatalistInterface;
use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Leapt\CoreBundle\Twig\TokenParser\DatalistThemeTokenParser;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Template;
use Twig\TwigFunction;

final class DatalistExtension extends AbstractExtension
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $defaultTheme = '@LeaptCore/Datalist/datalist_grid_layout.html.twig';

    /**
     * @var \SplObjectStorage
     */
    private $themes;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->themes = new \SplObjectStorage();
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('datalist_widget', [$this, 'renderDatalistWidget'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('datalist_field', [$this, 'renderDatalistField'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('datalist_search', [$this, 'renderDatalistSearch'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('datalist_filters', [$this, 'renderDatalistFilters'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('datalist_filter', [$this, 'renderDatalistFilter'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('datalist_action', [$this, 'renderDatalistAction'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    public function getTokenParsers(): array
    {
        return [new DatalistThemeTokenParser()];
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function renderDatalistWidget(Environment $env, DatalistInterface $datalist)
    {
        $blockNames = [
            '_' . $datalist->getType()->getBlockName() . '_datalist',
            $datalist->getType()->getBlockName(),
            'datalist',
        ];

        $viewContext = new ViewContext();
        $datalist->getType()->buildViewContext($viewContext, $datalist, $datalist->getOptions());

        return $this->renderBlock($env, $datalist, $blockNames, $viewContext->all());
    }

    /**
     * @param mixed $row
     *
     * @return string
     *
     * @throws \Exception
     */
    public function renderDatalistField(Environment $env, DatalistFieldInterface $field, $row)
    {
        $blockNames = [
            '_' . $field->getDatalist()->getType()->getBlockName() . '_' . $field->getName() . '_field',
            $field->getType()->getBlockName() . '_field',
        ];

        $viewContext = new ViewContext();
        $field->getType()->buildViewContext($viewContext, $field, $row, $field->getOptions());

        return $this->renderBlock($env, $field->getDatalist(), $blockNames, $viewContext->all());
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function renderDatalistSearch(Environment $env, DatalistInterface $datalist)
    {
        $blockNames = [
            '_' . $datalist->getType()->getBlockName() . '_search',
            'datalist_search',
        ];

        return $this->renderBlock($env, $datalist, $blockNames, [
            'form'               => $datalist->getSearchForm()->createView(),
            'placeholder'        => $datalist->getOption('search_placeholder'),
            'submit'             => $datalist->getOption('search_submit'),
            'translation_domain' => $datalist->getOption('translation_domain'),
        ]);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function renderDatalistFilters(Environment $env, DatalistInterface $datalist)
    {
        $blockNames = [
            '_' . $datalist->getType()->getBlockName() . '_filters',
            '_' . $datalist->getName() . '_filters',
            'datalist_filters',
        ];

        return $this->renderBlock($env, $datalist, $blockNames, [
            'filters'   => $datalist->getFilters(),
            'datalist'  => $datalist,
            'submit'    => $datalist->getOption('filter_submit'),
            'reset'     => $datalist->getOption('filter_reset'),
            'url'       => $this->requestStack->getCurrentRequest()->getPathInfo(),
        ]);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function renderDatalistFilter(Environment $env, DatalistFilterInterface $filter)
    {
        $blockNames = [
            '_' . $filter->getDatalist()->getName() . '_' . $filter->getName() . '_filter',
            $filter->getType()->getBlockName() . '_filter',
        ];
        $childForm = $filter->getDatalist()->getFilterForm()->get($filter->getName());

        return $this->renderBlock($env, $filter->getDatalist(), $blockNames, [
            'form'     => $childForm->createView(),
            'filter'   => $filter,
            'datalist' => $filter->getDatalist(),
        ]);
    }

    /**
     * @param mixed $item
     *
     * @return string
     *
     * @throws \Exception
     */
    public function renderDatalistAction(Environment $env, DatalistActionInterface $action, $item)
    {
        $blockNames = [
            '_' . $action->getDatalist()->getName() . '_' . $action->getName() . '_action',
            $action->getType()->getBlockName() . '_action',
        ];

        $viewContext = new ViewContext();
        $action->getType()->buildViewContext($viewContext, $action, $item, $action->getOptions());

        return $this->renderBlock(
            $env,
            $action->getDatalist(),
            $blockNames,
            $viewContext->all()
        );
    }

    /**
     * @param $ressources
     */
    public function setTheme(DatalistInterface $datalist, $ressources)
    {
        $this->themes[$datalist] = $ressources;
    }

    /**
     * @return string
     *
     * @throws \Exception
     * @throws \Twig\Error\LoaderError
     */
    private function renderBlock(Environment $env, DatalistInterface $datalist, array $blockNames, array $context = [])
    {
        $datalistTemplates = $this->getTemplatesForDatalist($datalist);
        foreach ($datalistTemplates as $template) {
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

    /**
     * @return array
     */
    private function getTemplatesForDatalist(DatalistInterface $datalist)
    {
        if (isset($this->themes[$datalist])) {
            return $this->themes[$datalist];
        }

        return [$this->defaultTheme];
    }
}
