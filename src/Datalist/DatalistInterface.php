<?php

declare(strict_types=1);

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

    public function addFilter(DatalistFilterInterface $filter): self;

    public function getFilters(): array;

    public function setSearchFilter(DatalistFilterInterface $filter);

    public function getSearchFilter(): DatalistFilterInterface;

    public function addAction(DatalistActionInterface $action): self;

    public function getActions(): array;

    public function setDatasource(DatasourceInterface $datasource): self;

    public function getDatasource(): DatasourceInterface;

    public function getName(): string;

    public function getOptions(): array;

    public function hasOption(string $name): bool;

    public function getOption(string $name, mixed $default = null): mixed;

    public function setPage(int $page): self;

    public function isFilterable(): bool;

    public function isSearchable(): bool;

    public function setSearchForm(FormInterface $form): self;

    public function setFilterForm(FormInterface $form): self;

    public function getSearchForm(): FormInterface;

    public function getFilterForm(): FormInterface;

    public function bind(mixed $data): self;

    public function getRoute(): string;

    public function setRoute(string $route): self;

    public function getRouteParams(): array;

    public function setRouteParams(array $routeParams): mixed;
}
