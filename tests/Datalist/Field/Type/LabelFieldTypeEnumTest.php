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
use Leapt\CoreBundle\Tests\Datalist\Field\Type\Enum\Status;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @requires PHP 8.1
 */
final class LabelFieldTypeEnumTest extends WebTestCase
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
            Status::Draft->value     => [
                'label'      => 'Essai',
            ],
            Status::Published->value => [
                'label' => 'Publier',
            ],
        ];
        yield 'enum'         => ['Publier', ['status' => Status::Published], ['mappings' => $mappings]];
        yield 'enum2' => ['Essai', ['status' => Status::Draft], ['mappings' => $mappings]];
    }
}
