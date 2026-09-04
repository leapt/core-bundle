<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\ArrayDatasource;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilter;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterConfig;
use Leapt\CoreBundle\Datalist\Filter\Type\EntityFilterType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class EntityFilterTypeTest extends TestCase
{
    #[DataProvider('filterCasesProvider')]
    public function testFilter(string|array|null $searchValue, bool $multiple, array $expectedResult): void
    {
        $datasource = new ArrayDatasource($this->getItems());
        $entityFilterType = new EntityFilterType();
        $request = new Request(['category' => $searchValue]);

        $formBuilder = $this->createStub(FormBuilderInterface::class);
        $formBuilder->method('getForm')->willReturn($this->createStub(FormInterface::class));
        $formFactory = $this->createStub(FormFactoryInterface::class);
        $formFactory->method('createNamedBuilder')->willReturn($formBuilder);
        $datalistFactory = new DatalistFactory($formFactory, $this->createStub(RouterInterface::class));
        $datalistFactory->registerFilterType($entityFilterType);
        $datalist = $datalistFactory->createBuilder(DatalistType::class)
            ->addFilter('category', EntityFilterType::class, [
                'class'    => \stdClass::class,
                'multiple' => $multiple,
            ])
            ->getDatalist();
        $datalist->setDatasource($datasource);
        $datalist->bind($request);

        self::assertSame($expectedResult, array_values(iterator_to_array($datalist->getIterator())));
    }

    public static function filterCasesProvider(): iterable
    {
        yield 'empty_value' => ['', false, [['title' => 'The Hobbit', 'category' => 'Movies'], ['title' => 'Black Panther', 'category' => 'Movies'], ['title' => 'The Good Doctor', 'category' => 'TV Shows'], ['title' => 'Pawn of Prophecy', 'category' => 'Books']]];
        yield 'null_value' => [null, false, [['title' => 'The Hobbit', 'category' => 'Movies'], ['title' => 'Black Panther', 'category' => 'Movies'], ['title' => 'The Good Doctor', 'category' => 'TV Shows'], ['title' => 'Pawn of Prophecy', 'category' => 'Books']]];
        yield 'valid_value' => ['Movies', false, [['title' => 'The Hobbit', 'category' => 'Movies'], ['title' => 'Black Panther', 'category' => 'Movies']]];
        yield 'multiple' => [['TV Shows', 'Books'], true, [['title' => 'The Good Doctor', 'category' => 'TV Shows'], ['title' => 'Pawn of Prophecy', 'category' => 'Books']]];
    }

    public function testBuildFormForwardsOptionsToEntityType(): void
    {
        $entityFilterType = new EntityFilterType();
        $filterConfig = new DatalistFilterConfig('category', $entityFilterType, [
            'label'         => 'Category',
            'class'         => \stdClass::class,
            'query_builder' => null,
            'multiple'      => true,
            'choice_label'  => 'name',
            'placeholder'   => 'Select a category',
        ]);
        $filter = new DatalistFilter($filterConfig);

        $formBuilder = $this->createMock(FormBuilderInterface::class);
        $formBuilder->expects(self::once())
            ->method('add')
            ->with('category', EntityType::class, [
                'class'         => \stdClass::class,
                'label'         => 'Category',
                'query_builder' => null,
                'required'      => false,
                'multiple'      => true,
                'choice_label'  => 'name',
                'placeholder'   => 'Select a category',
            ]);

        $entityFilterType->buildForm($formBuilder, $filter, $filterConfig->getOptions());
    }

    private function getItems(): array
    {
        return [
            [
                'title'    => 'The Hobbit',
                'category' => 'Movies',
            ],
            [
                'title'    => 'Black Panther',
                'category' => 'Movies',
            ],
            [
                'title'    => 'The Good Doctor',
                'category' => 'TV Shows',
            ],
            [
                'title'    => 'Pawn of Prophecy',
                'category' => 'Books',
            ],
        ];
    }
}
