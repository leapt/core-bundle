<?php

namespace Snowcap\CoreBundle\Manager;

use DoctrineExtensions\Paginate\Paginate;

class PaginatorManager
{

    /**
     * @var \Doctrine\ORM\Query
     */
    private $query;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $limitPerPage;

    /**
     * @param \Doctrine\ORM\Query $query
     * @param int $page
     * @param int $limitPerPage
     */
    public function __construct($query = null, $page = 1, $limitPerPage = 0)
    {
        $this->query = $query;
        $this->page = $page > 0 ? $page : 1;
        $this->limitPerPage = $limitPerPage;
    }

    /**
     * @param \Doctrine\ORM\Query $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page > 0 ? $page : 1;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $limitPerPage
     */
    public function setLimitPerPage($limitPerPage)
    {
        $this->limitPerPage = $limitPerPage;
    }

    /**
     * @return int
     */
    public function getLimitPerPage()
    {
        return $this->limitPerPage;
    }

    /**
     * Get the total of row available
     *
     * @return int
     */
    public function getCount()
    {

        return Paginate::getTotalQueryResults($this->query);
    }

    /**
     * Get the paginated result
     *
     * @return array
     */
    public function getResult()
    {
        $paginateQuery = Paginate::getPaginateQuery($this->query, $this->getOffset(), $this->limitPerPage);

        return $paginateQuery->getResult();
    }

    public function getNumPages()
    {
        $count = $this->getCount();

        //If limit is set to 0 or set to number bigger then total items count
        //display all in one page
        if (($this->limitPerPage < 1) || ($this->limitPerPage > $count)) {

            return 1;
        } else {
            //Calculate rest numbers from dividing operation so we can add one
            //more page for this items
            $restItemsNum = $count % $this->limitPerPage;
            //if rest items > 0 then add one more page else just divide items
            //by limitPerPage
            return ($restItemsNum > 0 ? intval($count / $this->limitPerPage) + 1 : intval($count / $this->limitPerPage));
        }
    }

    private function getOffset()
    {
        //Calculet offset for items based on current page number
        return ($this->page - 1) * $this->limitPerPage;
    }

}