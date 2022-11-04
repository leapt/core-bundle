<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Paginator;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class DoctrineORMPaginator extends AbstractPaginator
{
    private ORMPaginator $doctrinePaginator;

    public function __construct(Query|QueryBuilder $query)
    {
        $this->doctrinePaginator = new ORMPaginator($query);
    }

    public function setPage(int $page): self
    {
        $page = 0 < $page ? $page : 1;
        $this->page = $page;
        $this->doctrinePaginator->getQuery()->setFirstResult($this->getOffset());

        return $this;
    }

    public function setLimitPerPage(int $limitPerPage): self
    {
        $this->limitPerPage = $limitPerPage;
        $this->doctrinePaginator->getQuery()->setMaxResults($limitPerPage);

        return $this;
    }

    public function count(): int
    {
        return $this->doctrinePaginator->count();
    }

    public function getIterator(): \ArrayIterator
    {
        return $this->doctrinePaginator->getIterator();
    }
}
