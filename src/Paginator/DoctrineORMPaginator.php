<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Paginator;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\OffsetPaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as LegacyPaginator;
use Doctrine\ORM\Tools\Pagination\Window;
use Doctrine\ORM\Tools\Pagination\WindowPage;

class DoctrineORMPaginator extends AbstractPaginator
{
    private readonly Query $query;

    /** Used only as a fallback on doctrine/orm < 3.7, which does not provide OffsetPaginator yet. */
    private readonly ?LegacyPaginator $legacyPaginator;

    private readonly ?OffsetPaginator $offsetPaginator;

    private ?WindowPage $windowPage = null;

    public function __construct(Query|QueryBuilder $query, bool $fetchJoinCollection = true)
    {
        $this->query = $query instanceof QueryBuilder ? $query->getQuery() : $query;

        if (class_exists(OffsetPaginator::class)) {
            $this->offsetPaginator = new OffsetPaginator($fetchJoinCollection);
            $this->legacyPaginator = null;
        } else {
            $this->offsetPaginator = null;
            $this->legacyPaginator = new LegacyPaginator($this->query, $fetchJoinCollection);
        }
    }

    public function setPage(int $page): self
    {
        $this->page = 0 < $page ? $page : 1;
        $this->windowPage = null;

        return $this;
    }

    public function setLimitPerPage(int $limitPerPage): self
    {
        $this->limitPerPage = $limitPerPage;
        $this->windowPage = null;

        return $this;
    }

    public function count(): int
    {
        if (null !== $this->legacyPaginator) {
            $this->applyLegacyPagination();

            return $this->legacyPaginator->count();
        }

        return $this->getWindowPage()->getTotalCount();
    }

    public function getIterator(): \ArrayIterator
    {
        if (null !== $this->legacyPaginator) {
            $this->applyLegacyPagination();

            return new \ArrayIterator(iterator_to_array($this->legacyPaginator->getIterator()));
        }

        return new \ArrayIterator($this->getWindowPage()->getItems());
    }

    /** A limitPerPage of 0 (the default) means "no limit, display everything on a single page". */
    private function applyLegacyPagination(): void
    {
        $limitPerPage = 0 < $this->limitPerPage ? $this->limitPerPage : null;

        $this->legacyPaginator->getQuery()
            ->setFirstResult(null !== $limitPerPage ? $this->getOffset() : 0)
            ->setMaxResults($limitPerPage);
    }

    private function getWindowPage(): WindowPage
    {
        $window = 0 < $this->limitPerPage
            ? Window::fromPageNumberAndSize($this->page, $this->limitPerPage)
            : new Window(0, \PHP_INT_MAX);

        return $this->windowPage ??= $this->offsetPaginator->paginate($this->query, $window);
    }
}
