<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Util;

use Leapt\CoreBundle\Util\StringUtil;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StringUtilTest extends TestCase
{
    #[DataProvider('provideUcfirstCases')]
    public function testUcfirst(string $expected, string $string): void
    {
        self::assertSame($expected, StringUtil::ucfirst($string));
    }

    public static function provideUcfirstCases(): iterable
    {
        yield ['Hello world', 'hello world'];
        yield ['Hello world', 'Hello world'];
        yield ['École', 'école'];
        yield ['', ''];
        yield [' Hello', ' hello'];
    }

    #[DataProvider('provideLcfirstCases')]
    public function testLcfirst(string $expected, string $string): void
    {
        self::assertSame($expected, StringUtil::lcfirst($string));
    }

    public static function provideLcfirstCases(): iterable
    {
        yield ['hello world', 'Hello world'];
        yield ['hello world', 'hello world'];
        yield ['école', 'École'];
        yield ['', ''];
        yield [' hello', ' Hello'];
    }
}
