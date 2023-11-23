<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Paginator;

use Leapt\CoreBundle\Paginator\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractPaginatorTestCase extends WebTestCase
{
    /**
     * Test the default value of the paginator class.
     */
    public function testDefaults(): void
    {
        $paginator = $this->buildPaginator(10);

        $this->assertEquals(1, $paginator->getPage());
        $this->assertEquals(0, $paginator->getLimitPerPage());
    }

    public function testCount(): void
    {
        $paginator = $this->buildPaginator(7);

        $this->assertCount(7, $paginator);
    }

    public function testGetPageCount(): void
    {
        $paginator = $this->buildPaginator(7);
        $paginator->setLimitPerPage(10);

        $this->assertEquals(1, $paginator->getPageCount());

        $paginator->setLimitPerPage(5);
        $this->assertEquals(2, $paginator->getPageCount());
    }

    public function testGetRange(): void
    {
        $paginator = $this->buildPaginator(150);
        $paginator
            ->setLimitPerPage(10)
            ->setRangeLimit(10)
            ->setPage(1);

        $this->assertEquals(range(1, 10), $paginator->getRange());

        $paginator->setPage(9);
        $this->assertEquals(range(5, 14), $paginator->getRange());

        $paginator->setPage(14);
        $this->assertEquals(range(6, 15), $paginator->getRange());
    }

    abstract public function testIteration();

    abstract protected function buildPaginator(int $limit): PaginatorInterface;
}
