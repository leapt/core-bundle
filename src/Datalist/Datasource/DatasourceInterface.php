<?php

namespace Leapt\CoreBundle\Datalist\Datasource;

use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;

/**
 * Interface DatasourceInterface.
 */
interface DatasourceInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param int $limitPerPage
     * @param int $rangeLimit
     *
     * @return DatasourceInterface
     */
    public function paginate($limitPerPage, $rangeLimit);

    /**
     * @param int $page
     */
    public function setPage($page);

    /**
     * @return mixed
     */
    public function setSearchExpression(ExpressionInterface $expression);

    /**
     * @return mixed
     */
    public function setFilterExpression(ExpressionInterface $expression);

    /**
     * @return \Leapt\CoreBundle\Paginator\PaginatorInterface
     */
    public function getPaginator();

    /**
     * @param string $field
     * @param string $direction
     *
     * @throws \InvalidArgumentException
     */
    public function setSort($field, $direction);
}
