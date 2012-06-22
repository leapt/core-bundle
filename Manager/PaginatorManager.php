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
     * The number of page links shown in the paginator widget
     *
     * @var int
     */
    private $limitRange;

    /**
     * @var int
     */
    private $count;

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
        if (!isset($this->count)) {
            $this->count = Paginate::getTotalQueryResults($this->query);
        }
        return $this->count;
    }

    /**
     * Get the paginated result
     *
     * @return array
     */
    public function getResult()
    {
        // First we need to check if there are actual results
        if ($this->getCount() > 0) {
            $query = Paginate::getPaginateQuery($this->query, $this->getOffset(), $this->limitPerPage);
        } else {
            $query = $this->query;
        }

        return $query->getResult();
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
        //Calculate offset for items based on current page number
        return ($this->page - 1) * $this->limitPerPage;
    }

    /**
     * @param int $limitRange
     */
    public function setLimitRange($limitRange)
    {
        $this->limitRange = $limitRange;
    }

    /**
     * Return a range array that can be used to build pagination links
     * The returned range is centered around the current page
     *
     * @return array
     */
    public function getRange()
    {
        if ($this->getNumPages() < $this->limitRange) { // Not enough pages to apply range limit
            $start = 1;
            $stop = $this->getNumPages();
        } else { // Enough page to apply range limit
            if($this->getPage() <= $this->limitRange / 2) { // Cannot center, current page too far on the left
                $start = 1;
                $stop = $start + $this->limitRange - 1;
            }
            elseif($this->getPage() + ceil($this->limitRange / 2) > $this->getNumPages()) { // Cannot center, current page too far on the right
                $stop = $this->getNumPages();
                $start = $stop - $this->limitRange + 1;
            }
            else { // Enough space on both sides, we can center
                $start = $this->getPage() - floor($this->limitRange / 2) + 1;
                $stop = $start + $this->limitRange -1;
            }
        }

        return range($start, $stop);
    }


}