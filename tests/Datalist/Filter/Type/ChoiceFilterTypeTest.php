<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\ArrayDatasource;
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
    public function testFilter(string|array|null $searchValue, bool $multiple, array $expectedResult): void
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
                'choices'  => ['Movie', 'TV Shows', 'Books'],
                'multiple' => $multiple,
            ])
            ->getDatalist();
        $datalist->setDatasource($datasource);
        $datalist->bind($request);

        self::assertSame($expectedResult, array_values(iterator_to_array($datalist->getIterator())));
    }

    public function filterCasesProvider(): iterable
    {
        yield 'empty_value' => ['', false, [['title' => 'The Hobbit', 'category' => 'Movies'], ['title' => 'Black Panther', 'category' => 'Movies'], ['title' => 'The Good Doctor', 'category' => 'TV Shows'], ['title' => 'Pawn of Prophecy', 'category' => 'Books']]];
        yield 'null_value' => [null, false, [['title' => 'The Hobbit', 'category' => 'Movies'], ['title' => 'Black Panther', 'category' => 'Movies'], ['title' => 'The Good Doctor', 'category' => 'TV Shows'], ['title' => 'Pawn of Prophecy', 'category' => 'Books']]];
        yield 'valid_value' => ['Movies', false, [['title' => 'The Hobbit', 'category' => 'Movies'], ['title' => 'Black Panther', 'category' => 'Movies']]];
        yield 'multiple' => [['TV Shows', 'Books'], true, [['title' => 'The Good Doctor', 'category' => 'TV Shows'], ['title' => 'Pawn of Prophecy', 'category' => 'Books']]];
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
