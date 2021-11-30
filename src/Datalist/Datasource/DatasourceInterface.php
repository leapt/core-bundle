<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Datasource;

use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;
use Leapt\CoreBundle\Paginator\PaginatorInterface;

/**
 * Interface DatasourceInterface.
 */
interface DatasourceInterface extends IteratorAggregate, Countable
{
    public function paginate(int $limitPerPage, int $rangeLimit): self;

    public function setPage(int $page);

    public function setSearchExpression(ExpressionInterface $expression): mixed;

    public function setFilterExpression(ExpressionInterface $expression): mixed;

    public function getPaginator(): PaginatorInterface;

    /**
     * @throws InvalidArgumentException
     */
    public function setSort(string $field, string $direction);
}
