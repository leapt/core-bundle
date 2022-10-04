<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\ArrayDatasource;
use Leapt\CoreBundle\Datalist\Filter\Type\BooleanFilterType;
use Leapt\CoreBundle\Datalist\Filter\Type\ChoiceFilterType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class ChoiceFilterTypeTest extends TestCase
{
    /**
     * @dataProvider filterCasesProvider
     */
    public function testFilter(?string $searchValue, array $expectedResult): void
    {
        $datasource = new ArrayDatasource($this->getItems());
        $choiceFilterType = new ChoiceFilterType();
        $request = new Request(['category' => $searchValue]);

        $formBuilder = $this->createMock(FormBuilderInterface::class);
        $formBuilder->method('getForm')->willReturn($this->createMock(FormInterface::class));
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('createNamedBuilder')->willReturn($formBuilder);
        $datalistFactory = new DatalistFactory($formFactory, $this->createMock(RouterInterface::class));
        $datalistFactory->registerFilterType($choiceFilterType);
        $datalist = $datalistFactory->createBuilder(DatalistType::class)
            ->addFilter('category', ChoiceFilterType::class, [
                'choices' => ['Movie', 'TV Shows'],
            ])
            ->getDatalist();
        $datalist->setDatasource($datasource);
        $datalist->bind($request);

        self::assertSame($expectedResult, array_values(iterator_to_array($datalist->getIterator())));
    }

    public function filterCasesProvider(): iterable
    {
        yield 'empty_value' => ['', [['title' => 'The Hobbit', 'category' => 'Movies'], ['title' => 'Black Panther', 'category' => 'Movies'], ['title' => 'The Good Doctor', 'category' => 'TV Shows']]];
        yield 'null_value' => [null, [['title' => 'The Hobbit', 'category' => 'Movies'], ['title' => 'Black Panther', 'category' => 'Movies'], ['title' => 'The Good Doctor', 'category' => 'TV Shows']]];
        yield 'valid_value' => ['Movies', [['title' => 'The Hobbit', 'category' => 'Movies'], ['title' => 'Black Panther', 'category' => 'Movies']]];
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
        ];
    }
}
