<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Datasource;

use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;
use Leapt\CoreBundle\Paginator\PaginatorInterface;

abstract class AbstractDatasource implements DatasourceInterface
{
    protected int $page;

    protected int $limitPerPage;

    protected int $rangeLimit;

    protected string $searchQuery;

    protected ?ExpressionInterface $filterExpression = null;

    protected ?ExpressionInterface $searchExpression = null;

    protected \Iterator $iterator;

    protected ?PaginatorInterface $paginator;

    protected ?string $sortField = null;

    protected ?string $sortDirection = null;

    public function paginate(int $limitPerPage, int $rangeLimit): DatasourceInterface
    {
        $this->limitPerPage = $limitPerPage;
        $this->rangeLimit = $rangeLimit;

        return $this;
    }

    public function setPage(int $page): DatasourceInterface
    {
        $this->page = $page;

        return $this;
    }

    public function setSearchExpression(ExpressionInterface $expression): self
    {
        $this->searchExpression = $expression;

        return $this;
    }

    public function setFilterExpression(ExpressionInterface $expression): self
    {
        $this->filterExpression = $expression;

        return $this;
    }

    public function setSort(string $field, string $direction): self
    {
        if (!\in_array($direction, ['asc', 'desc'], true)) {
            throw new \InvalidArgumentException('Datasource->setSort(): Argument "direction" must be "asc" or "desc".');
        }

        $this->sortField = $field;
        $this->sortDirection = $direction;

        return $this;
    }

    public function getSortDirection(): ?string
    {
        return $this->sortDirection;
    }

    public function getSortField(): ?string
    {
        return $this->sortField;
    }

    public function count(): int
    {
        $this->initialize();

        return \count($this->iterator);
    }

    /**
     * This method should populate the iterator and paginator member variables.
     */
    abstract protected function initialize();
}
