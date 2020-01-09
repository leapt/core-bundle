<?php

namespace Leapt\CoreBundle\Paginator;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

/**
 * Class DoctrineORMPaginator.
 */
class DoctrineORMPaginator extends AbstractPaginator
{
    /**
     * @var \Doctrine\ORM\Tools\Pagination\Paginator
     */
    private $doctrinePaginator;

    public function __construct(AbstractQuery $query)
    {
        $this->doctrinePaginator = new ORMPaginator($query);
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $page = 0 < $page ? $page : 1;
        $this->page = $page;
        $this->doctrinePaginator->getQuery()->setFirstResult($this->getOffset());

        return $this;
    }

    /**
     * @param int $limitPerPage
     *
     * @return $this
     */
    public function setLimitPerPage($limitPerPage)
    {
        $this->limitPerPage = $limitPerPage;
        $this->doctrinePaginator->getQuery()->setMaxResults($limitPerPage);

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->doctrinePaginator->count();
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return $this->doctrinePaginator->getIterator();
    }
}
