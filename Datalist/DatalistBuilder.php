<?php

namespace Leapt\CoreBundle\Datalist;

use Leapt\CoreBundle\Datalist\Action\DatalistAction;
use Leapt\CoreBundle\Datalist\Action\DatalistActionConfig;
use Leapt\CoreBundle\Datalist\Field\DatalistField;
use Leapt\CoreBundle\Datalist\Field\DatalistFieldConfig;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilter;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterConfig;
use Leapt\CoreBundle\Datalist\Filter\Type\SearchFilterType;
use Leapt\CoreBundle\Datalist\Type\DatalistTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatalistBuilder
 * @package Leapt\CoreBundle\Datalist
 */
class DatalistBuilder extends DatalistConfig
{
    /**
     * @var array
     */
    private $fields = [];

    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var array
     */
    private $actions = [];

    /**
     * @var DatalistFactory
     */
    private $factory;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param string $name
     * @param Type\DatalistTypeInterface $type
     * @param array $options
     * @param DatalistFactory $factory
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    public function __construct($name, DatalistTypeInterface $type, array $options, DatalistFactory $factory, FormFactoryInterface $formFactory)
    {
        parent::__construct($name, $type, $options);

        $this->factory = $factory;
        $this->formFactory = $formFactory;
    }

    /**
     * @param string $field
     * @param string $type
     * @param array $options
     * @return DatalistBuilder
     */
    public function addField($field, $type = null, array $options = [])
    {
        $this->fields[$field] = [
            'type'    => $type,
            'options' => $options
        ];

        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function removeField($field)
    {
        if (array_key_exists($field, $this->fields)) {
            unset($this->fields[$field]);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $filter
     * @param string $type
     * @param array $options
     * @return DatalistBuilder
     */
    public function addFilter($filter, $type = null, array $options = [])
    {
        $this->filters[$filter] = [
            'type'    => $type,
            'options' => $options
        ];

        return $this;
    }

    /**
     * @param $filter
     * @return $this
     */
    public function removeFilter($filter)
    {
        if (array_key_exists($filter, $this->filters)) {
            unset($this->filters[$filter]);
        }

        return $this;
    }

    /**
     * @param string $action
     * @param string $type
     * @param array $options
     * @return $this
     */
    public function addAction($action, $type = null, array $options = [])
    {
        $this->actions[$action] = [
            'type'    => $type,
            'options' => $options
        ];

        return $this;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function removeAction($action)
    {
        if (array_key_exists($action, $this->actions)) {
            unset($this->actions[$action]);
        }

        return $this;
    }

    /**
     * @return DatalistInterface
     */
    public function getDatalist()
    {
        $datalist = new Datalist($this->getDatalistConfig());

        // Add fields
        foreach ($this->fields as $fieldName => $fieldConfig) {
            $field = $this->createField($fieldName, $fieldConfig);
            $field->setDatalist($datalist);
            $datalist->addField($field);
        }

        // Add search form
        if (null !== $this->getOption('search')) {
            $searchFormBuilder = $this->formFactory->createNamedBuilder('', FormType::class, null, []);
            $searchFilter = $this->createFilter('search', [
                'type'    => SearchFilterType::class,
                'options' => [
                    'search_fields' => $datalist->getOption('search'),
                ],
            ]);
            $searchFilter->getType()->buildForm($searchFormBuilder, $searchFilter, $searchFilter->getOptions());

            $searchFilter->setDatalist($datalist);
            $datalist->setSearchFilter($searchFilter);
            $datalist->setSearchForm($searchFormBuilder->getForm());
        }

        // Add filters and filter form
        $filterFormBuilder = $this->formFactory->createNamedBuilder('', FormType::class, null, []);
        foreach ($this->filters as $filterName => $filterConfig) {
            $filter = $this->createFilter($filterName, $filterConfig);
            $filter->setDatalist($datalist);
            $filter->getType()->buildForm($filterFormBuilder, $filter, $filter->getOptions());
            $datalist->addFilter($filter);
        }
        $datalist->setFilterForm($filterFormBuilder->getForm());

        // Add actions
        foreach ($this->actions as $actionName => $actionConfig) {
            $action = $this->createAction($actionName, $actionConfig);
            $action->setDatalist($datalist);
            $datalist->addAction($action);
        }

        return $datalist;
    }

    /**
     * @param string $fieldName
     * @param array $fieldConfig
     * @return \Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface
     */
    private function createField($fieldName, array $fieldConfig)
    {
        $type = $this->factory->getFieldType($fieldConfig['type'] ? : 'text');

        // Handle field options
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['label' => ucfirst($fieldName)]);
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($fieldConfig['options']);

        $config = new DatalistFieldConfig($fieldName, $type, $resolvedOptions);

        return new DatalistField($config);
    }

    /**
     * @param string $filterName
     * @param array $filterConfig
     * @return Filter\DatalistFilter
     */
    private function createFilter($filterName, array $filterConfig)
    {
        $type = $this->factory->getFilterType($filterConfig['type']);

        // Handle filter options
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['label' => ucfirst($filterName)]);
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($filterConfig['options']);

        $config = new DatalistFilterConfig($filterName, $type, $resolvedOptions);

        return new DatalistFilter($config);
    }

    /**
     * @param string $actionName
     * @param array $actionConfig
     * @return DatalistAction
     */
    private function createAction($actionName, array $actionConfig)
    {
        $type = $this->factory->getActionType($actionConfig['type'] ?: 'simple');

        // Handle action options
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['label' => ucfirst($actionName)]);
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($actionConfig['options']);

        $config = new DatalistActionConfig($actionName, $type, $resolvedOptions);

        return new DatalistAction($config);
    }

    /**
     * @return DatalistBuilder
     */
    private function getDatalistConfig()
    {
        $config = clone $this;

        return $config;
    }
}