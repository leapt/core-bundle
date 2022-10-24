<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\ArrayDatasource;
use Leapt\CoreBundle\Datalist\Filter\Type\EnumFilterType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use Leapt\CoreBundle\Tests\Datalist\Filter\Type\Enums\Category;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class EnumFilterTypeTest extends TestCase
{
    /**
     * @requires PHP >= 8.1
     *
     * @dataProvider filterCasesProvider
     */
    public function testFilter(\BackedEnum|string|array|null $searchValue, bool $multiple, array $expectedResult): void
    {
        $datasource = new ArrayDatasource($this->getItems());
        $enumFilterType = new EnumFilterType();
        $request = new Request(['category' => $searchValue]);

        $formBuilder = $this->createMock(FormBuilderInterface::class);
        $formBuilder->method('getForm')->willReturn($this->createMock(FormInterface::class));
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('createNamedBuilder')->willReturn($formBuilder);
        $datalistFactory = new DatalistFactory($formFactory, $this->createMock(RouterInterface::class));
        $datalistFactory->registerFilterType($enumFilterType);
        $datalist = $datalistFactory->createBuilder(DatalistType::class)
            ->addFilter('category', EnumFilterType::class, [
                'class'    => Category::class,
                'multiple' => $multiple,
            ])
            ->getDatalist();
        $datalist->setDatasource($datasource);
        $datalist->bind($request);

        self::assertSame($expectedResult, array_values(iterator_to_array($datalist->getIterator())));
    }

    /**
     * @requires PHP >= 8.1
     */
    public function filterCasesProvider(): iterable
    {
        yield 'empty_value' => ['', false, [['title' => 'The Hobbit', 'category' => Category::Movies], ['title' => 'Black Panther', 'category' => Category::Movies], ['title' => 'The Good Doctor', 'category' => Category::TVShows], ['title' => 'Pawn of Prophecy', 'category' => Category::Books]]];
        yield 'null_value' => [null, false, [['title' => 'The Hobbit', 'category' => Category::Movies], ['title' => 'Black Panther', 'category' => Category::Movies], ['title' => 'The Good Doctor', 'category' => Category::TVShows], ['title' => 'Pawn of Prophecy', 'category' => Category::Books]]];
        yield 'valid_value' => [Category::Movies, false, [['title' => 'The Hobbit', 'category' => Category::Movies], ['title' => 'Black Panther', 'category' => Category::Movies]]];
        yield 'multiple' => [[Category::TVShows, Category::Books], true, [['title' => 'The Good Doctor', 'category' => Category::TVShows], ['title' => 'Pawn of Prophecy', 'category' => Category::Books]]];
    }

    private function getItems(): array
    {
        return [
            [
                'title'    => 'The Hobbit',
                'category' => Category::Movies,
            ],
            [
                'title'    => 'Black Panther',
                'category' => Category::Movies,
            ],
            [
                'title'    => 'The Good Doctor',
                'category' => Category::TVShows,
            ],
            [
                'title'    => 'Pawn of Prophecy',
                'category' => Category::Books,
            ],
        ];
    }
}
