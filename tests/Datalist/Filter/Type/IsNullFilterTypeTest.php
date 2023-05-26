<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\ArrayDatasource;
use Leapt\CoreBundle\Datalist\Filter\Type\IsNullFilterType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class IsNullFilterTypeTest extends TestCase
{
    /**
     * @dataProvider filterCasesProvider
     */
    public function testFilter(?string $value, array $expectedResult): void
    {
        $datasource = new ArrayDatasource($this->getItems());
        $isNullFilterType = new IsNullFilterType();
        $request = new Request(['description' => $value]);

        $formBuilder = $this->createMock(FormBuilderInterface::class);
        $formBuilder->method('getForm')->willReturn($this->createMock(FormInterface::class));
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('createNamedBuilder')->willReturn($formBuilder);
        $datalistFactory = new DatalistFactory($formFactory, $this->createMock(RouterInterface::class));
        $datalistFactory->registerFilterType($isNullFilterType);
        $datalist = $datalistFactory->createBuilder(DatalistType::class)
            ->addFilter('description', IsNullFilterType::class)
            ->getDatalist();
        $datalist->setDatasource($datasource);
        $datalist->bind($request);

        self::assertSame($expectedResult, array_values(iterator_to_array($datalist->getIterator())));
    }

    public static function filterCasesProvider(): iterable
    {
        yield 'empty_value' => [null, [['name' => 'Test 1', 'description' => 'My first test'], ['name' => 'Test 2', 'description' => null]]];
        yield 'is_null' => ['1', [['name' => 'Test 2', 'description' => null]]];
        yield 'is_not_null' => ['0', [['name' => 'Test 1', 'description' => 'My first test']]];
    }

    private function getItems(): array
    {
        return [
            [
                'name'        => 'Test 1',
                'description' => 'My first test',
            ],
            [
                'name'        => 'Test 2',
                'description' => null,
            ],
        ];
    }
}
