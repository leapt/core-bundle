<?php

namespace Leapt\CoreBundle\Paginator;

/**
 * Class ArrayPaginator
 * @package Leapt\CoreBundle\Paginator
 */
class ArrayPaginator extends AbstractPaginator
{
    /**
     * @var array
     */
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Set the paginator current page
     *
     * @param int $page
     * @return PaginatorInterface
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set the maximum number of items to display on a single page
     *
     * @param int $limitPerPage
     * @return PaginatorInterface
     */
    public function setLimitPerPage($limitPerPage)
    {
        $this->limitPerPage = $limitPerPage;

        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @return \LimitIterator
     */
    public function getIterator()
    {
        $innerIterator = new \ArrayIterator($this->items);

        return new \LimitIterator($innerIterator, $this->getOffset(), $this->limitPerPage);
    }
}