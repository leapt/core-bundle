<?php

namespace Leapt\CoreBundle\Datalist\Datasource;

use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractDatasource
 * @package Leapt\CoreBundle\Datalist\Datasource
 */
abstract class AbstractDatasource implements DatasourceInterface
{
    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $limitPerPage;

    /**
     * @var int
     */
    protected $rangeLimit;

    /**
     * @var string
     */
    protected $searchQuery;

    /**
     * @var ExpressionInterface
     */
    protected $filterExpression;

    /**
     * @var ExpressionInterface
     */
    protected $searchExpression;

    /**
     * @var \Traversable
     */
    protected $iterator;

    /**
     * @var \Leapt\CoreBundle\Paginator\PaginatorInterface
     */
    protected $paginator;

    /**
     * @var string
     */
    protected $sortField;

    /**
     * @var string
     */
    protected $sortDirection;

    /**
     * @param int $limitPerPage
     * @param int $rangeLimit
     *
     * @return DatasourceInterface
     */
    public function paginate($limitPerPage, $rangeLimit)
    {
        $this->limitPerPage = $limitPerPage;
        $this->rangeLimit = $rangeLimit;

        return $this;
    }

    /**
     * @param int $page
     *
     * @return DatasourceInterface
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param \Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface $expression
     */
    public function setSearchExpression(ExpressionInterface $expression)
    {
        $this->searchExpression = $expression;
    }

    /**
     * @param \Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface $expression
     */
    public function setFilterExpression(ExpressionInterface $expression)
    {
        $this->filterExpression = $expression;
    }

    /**
     * @param string $field
     * @param string $direction
     * @throws \InvalidArgumentException
     */
    public function setSort($field, $direction)
    {
        if (!in_array($direction, array('asc', 'desc'))) {
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
     * This method should populated the iterator and paginator member variables
     */
    abstract protected function initialize();

    /**
     * @return int
     */
    public function count()
    {
        $this->initialize();

        return count($this->iterator);
    }
}