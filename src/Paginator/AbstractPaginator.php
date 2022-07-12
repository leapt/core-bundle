<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Paginator;

abstract class AbstractPaginator implements PaginatorInterface
{
    protected int $page = 1;

    protected int $limitPerPage = 0;

    protected int $rangeLimit = 10;

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimitPerPage(): int
    {
        return $this->limitPerPage;
    }

    /**
     * Set the maximum numbers of pagination links (1 2 3 4 > >>) to display.
     */
    public function setRangeLimit(int $rangeLimit): PaginatorInterface
    {
        $this->rangeLimit = $rangeLimit;

        return $this;
    }

    /**
     * Return a range array that can be used to build pagination links
     * The returned range is centered around the current page.
     */
    public function getRange(): array
    {
        if ($this->getPageCount() < $this->rangeLimit) { // Not enough pages to apply range limit
            $start = 1;
            $stop = $this->getPageCount();
        } else { // Enough page to apply range limit
            if ($this->getPage() <= $this->rangeLimit / 2) { // Cannot center, current page too far on the left
                $start = 1;
                $stop = $start + $this->rangeLimit - 1;
            } elseif ($this->getPage() + ceil($this->rangeLimit / 2) > $this->getPageCount()
            ) { // Cannot center, current page too far on the right
                $stop = $this->getPageCount();
                $start = $stop - $this->rangeLimit + 1;
            } else { // Enough space on both sides, we can center
                $start = $this->getPage() - floor($this->rangeLimit / 2) + 1;
                $stop = $start + $this->rangeLimit - 1;
            }
        }

        return range($start, $stop);
    }

    public function getPageCount(): int
    {
        $count = $this->count();

        // If limit is set to 0 or set to number bigger then total items count
        // display all in one page
        if ((1 > $this->limitPerPage) || ($this->limitPerPage > $count)) {
            return 1;
        }
        // Calculate rest numbers from dividing operation so we can add one
        // more page for this items
        $restItemsNum = $count % $this->limitPerPage;

        // if rest items > 0 then add one more page else just divide items
        // by limitPerPage
        return 0 < $restItemsNum ? (int) ($count / $this->limitPerPage) + 1 : (int) (
            $count / $this->limitPerPage
        );
    }

    protected function getOffset(): int
    {
        // Calculate offset for items based on current page number
        return ($this->page - 1) * $this->limitPerPage;
    }
}
