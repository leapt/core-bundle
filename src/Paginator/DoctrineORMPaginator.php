<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Paginator;

use ArrayIterator;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class DoctrineORMPaginator extends AbstractPaginator
{
    private ORMPaginator $doctrinePaginator;

    public function __construct(AbstractQuery $query)
    {
        $this->doctrinePaginator = new ORMPaginator($query);
    }

    /**
     * @return $this
     */
    public function setPage(int $page): self
    {
        $page = 0 < $page ? $page : 1;
        $this->page = $page;
        $this->doctrinePaginator->getQuery()->setFirstResult($this->getOffset());

        return $this;
    }

    /**
     * @return $this
     */
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

    /**
     * @return \Traversable
     */
    public function getIterator(): ArrayIterator
    {
        return $this->doctrinePaginator->getIterator();
    }
}
