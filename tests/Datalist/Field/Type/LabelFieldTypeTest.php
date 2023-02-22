<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Datalist;
use Leapt\CoreBundle\Datalist\DatalistConfig;
use Leapt\CoreBundle\Datalist\Field\DatalistField;
use Leapt\CoreBundle\Datalist\Field\DatalistFieldConfig;
use Leapt\CoreBundle\Datalist\Field\Type\LabelFieldType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LabelFieldTypeTest extends WebTestCase
{
    /**
     * @dataProvider buildViewContextProvider
     */
    public function testBuildViewContext(string $expectedValue, array $item, array $options = []): void
    {
        $fieldType = new LabelFieldType();
        $viewContext = new ViewContext();
        $datalist = new Datalist(new DatalistConfig('base', new DatalistType()));
        $field = new DatalistField(new DatalistFieldConfig('status', $fieldType));
        $field->setDatalist($datalist);

        $fieldType->buildViewContext($viewContext, $field, $item, $options);
        self::assertSame($expectedValue, $viewContext['value']);
    }

    public function buildViewContextProvider(): iterable
    {
        $stringMappings = [
            'Draft' => [
                'label' => 'Draft status',
            ],
            'Published' => [
                'label' => 'Published status',
            ],
        ];
        $booleanMappings = [
            0 => [
                'label' => 'Falsy',
            ],
            1 => [
                'label' => 'Truthy',
            ],
        ];
        yield 'regular_text' => ['Draft status', ['status' => 'Draft'], ['mappings' => $stringMappings]];
        yield 'regular_text2' => ['Published status', ['status' => 'Published'], ['mappings' => $stringMappings]];
        yield 'boolean_true' => ['Truthy', ['status' => true], ['mappings' => $booleanMappings]];
        yield 'boolean_false' => ['Falsy', ['status' => false], ['mappings' => $booleanMappings]];
    }
}
