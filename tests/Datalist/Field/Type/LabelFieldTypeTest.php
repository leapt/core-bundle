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
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

final class LabelFieldTypeTest extends AbstractDatalistFieldTypeTestCase
{
    #[DataProvider('buildViewContextProvider')]
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

    public static function buildViewContextProvider(): iterable
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

    public function testChoiceTranslationDomainDefaultsToNull(): void
    {
        $fieldType = new LabelFieldType();
        $resolver = new OptionsResolver();
        $fieldType->configureOptions($resolver);

        $options = $resolver->resolve(['mappings' => []]);

        self::assertNull($options['choice_translation_domain']);
    }

    #[DataProvider('provideValidChoiceTranslationDomainValues')]
    public function testChoiceTranslationDomainAcceptsValidTypes(mixed $value): void
    {
        $fieldType = new LabelFieldType();
        $resolver = new OptionsResolver();
        $fieldType->configureOptions($resolver);

        $options = $resolver->resolve(['mappings' => [], 'choice_translation_domain' => $value]);

        self::assertSame($value, $options['choice_translation_domain']);
    }

    public static function provideValidChoiceTranslationDomainValues(): iterable
    {
        yield 'null' => [null];
        yield 'false' => [false];
        yield 'true' => [true];
        yield 'string' => ['custom_domain'];
    }

    public function testChoiceTranslationDomainRejectsInvalidType(): void
    {
        $fieldType = new LabelFieldType();
        $resolver = new OptionsResolver();
        $fieldType->configureOptions($resolver);

        $this->expectException(InvalidOptionsException::class);

        $resolver->resolve(['mappings' => [], 'choice_translation_domain' => 42]);
    }

    public function testRendersWithDatalistTranslationDomainByDefault(): void
    {
        $result = $this->renderLabelField(['mappings' => ['draft' => ['label' => 'Draft status']]]);
        self::assertStringContainsString('Draft status[messages]', $result);
    }

    public function testRendersUntranslatedWhenChoiceTranslationDomainIsFalse(): void
    {
        $result = $this->renderLabelField([
            'mappings' => ['draft' => ['label' => 'Draft status']],
            'choice_translation_domain' => false,
        ]);
        self::assertStringContainsString('Draft status', $result);
        self::assertStringNotContainsString('Draft status[', $result);
    }

    public function testRendersWithCustomTranslationDomainWhenSet(): void
    {
        $result = $this->renderLabelField([
            'mappings' => ['draft' => ['label' => 'Draft status']],
            'choice_translation_domain' => 'custom_domain',
        ]);
        self::assertStringContainsString('Draft status[custom_domain]', $result);
    }

    private function renderLabelField(array $options): string
    {
        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturnCallback(
            static fn(string $id, array $parameters = [], ?string $domain = null): string => \sprintf('%s[%s]', $id, $domain ?? 'null'),
        );

        return $this->renderDatalistField('status', LabelFieldType::class, $options, ['status' => 'draft'], $translator);
    }
}
