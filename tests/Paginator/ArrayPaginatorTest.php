<?php

namespace Leapt\CoreBundle\Tests\Paginator;

use Faker\Factory as FakerFactory;
use Leapt\CoreBundle\Paginator\ArrayPaginator;

class ArrayPaginatorTest extends AbstractPaginatorTest
{
    /**
     * Test the IteratorAggregate implementation.
     */
    public function testIteration()
    {
        $items = $this->buildItems(20);
        $eleventhItem = $items[10];

        $paginator = new ArrayPaginator($items);
        $paginator->setLimitPerPage(10);
        $paginator->setPage(2);

        foreach ($paginator as $item) {
            break;
        }

        $this->assertEquals($eleventhItem, $item);
    }

    /**
     * Build a populated paginator instance.
     *
     * @param int $limit
     *
     * @return \Leapt\CoreBundle\Paginator\PaginatorInterface
     */
    protected function buildPaginator($limit)
    {
        $items = $this->buildItems($limit);
        $paginator = new ArrayPaginator($items);

        return $paginator;
    }

    /**
     * Build a simple array of items to be used in the paginator.
     *
     * @param int $limit
     *
     * @return array
     */
    private function buildItems($limit)
    {
        $faker = FakerFactory::create();
        $items = [];
        for ($i = 1; $i <= $limit; ++$i) {
            $items[] = [
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
            ];
        }

        return $items;
    }
}
