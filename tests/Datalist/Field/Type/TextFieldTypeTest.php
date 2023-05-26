<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Datalist;
use Leapt\CoreBundle\Datalist\DatalistConfig;
use Leapt\CoreBundle\Datalist\Field\DatalistField;
use Leapt\CoreBundle\Datalist\Field\DatalistFieldConfig;
use Leapt\CoreBundle\Datalist\Field\Type\TextFieldType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use Leapt\CoreBundle\Datalist\ViewContext;
use PHPUnit\Framework\TestCase;

final class TextFieldTypeTest extends TestCase
{
    /**
     * @dataProvider buildViewContextProvider
     */
    public function testBuildViewContext(string $expectedValue, array $item, array $options = []): void
    {
        $fieldType = new TextFieldType();
        $viewContext = new ViewContext();
        $datalist = new Datalist(new DatalistConfig('base', new DatalistType()));
        $field = new DatalistField(new DatalistFieldConfig('name', $fieldType));
        $field->setDatalist($datalist);

        $fieldType->buildViewContext($viewContext, $field, $item, $options);
        self::assertSame($expectedValue, $viewContext['value']);
    }

    public static function buildViewContextProvider(): iterable
    {
        yield 'regular_text' => ['test', ['name' => 'test']];
        yield 'callback' => ['Here is the weight: 123', ['weight' => 123], ['callback' => fn (array $item): string => 'Here is the weight: ' . $item['weight']]];
    }
}
