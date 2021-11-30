<?php

namespace Leapt\CoreBundle\Datalist;

use Leapt\CoreBundle\Datalist\Action\DatalistActionInterface;
use Leapt\CoreBundle\Datalist\Datasource\DatasourceInterface;
use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Symfony\Component\Form\FormInterface;

interface DatalistInterface extends \IteratorAggregate
{
    public function getType(): TypeInterface;

    public function addField(DatalistFieldInterface $field): self;

    public function getFields(): array;

    /**
     * @param Filter\DatalistFilterInterface $filter
     */
    public function addFilter(DatalistFilterInterface $filter): self;

    public function getFilters(): array;

    /**
     * @param Filter\DatalistFilterInterface $filter
     */
    public function setSearchFilter(DatalistFilterInterface $filter);

    /**
     * @return Filter\DatalistFilterInterface
     */
    public function getSearchFilter(): DatalistFilterInterface;

    /**
     * @param Action\DatalistActionInterface $action
     */
    public function addAction(DatalistActionInterface $action): self;

    public function getActions(): array;

    /**
     * @param DatasourceInterface $datasource
     */
    public function setDatasource($datasource): self;

    public function getDatasource(): DatasourceInterface;

    public function getName(): string;

    public function getOptions(): array;

    /**
     * @param string $name
     */
    public function hasOption($name): bool;

    /**
     * @param string $name
     * @param mixed  $default
     */
    public function getOption($name, $default = null);

    /**
     * @param int $page
     */
    public function setPage($page): self;

    public function isFilterable(): bool;

    public function isSearchable(): bool;

    public function setSearchForm(FormInterface $form): self;

    public function setFilterForm(FormInterface $form): self;

    public function getSearchForm(): FormInterface;

    public function getFilterForm(): FormInterface;

    /**
     * @param mixed $data
     */
    public function bind($data): self;

    public function getRoute(): string;

    public function setRoute(string $route): self;

    public function getRouteParams(): array;

    public function setRouteParams(array $routeParams): mixed;
}
