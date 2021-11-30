<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Datasource;

use Countable;
use IteratorAggregate;
use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;
use Leapt\CoreBundle\Paginator\PaginatorInterface;

interface DatasourceInterface extends IteratorAggregate, Countable
{
    public function paginate(int $limitPerPage, int $rangeLimit): self;

    public function setPage(int $page): self;

    public function setSearchExpression(ExpressionInterface $expression): mixed;

    public function setFilterExpression(ExpressionInterface $expression): mixed;

    public function getPaginator(): ?PaginatorInterface;

    public function setSort(string $field, string $direction): self;
}
