<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Paginator;

class ArrayPaginator extends AbstractPaginator
{
    public function __construct(private array $items)
    {
    }

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
