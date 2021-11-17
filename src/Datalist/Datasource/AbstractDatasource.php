<?php

namespace Leapt\CoreBundle\Datalist\Datasource;

use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;

abstract class AbstractDatasource implements DatasourceInterface
{
    protected int $page;

    protected int $limitPerPage;

    protected int $rangeLimit;

    protected string $searchQuery;

    protected ExpressionInterface $filterExpression;

    protected ExpressionInterface $searchExpression;

    protected \Traversable $iterator;

    protected \Leapt\CoreBundle\Paginator\PaginatorInterface $paginator;

    protected string $sortField;

    protected string $sortDirection;

    /**
     * @return DatasourceInterface
     */
    public function paginate(int $limitPerPage, int $rangeLimit)
    {
        $this->limitPerPage = $limitPerPage;
        $this->rangeLimit = $rangeLimit;

        return $this;
    }

    /**
     * @return DatasourceInterface
     */
    public function setPage(int $page)
    {
        $this->page = $page;

        return $this;
    }

    public function setSearchExpression(ExpressionInterface $expression)
    {
        $this->searchExpression = $expression;
    }

    public function setFilterExpression(ExpressionInterface $expression)
    {
        $this->filterExpression = $expression;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function setSort(string $field, string $direction)
    {
        if (!\in_array($direction, ['asc', 'desc'], true)) {
            throw new \InvalidArgumentException('Datasource->setSort(): Argument "direction" must be "asc" or "desc".');
        }

        $this->sortField = $field;
        $this->sortDirection = $direction;
    }

    /**
     * @return string
     */
    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    /**
     * @return string
     */
    public function getSortField()
    {
        return $this->sortField;
    }

    /**
     * @return int
     */
    public function count()
    {
        $this->initialize();

        return \count($this->iterator);
    }

    /**
     * This method should populated the iterator and paginator member variables.
     */
    abstract protected function initialize();
}
