<?php

namespace Leapt\CoreBundle\Paginator;

/**
 * Interface PaginatorInterface.
 */
interface PaginatorInterface extends \Countable, \IteratorAggregate
{
    /**
     * Set the paginator current page.
     */
    public function setPage(int $page): self;

    /**
     * Get the paginator current page.
     */
    public function getPage(): int;

    /**
     * Set the maximum number of items to display on a single page.
     */
    public function setLimitPerPage(int $limitPerPage): self;

    /**
     * Get the maximum number of items to display on a single page.
     */
    public function getLimitPerPage(): int;

    /**
     * Set the maximum numbers of pagination links (1 2 3 4 > >>) to display.
     */
    public function setRangeLimit(int $rangeLimit): self;

    /**
     * Get the maximum numbers of pagination links to display.
     *
     * @return PaginatorInterface
     */
    public function getRange(): array;

    /**
     * Get the number of pages.
     */
    public function getPageCount(): int;
}
