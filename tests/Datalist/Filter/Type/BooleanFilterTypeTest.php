<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\ArrayDatasource;
use Leapt\CoreBundle\Datalist\Filter\Type\BooleanFilterType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class BooleanFilterTypeTest extends TestCase
{
    public function testFilter(): void
    {
        $items = [
            [
                'name'   => 'Test 1',
                'active' => true,
                'used'   => '1',
            ],
            [
                'name'   => 'Test 2',
                'active' => false,
                'used'   => '1',
            ],
            [
                'name'   => 'Test 3',
                'active' => true,
                'used'   => '0',
            ],
        ];
        $datasource = new ArrayDatasource($items);

        $request = new Request(['active' => true]);
        $datalistFactory = new DatalistFactory($this->createMock(FormFactoryInterface::class), $this->createMock(RouterInterface::class));
        $datalist = $datalistFactory->createBuilder(DatalistType::class)
            ->addFilter('active', BooleanFilterType::class, [

            ])
            ->getDatalist();
        $datalist->setDatasource($datasource);
        $datalist->bind($request);
    }
}
