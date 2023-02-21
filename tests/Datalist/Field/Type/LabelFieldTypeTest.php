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
        $mappings = [
            'Draft'    => [
                'label'      => 'Essai',
            ],
            'Published' => [
                'label' => 'Publier',
            ],
        ];
        $mappingsBoolean = [
            0 => [
                'label'      => 'Essai',
            ],
            1 => [
                'label' => 'Publier',
            ],
        ];
        yield 'regulat_text' => ['Essai', ['status' => 'Draft'], ['mappings' => $mappings]];
        yield 'regulat_text2' => ['Publier', ['status' => 'Published'], ['mappings' => $mappings]];
        yield 'boolean'      => ['Publier', ['status' => true], ['mappings' => $mappingsBoolean]];
    }
}
