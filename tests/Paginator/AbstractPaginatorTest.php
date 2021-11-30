<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Paginator;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractPaginatorTest extends WebTestCase
{
    /**
     * Test the default value of the paginator class.
     */
    public function testDefaults()
    {
        $paginator = $this->buildPaginator(10);

        $this->assertEquals(1, $paginator->getPage());
        $this->assertEquals(0, $paginator->getLimitPerPage());
    }

    /**
     * Test the implementation of the countabmle interface.
     */
    public function testCount()
    {
        $paginator = $this->buildPaginator(7);

        $this->assertEquals(7, \count($paginator));
    }

    /**
     * Test the page count method.
     */
    public function testGetPageCount()
    {
        $paginator = $this->buildPaginator(7);
        $paginator->setLimitPerPage(10);

        $this->assertEquals(1, $paginator->getPageCount());

        $paginator->setLimitPerPage(5);
        $this->assertEquals(2, $paginator->getPageCount());
    }

    /**
     * Test the getRange method.
     */
    public function testGetRange()
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

    /**
     * Test the IteratorAggregate implementation.
     */
    abstract public function testIteration();

    /**
     * Build a populated paginator instance.
     *
     * @param int $limit
     *
     * @return \Leapt\CoreBundle\Paginator\PaginatorInterface
     */
    abstract protected function buildPaginator($limit);
}
