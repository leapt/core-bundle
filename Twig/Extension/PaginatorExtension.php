<?php

namespace Leapt\AdminBundle\Twig\Extension;

use Leapt\AdminBundle\Datalist\Action\DatalistActionInterface;
use Leapt\AdminBundle\Datalist\DatalistInterface;
use Leapt\AdminBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\AdminBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\AdminBundle\Datalist\ViewContext;
use Leapt\AdminBundle\Twig\TokenParser\DatalistThemeTokenParser;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormFactory;

/**
 * Class DatalistExtension
 * @package Leapt\AdminBundle\Twig\Extension
 */
class DatalistExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;

    /**
     * @var string
     */
    private $defaultTheme = 'LeaptAdminBundle:Datalist:datalist_grid_layout.html.twig';

    /**
     * @var \SplObjectStorage
     */
    private $themes;

    /**
     * @param \Symfony\Component\Form\FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
        $this->themes = new \SplObjectStorage();
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('datalist_widget', [$this, 'renderDatalistWidget'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('datalist_field', [$this, 'renderDatalistField'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('datalist_search', [$this, 'renderDatalistSearch'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('datalist_filters', [$this, 'renderDatalistFilters'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('datalist_filter', [$this, 'renderDatalistFilter'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('datalist_action', [$this, 'renderDatalistAction'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    /**
     * @return array
     */
    public function getTokenParsers()
    {
        return [new DatalistThemeTokenParser()];
    }

    /**
     * @param \Twig_Environment $env
     * @param \Leapt\AdminBundle\Datalist\DatalistInterface $datalist
     * @return string
     * @throws \Exception
     */
    public function renderDatalistWidget(\Twig_Environment $env, DatalistInterface $datalist)
    {
        $blockNames = [
            $datalist->getType()->getBlockName(),
            '_' . $datalist->getName() . '_datalist'
        ];

        $viewContext = new ViewContext();
        $datalist->getType()->buildViewContext($viewContext, $datalist, $datalist->getOptions());

        return $this->renderBlock($env, $datalist, $blockNames, $viewContext->all());
    }

    /**
     * @param \Twig_Environment $env
     * @param \Leapt\AdminBundle\Datalist\Field\DatalistFieldInterface $field
     * @param mixed $row
     * @return string
     * @throws \Exception
     */
    public function renderDatalistField(\Twig_Environment $env, DatalistFieldInterface $field, $row)
    {
        $blockNames = [
            $field->getType()->getBlockName() . '_field',
            '_' . $field->getDatalist()->getName() . '_' . $field->getName() . '_field',
        ];

        $viewContext = new ViewContext();
        $field->getType()->buildViewContext($viewContext, $field, $row, $field->getOptions());

        return $this->renderBlock($env, $field->getDatalist(), $blockNames, $viewContext->all());
    }

    /**
     * @param \Twig_Environment $env
     * @param \Leapt\AdminBundle\Datalist\DatalistInterface $datalist
     * @return string
     * @throws \Exception
     */
    public function renderDatalistSearch(\Twig_Environment $env, DatalistInterface $datalist)
    {
        $blockNames = [
            'datalist_search',
            '_' . $datalist->getName() . '_search',
        ];

        return $this->renderBlock($env, $datalist, $blockNames, [
            'form' => $datalist->getSearchForm()->createView(),
            'placeholder' => $datalist->getOption('search_placeholder'),
            'submit' => $datalist->getOption('search_submit'),
            'translation_domain' => $datalist->getOption('translation_domain')
        ]);
    }

    /**
     * @param \Twig_Environment $env
     * @param \Leapt\AdminBundle\Datalist\DatalistInterface $datalist
     * @return string
     * @throws \Exception
     */
    public function renderDatalistFilters(\Twig_Environment $env, DatalistInterface $datalist)
    {
        $blockNames = [
            'datalist_filters',
            '_' . $datalist->getName() . '_filters'
        ];

        return $this->renderBlock($env, $datalist, $blockNames, [
            'filters' => $datalist->getFilters(),
            'datalist' => $datalist,
            'submit' => $datalist->getOption('filter_submit'),
            'reset' => $datalist->getOption('filter_reset'),
            'url' => $this->container->get('request')->getPathInfo()
        ]);
    }

    /**
     * @param \Twig_Environment $env
     * @param \Leapt\AdminBundle\Datalist\Filter\DatalistFilterInterface $filter
     * @return string
     * @throws \Exception
     */
    public function renderDatalistFilter(\Twig_Environment $env, DatalistFilterInterface $filter)
    {
        $blockNames = [
            $filter->getType()->getBlockName() . '_filter',
            '_' . $filter->getDatalist()->getName() . '_' . $filter->getName() . '_filter'
        ];
        $childForm = $filter->getDatalist()->getFilterForm()->get($filter->getName());

        return $this->renderBlock($env, $filter->getDatalist(), $blockNames, [
            'form' => $childForm->createView(),
            'filter' => $filter,
            'datalist' => $filter->getDatalist()
        ]);
    }

    /**
     * @param \Twig_Environment $env
     * @param \Leapt\AdminBundle\Datalist\Action\DatalistActionInterface $action
     * @param mixed $item
     * @return string
     * @throws \Exception
     */
    public function renderDatalistAction(\Twig_Environment $env, DatalistActionInterface $action, $item)
    {
        $blockNames = [
            $action->getType()->getBlockName() . '_action',
            '_' . $action->getDatalist()->getName() . '_' . $action->getName() . '_action'
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
     * @param \Twig_Environment $env
     * @param \Leapt\AdminBundle\Datalist\DatalistInterface $datalist
     * @param array $blockNames
     * @param array $context
     * @return string
     * @throws \Exception
     * @throws \Twig_Error_Loader
     */
    private function renderBlock(\Twig_Environment $env, DatalistInterface $datalist, array $blockNames, array $context = [])
    {
        $datalistTemplates = $this->getTemplatesForDatalist($datalist);
        foreach($datalistTemplates as $template) {
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

    /**
     * @param \Leapt\AdminBundle\Datalist\DatalistInterface $datalist
     * @return array
     */
    private function getTemplatesForDatalist(DatalistInterface $datalist)
    {
        if(isset($this->themes[$datalist])){
            return $this->themes[$datalist];
        }

        return [$this->defaultTheme];
    }

    /**
     * @param DatalistInterface $datalist
     * @param $ressources
     */
    public function setTheme(DatalistInterface $datalist, $ressources)
    {
        $this->themes[$datalist] = $ressources;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'leapt_admin_datalist';
    }
}
