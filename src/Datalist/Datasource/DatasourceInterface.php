<?php

namespace Leapt\CoreBundle\Datalist\Datasource;

use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;

/**
 * Interface DatasourceInterface.
 */
interface DatasourceInterface extends \IteratorAggregate, \Countable
{
    public function paginate(int $limitPerPage, int $rangeLimit): self;

    public function setPage(int $page);

    public function setSearchExpression(ExpressionInterface $expression): mixed;

    public function setFilterExpression(ExpressionInterface $expression): mixed;

    public function getPaginator(): \Leapt\CoreBundle\Paginator\PaginatorInterface;

    /**
     * @throws \InvalidArgumentException
     */
    public function setSort(string $field, string $direction);
}
