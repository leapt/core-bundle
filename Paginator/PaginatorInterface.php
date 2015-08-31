<?php

namespace Leapt\CoreBundle\Paginator;

/**
 * Interface PaginatorInterface
 * @package Leapt\CoreBundle\Paginator
 */
interface PaginatorInterface extends \Countable, \IteratorAggregate
{
    /**
     * Set the paginator current page
     *
     * @param int $page
     * @return PaginatorInterface
     */
    public function setPage($page);

    /**
     * Get the paginator current page
     *
     * @return int
     */
    public function getPage();

    /**
     * Set the maximum number of items to display on a single page
     *
     * @param int $limitPerPage
     * @return PaginatorInterface
     */
    public function setLimitPerPage($limitPerPage);

    /**
     * Get the maximum number of items to display on a single page
     *
     * @return int
     */
    public function getLimitPerPage();

    /**
     * Set the maximum numbers of pagination links (1 2 3 4 > >>) to display
     *
     * @param int $rangeLimit
     * @return PaginatorInterface
     */
    public function setRangeLimit($rangeLimit);

    /**
     * Get the maximum numbers of pagination links to display
     *
     * @return PaginatorInterface
     */
    public function getRange();

    /**
     * Get the number of pages
     *
     * @return int
     */
    public function getPageCount();
}