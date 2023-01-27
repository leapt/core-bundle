<?php

declare(strict_types=1);

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

class DatalistBuilder extends DatalistConfig
{
    private array $fields = [];

    private array $filters = [];

    private array $actions = [];

    public function __construct(
        string $name,
        DatalistTypeInterface $type,
        array $options,
        private DatalistFactory $factory,
        private FormFactoryInterface $formFactory,
    ) {
        parent::__construct($name, $type, $options);
    }

    public function addField(string $field, string $type = null, array $options = []): self
    {
        $this->fields[$field] = [
            'type'    => $type,
            'options' => $options,
        ];

        return $this;
    }

    public function removeField(mixed $field): self
    {
        if (\array_key_exists($field, $this->fields)) {
            unset($this->fields[$field]);
        }

        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function addFilter(string $filter, string $type = null, array $options = []): self
    {
        $this->filters[$filter] = [
            'type'    => $type,
            'options' => $options,
        ];

        return $this;
    }

    public function removeFilter(mixed $filter): self
    {
        if (\array_key_exists($filter, $this->filters)) {
            unset($this->filters[$filter]);
        }

        return $this;
    }

    public function addAction(string $action, string $type = null, array $options = []): self
    {
        $this->actions[$action] = [
            'type'    => $type,
            'options' => $options,
        ];

        return $this;
    }

    public function removeAction(string $action): self
    {
        if (\array_key_exists($action, $this->actions)) {
            unset($this->actions[$action]);
        }

        return $this;
    }

    public function getDatalist(): Datalist
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
            $searchFormBuilder = $this->formFactory->createNamedBuilder('', FormType::class, null, [
                'csrf_protection' => false,
            ]);
            $searchFilter = $this->createFilter('search', [
                'type'    => SearchFilterType::class,
                'options' => [
                    'search_fields'         => $datalist->getOption('search'),
                    'search_explode_terms'  => $datalist->getOption('search_explode_terms'),
                ],
            ]);
            $searchFilter->getType()->buildForm($searchFormBuilder, $searchFilter, $searchFilter->getOptions());

            $searchFilter->setDatalist($datalist);
            $datalist->setSearchFilter($searchFilter);
            $datalist->setSearchForm($searchFormBuilder->getForm());
        }

        // Add filters and filter form
        $filterFormBuilder = $this->formFactory->createNamedBuilder('', FormType::class, null, [
            'csrf_protection' => false,
        ]);
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

    private function createField(string $fieldName, array $fieldConfig): DatalistField
    {
        $type = $this->factory->getFieldType($fieldConfig['type'] ?: 'text');

        // Handle field options
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['label' => ucfirst($fieldName)]);
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($fieldConfig['options']);

        $config = new DatalistFieldConfig($fieldName, $type, $resolvedOptions);

        return new DatalistField($config);
    }

    private function createFilter(string $filterName, array $filterConfig): DatalistFilter
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

    private function createAction(string $actionName, array $actionConfig): DatalistAction
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

    private function getDatalistConfig(): self
    {
        return clone $this;
    }
}
