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
    /**
     * @dataProvider filterCasesProvider
     */
    public function testFilter(string $property, bool|string $value, array $expectedResult): void
    {
        $datasource = new ArrayDatasource($this->getItems());
        $booleanFilterType = new BooleanFilterType();
        $request = new Request([$property => $value]);

        $datalistFactory = new DatalistFactory($this->createMock(FormFactoryInterface::class), $this->createMock(RouterInterface::class));
        $datalistFactory->registerFilterType($booleanFilterType);
        $datalist = $datalistFactory->createBuilder(DatalistType::class)
            ->addFilter($property, BooleanFilterType::class)
            ->getDatalist();
        $datalist->setDatasource($datasource);
        $datalist->bind($request);

        self::assertSame($expectedResult, array_values(iterator_to_array($datalist->getIterator())));
    }

    public function filterCasesProvider(): iterable
    {
        yield 'boolean_true' => ['active', true, [['name' => 'Test 1', 'active' => true, 'used' => '1'], ['name' => 'Test 3', 'active' => true, 'used' => '0']]];
        yield 'boolean_false' => ['active', false, [['name' => 'Test 2', 'active' => false, 'used' => '1']]];
        yield 'string_1' => ['used', '1', [['name' => 'Test 1', 'active' => true, 'used' => '1'], ['name' => 'Test 2', 'active' => false, 'used' => '1']]];
        yield 'string_0' => ['used', '0', [['name' => 'Test 3', 'active' => true, 'used' => '0']]];
    }

    private function getItems(): array
    {
        return [
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
    }
}
