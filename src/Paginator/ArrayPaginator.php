<?php

namespace Leapt\CoreBundle\Paginator;

/**
 * Class ArrayPaginator.
 */
class ArrayPaginator extends AbstractPaginator
{
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Set the paginator current page.
     *
     * @return PaginatorInterface
     */
    public function setPage(int $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set the maximum number of items to display on a single page.
     *
     * @return PaginatorInterface
     */
    public function setLimitPerPage(int $limitPerPage)
    {
        $this->limitPerPage = $limitPerPage;

        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object.
     *
     * @see http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     *             </p>
     *             <p>
     *             The return value is cast to an integer.
     */
    public function count()
    {
        return \count($this->items);
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
