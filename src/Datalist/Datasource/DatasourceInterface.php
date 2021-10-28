<?php

namespace Leapt\CoreBundle\Datalist\Datasource;

use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;

/**
 * Interface DatasourceInterface.
 */
interface DatasourceInterface extends \IteratorAggregate, \Countable
{
    /**
     * @return DatasourceInterface
     */
    public function paginate(int $limitPerPage, int $rangeLimit);

    public function setPage(int $page);

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
     * @throws \InvalidArgumentException
     */
    public function setSort(string $field, string $direction);
}
