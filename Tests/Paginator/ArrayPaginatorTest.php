<?php

namespace Snowcap\CoreBundle\Tests\Paginator;

use Snowcap\CoreBundle\Paginator\ArrayPaginator;

use Faker\Factory as FakerFactory;

class ArrayPaginatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the default value of the paginator class
     *
     */
    public function testDefaults()
    {
        $paginator = $this->buildPaginator(10);

        $this->assertEquals(1, $paginator->getPage());
        $this->assertEquals(0, $paginator->getLimitPerPage());
    }

    /**
     * Test the implementation of the countabmle interface
     *
     */
    public function testCount()
    {
        $items = $this->buildItems(7);
        $paginator = new ArrayPaginator($items);

        $this->assertEquals(7, count($paginator));
    }

    /**
     * Test the page count method
     *
     */
    public function testGetPageCount()
    {
        $items = $this->buildItems(7);
        $paginator = new ArrayPaginator($items);
        $paginator->setLimitPerPage(10);

        $this->assertEquals(1, $paginator->getPageCount());

        $paginator->setLimitPerPage(5);
        $this->assertEquals(2, $paginator->getPageCount());
    }

    /**
     * Test the getRange method
     *
     */
    public function testGetRange()
    {
        $items = $this->buildItems(150);
        $paginator = new ArrayPaginator($items);
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
     * Test the IteratorAggregate implementation
     *
     */
    public function testIteration()
    {
        $items = $this->buildItems(20);
        $eleventhItem = $items[10];

        $paginator = new ArrayPaginator($items);
        $paginator->setLimitPerPage(10);
        $paginator->setPage(2);

        foreach($paginator as $item) {
            break;
        }

        $this->assertEquals($eleventhItem, $item);
    }

    /**
     * Build a populated paginator instance
     *
     * @param int $limit
     * @return \Snowcap\CoreBundle\Paginator\PaginatorInterface
     */
    protected function buildPaginator($limit)
    {
        $items = $this->buildItems($limit);
        $paginator = new ArrayPaginator($items);

        return $paginator;
    }

    /**
     * Build a simple array of items to be used in the paginator
     *
     * @param int $limit
     * @return array
     */
    private function buildItems($limit)
    {
        $faker = FakerFactory::create();
        $items = array();
        for ($i = 1; $i <= $limit; $i++) {
            $items[]= array(
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName
            );
        }

        return $items;
    }
}