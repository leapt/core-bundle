<?php

namespace Snowcap\Paginator;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorManager
{
    /**
     * @var \Doctrine\ORM\Tools\Pagination\Paginator
     */
    private $paginator;

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
     * @param \Doctrine\ORM\Query $query
     * @param int $page
     * @param int $limitPerPage
     */
    public function __construct($query = null, $page = 1, $limitPerPage = 0, $limitRange = 10)
    {
        $this->paginator = new Paginator($query);
        $this->page = $page > 0 ? $page : 1;
        $this->limitPerPage = $limitPerPage;
        $this->limitRange = $limitRange;
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
        return count($this->paginator);
    }

    /**
     * Get the paginated result
     *
     * @return Paginator
     */
    public function getPaginator()
    {
        // First we need to check if there are actual results
        if ($this->getCount() > 0) {
            $this->paginator->getQuery()
                ->setFirstResult($this->getOffset())
                ->setMaxResults($this->limitPerPage);
        }

        return $this->paginator;
    }

    /**
     * @return int
     */
    public function getPageCount()
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

    /**
     * @return int
     */
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