<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\ArrayDatasource;
use Leapt\CoreBundle\Datalist\Filter\Type\SearchFilterType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class SearchFilterTypeTest extends TestCase
{
    /**
     * @dataProvider filterCasesProvider
     */
    public function testFilter(string|array $searchFields, string $searchValue, array $expectedResult): void
    {
        $datasource = new ArrayDatasource($this->getItems());
        $searchFilterType = new SearchFilterType();
        $request = new Request(['data' => $searchValue]);

        $formBuilder = $this->createMock(FormBuilderInterface::class);
        $formBuilder->method('getForm')->willReturn($this->createMock(FormInterface::class));
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('createNamedBuilder')->willReturn($formBuilder);
        $datalistFactory = new DatalistFactory($formFactory, $this->createMock(RouterInterface::class));
        $datalistFactory->registerFilterType($searchFilterType);
        $datalist = $datalistFactory->createBuilder(DatalistType::class)
            ->addFilter('data', SearchFilterType::class, [
                'search_fields' => $searchFields,
            ])
            ->getDatalist();
        $datalist->setDatasource($datasource);
        $datalist->bind($request);

        self::assertSame($expectedResult, array_values(iterator_to_array($datalist->getIterator())));
    }

    public function filterCasesProvider(): iterable
    {
        yield 'one_field_as_string' => ['[title]', 'The', [['title' => 'The Hobbit', 'tags' => 'Movie'], ['title' => 'The Good Doctor', 'tags' => 'Show']]];
        yield 'one_field_as_array' => [['[title]'], 'The', [['title' => 'The Hobbit', 'tags' => 'Movie'], ['title' => 'The Good Doctor', 'tags' => 'Show']]];
        yield 'multiple_fields' => [['[title]', '[tags]'], 'Good', [['title' => 'Black Panther', 'tags' => 'Movie, Good'], ['title' => 'The Good Doctor', 'tags' => 'Show']]];
    }

    private function getItems(): array
    {
        return [
            [
                'title' => 'The Hobbit',
                'tags'  => 'Movie',
            ],
            [
                'title' => 'Black Panther',
                'tags'  => 'Movie, Good',
            ],
            [
                'title' => 'The Good Doctor',
                'tags'  => 'Show',
            ],
        ];
    }
}
