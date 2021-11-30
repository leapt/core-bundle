<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Paginator;

use Faker\Factory as FakerFactory;
use Leapt\CoreBundle\Paginator\ArrayPaginator;
use Leapt\CoreBundle\Paginator\PaginatorInterface;

class ArrayPaginatorTest extends AbstractPaginatorTest
{
    public function testIteration(): void
    {
        $items = $this->buildItems(20);
        $eleventhItem = $items[10];

        $paginator = new ArrayPaginator($items);
        $paginator->setLimitPerPage(10);
        $paginator->setPage(2);

        foreach ($paginator as $item) {
            break;
        }

        \assert(isset($item));
        $this->assertEquals($eleventhItem, $item);
    }

    protected function buildPaginator(int $limit): PaginatorInterface
    {
        $items = $this->buildItems($limit);

        return new ArrayPaginator($items);
    }

    private function buildItems(int $limit): array
    {
        $faker = FakerFactory::create();
        $items = [];
        for ($i = 1; $i <= $limit; ++$i) {
            $items[] = [
                'first_name' => $faker->firstName(),
                'last_name'  => $faker->lastName(),
            ];
        }

        return $items;
    }
}
