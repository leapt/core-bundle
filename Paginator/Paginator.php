<?php

namespace Snowcap\CoreBundle\Paginator;

use Doctrine\ORM\Tools\Pagination\Paginator as BasePaginator;

class Paginator extends BasePaginator
{
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
     * @param int $page
     */
    public function setPage($page)
    {
        $page = $page > 0 ? $page : 1;
        $this->page = $page;
        $this->getQuery()->setFirstResult($this->getOffset());

        return $this;
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
        $this->getQuery()->setMaxResults($limitPerPage);
        $this->limitPerPage = $limitPerPage;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimitPerPage()
    {
        return $this->limitPerPage;
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
        $count = $this->count();

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
        if ($this->getPageCount() < $this->limitRange) { // Not enough pages to apply range limit
            $start = 1;
            $stop = $this->getPageCount();
        } else { // Enough page to apply range limit
            if($this->getPage() <= $this->limitRange / 2) { // Cannot center, current page too far on the left
                $start = 1;
                $stop = $start + $this->limitRange - 1;
            }
            elseif($this->getPage() + ceil($this->limitRange / 2) > $this->getPageCount()) { // Cannot center, current page too far on the right
                $stop = $this->getPageCount();
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