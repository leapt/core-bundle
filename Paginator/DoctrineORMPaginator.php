<?php

namespace Snowcap\CoreBundle\Paginator;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class DoctrineORMPaginator extends AbstractPaginator
{
    /**
     * @var ORMPaginator
     */
    private $doctrinePaginator;

    /**
     * @param \Doctrine\ORM\Query $query
     */
    public function __construct(Query $query)
    {
        $this->doctrinePaginator = new ORMPaginator($query);
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $page = $page > 0 ? $page : 1;
        $this->page = $page;
        $this->doctrinePaginator->getQuery()->setFirstResult($this->getOffset());

        return $this;
    }

    /**
     * @param int $limitPerPage
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