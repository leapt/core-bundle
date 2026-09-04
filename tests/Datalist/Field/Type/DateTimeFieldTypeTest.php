<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Field\Type\DateTimeFieldType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DateTimeFieldTypeTest extends AbstractDatalistFieldTypeTestCase
{
    public function testDefaultOptions(): void
    {
        $fieldType = new DateTimeFieldType();
        $resolver = new OptionsResolver();
        $fieldType->configureOptions($resolver);

        $options = $resolver->resolve();

        self::assertSame('d/m/Y', $options['format']);
        self::assertFalse($options['time_ago']);
    }

    public function testTimeAgoOptionCanBeEnabled(): void
    {
        $fieldType = new DateTimeFieldType();
        $resolver = new OptionsResolver();
        $fieldType->configureOptions($resolver);

        $options = $resolver->resolve(['time_ago' => true]);

        self::assertTrue($options['time_ago']);
    }

    public function testRendersFormattedDateByDefault(): void
    {
        $result = $this->renderDatalistField('publicationDate', DateTimeFieldType::class, [], ['publicationDate' => new \DateTime('2024-01-15')]);
        self::assertStringContainsString('15/01/2024', $result);
    }

    public function testRendersTimeAgoWhenOptionEnabled(): void
    {
        $result = $this->renderDatalistField('publicationDate', DateTimeFieldType::class, ['time_ago' => true], ['publicationDate' => new \DateTime('now')]);
        self::assertStringContainsString('timeago.justnow', $result);
    }
}
