<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist;

use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Filter\Type\BooleanFilterType;
use Leapt\CoreBundle\Datalist\Filter\Type\ChoiceFilterType;
use Leapt\CoreBundle\Datalist\Filter\Type\EntityFilterType;
use Leapt\CoreBundle\Datalist\Filter\Type\EnumFilterType;
use Leapt\CoreBundle\Datalist\Filter\Type\SearchFilterType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;

final class DatalistFactoryTest extends TestCase
{
    public function testInitialize(): void
    {
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $router = $this->createMock(RouterInterface::class);
        $datalistFactory = new DatalistFactory($formFactory, $router);

        self::assertInstanceOf(BooleanFilterType::class, $datalistFactory->getFilterType(BooleanFilterType::class));
        self::assertInstanceOf(ChoiceFilterType::class, $datalistFactory->getFilterType(ChoiceFilterType::class));
        self::assertInstanceOf(EntityFilterType::class, $datalistFactory->getFilterType(EntityFilterType::class));
        self::assertInstanceOf(EnumFilterType::class, $datalistFactory->getFilterType(EnumFilterType::class));
        self::assertInstanceOf(SearchFilterType::class, $datalistFactory->getFilterType(SearchFilterType::class));
    }
}
