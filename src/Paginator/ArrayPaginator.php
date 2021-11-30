<?php

namespace Leapt\CoreBundle\Paginator;

class ArrayPaginator extends AbstractPaginator
{
    public function __construct(private array $items)
    {
    }

    /**
     * Set the paginator current page.
     */
    public function setPage(int $page): PaginatorInterface
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set the maximum number of items to display on a single page.
     */
    public function setLimitPerPage(int $limitPerPage): PaginatorInterface
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
    public function count(): int
    {
        return \count($this->items);
    }

    public function getIterator(): \LimitIterator
    {
        $innerIterator = new \ArrayIterator($this->items);

        return new \LimitIterator($innerIterator, $this->getOffset(), $this->limitPerPage);
    }
}
