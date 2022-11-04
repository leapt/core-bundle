<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist;

use Iterator;
use Leapt\CoreBundle\Datalist\Action\DatalistActionInterface;
use Leapt\CoreBundle\Datalist\Datasource\DatasourceInterface;
use Leapt\CoreBundle\Datalist\Field\DatalistField;
use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Paginator\PaginatorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Datalist implements DatalistInterface, \Countable
{
    private DatasourceInterface $datasource;

    private array $fields = [];

    private array $sortedFields;

    private array $filters = [];

    private DatalistFilterInterface $searchFilter;

    private array $actions = [];

    private int $page = 1;

    private string $searchQuery;

    private array $filterData = [];

    private FormInterface $searchForm;

    private FormInterface $filterForm;

    private \Iterator $iterator;

    private bool $initialized = false;

    private string $route;

    private array $routeParams = [];

    public function __construct(private DatalistConfig $config)
    {
    }

    public function getType(): TypeInterface
    {
        return $this->config->getType();
    }

    public function addField(DatalistFieldInterface $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    public function getFields(): array
    {
        if (!isset($this->sortedFields)) {
            $sortedFields = $this->fields;
            $i = 1;
            array_walk($sortedFields, function (DatalistFieldInterface $field) use (&$i) {
                if (null === $field->getOption('order')) {
                    $field->setOption('order', $i);
                }
                ++$i;
            });
            usort(
                $sortedFields,
                function (DatalistFieldInterface $field1, DatalistFieldInterface $field2) {
                    return $field1->getOption('order', 0) >= $field2->getOption('order', 0) ? 1 : -1;
                },
            );
            $this->sortedFields = $sortedFields;
        }

        return $this->sortedFields;
    }

    public function addFilter(DatalistFilterInterface $filter): DatalistInterface
    {
        $this->filters[$filter->getName()] = $filter;

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setSearchFilter(DatalistFilterInterface $filter): void
    {
        $this->searchFilter = $filter;
    }

    public function getSearchFilter(): DatalistFilterInterface
    {
        return $this->searchFilter;
    }

    public function addAction(DatalistActionInterface $action): DatalistInterface
    {
        $this->actions[$action->getName()] = $action;

        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function setDatasource(DatasourceInterface $datasource): DatalistInterface
    {
        $this->datasource = $datasource;

        return $this;
    }

    public function getDatasource(): DatasourceInterface
    {
        return $this->datasource;
    }

    public function getPaginator(): ?PaginatorInterface
    {
        $this->initialize();

        return $this->datasource->getPaginator();
    }

    public function setPage(int $page): DatalistInterface
    {
        $this->page = $page;

        return $this;
    }

    public function getName(): string
    {
        return $this->config->getName();
    }

    public function getOptions(): array
    {
        return $this->config->getOptions();
    }

    public function hasOption(string $name): bool
    {
        return $this->config->hasOption($name);
    }

    public function getOption(string $name, mixed $default = null): mixed
    {
        return $this->config->getOption($name, $default);
    }

    public function isFilterable(): bool
    {
        return 0 < \count($this->filters);
    }

    public function isSearchable(): bool
    {
        return null !== $this->getOption('search');
    }

    public function setSearchForm(FormInterface $form): DatalistInterface
    {
        $this->searchForm = $form;

        return $this;
    }

    public function setFilterForm(FormInterface $form): DatalistInterface
    {
        $this->filterForm = $form;

        return $this;
    }

    public function getSearchForm(): FormInterface
    {
        return $this->searchForm;
    }

    public function getFilterForm(): FormInterface
    {
        return $this->filterForm;
    }

    /**
     * Bind search / filter data to the datalist.
     *
     * @param mixed $data a data array, a Request instance or an arbitrary object
     */
    public function bind(mixed $data): DatalistInterface
    {
        if ($data instanceof Request) {
            $data = $data->query->all();
        }

        // Handle pagination
        if (isset($data['page'])) {
            $this->setPage((int) $data['page']);
        }

        // Handle search
        if (isset($data['search'])) {
            $this->searchQuery = $data['search'];
            $this->searchForm->submit(['search' => $data['search']]);
        }

        // Handle filters
        foreach ($this->filters as $filter) {
            if (isset($data[$filter->getName()]) && '' !== $data[$filter->getName()]) {
                $this->filterData[$filter->getName()] = $data[$filter->getName()];
            } elseif ($filter->hasOption('default')) {
                $this->filterData[$filter->getName()] = $filter->getOption('default');
            }
        }
        $this->filterForm->submit($this->filterData);

        return $this;
    }

    public function getIterator(): \Iterator
    {
        $this->initialize();

        return $this->iterator;
    }

    public function count(): int
    {
        $this->initialize();

        return \count($this->datasource);
    }

    public function setRouteParams(array $routeParams): self
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    public function setRoute(string $route): DatalistInterface
    {
        $this->route = $route;

        return $this;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    protected function getField(string $name): ?DatalistField
    {
        foreach ($this->fields as $field) {
            /** @var DatalistField $field */
            if ($field->getName() === $name) {
                return $field;
            }
        }

        return null;
    }

    /**
     * This method populates the iterator property.
     */
    private function initialize(): void
    {
        if ($this->initialized) {
            return;
        }

        if (!isset($this->datasource)) {
            throw new \Exception('A datalist must have a datasource before it can be iterated or counted');
        }

        // Handle pagination
        if ($this->hasOption('limit_per_page')) {
            $this->datasource
                ->paginate($this->getOption('limit_per_page'), $this->getOption('range_limit'))
                ->setPage($this->page);
        }

        // Handle search
        if (null !== $this->getOption('search') && !empty($this->searchQuery)) {
            $expressionBuilder = new DatalistFilterExpressionBuilder();
            $this->searchFilter->getType()->buildExpression(
                $expressionBuilder,
                $this->searchFilter,
                $this->searchQuery,
                $this->searchFilter->getOptions(),
            );
            $this->datasource->setSearchExpression($expressionBuilder->getExpression());
        }

        // Handle filters
        $expressionBuilder = new DatalistFilterExpressionBuilder();
        if (!empty($this->filterData)) {
            foreach ($this->filterData as $filterName => $filterValue) {
                $filter = $this->filters[$filterName];
                $filter->getType()->buildExpression($expressionBuilder, $filter, $filterValue, $filter->getOptions());
            }
            $this->datasource->setFilterExpression($expressionBuilder->getExpression());
        }

        // Handle sort
        if (isset($this->routeParams['sort-field'], $this->routeParams['sort-direction'])) {
            $field = $this->getField($this->routeParams['sort-field']);

            if (null !== $field && true === $field->getOption('sortable')) {
                $propertyPath = $field->getOption('sort_property_path');
                if (empty($propertyPath)) {
                    throw new \Exception('The "sort_property_path" option must be set on datalist field when option "sortable" is true.');
                }

                $this->datasource->setSort($propertyPath, $this->routeParams['sort-direction']);
            }
        }

        $this->iterator = $this->datasource->getIterator();
    }
}
