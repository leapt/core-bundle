<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Twig\Extension\SiteExtension;
use PHPUnit\Framework\TestCase;

final class SiteExtensionTest extends TestCase
{
    private SiteExtension $siteExtension;

    protected function setUp(): void
    {
        $this->siteExtension = new SiteExtension();
    }

    /**
     * @dataProvider provideFalseTestCases
     */
    public function testFalseTest(mixed $testData, bool $expectedResult): void
    {
        self::assertSame($expectedResult, $this->siteExtension->getTests()[0]->getCallable()($testData));
    }

    public static function provideFalseTestCases(): iterable
    {
        yield ['test', false];
        yield ['', false];
        yield ['0', false];
        yield [0, false];
        yield [false, true];
    }

    public function testPageTitle(): void
    {
        $this->siteExtension->prependPageTitle('Demo');
        $this->siteExtension->appendPageTitle('Dashboard');
        $this->siteExtension->appendPageTitle('Home');
        self::assertSame('Demo - ACME Website - Dashboard - Home', $this->siteExtension->getPageTitle('ACME Website'));
    }

    public function testMetaDescription(): void
    {
        self::assertSame('Default description', $this->siteExtension->getMetaDescription('Default description'));
        $this->siteExtension->setMetaDescription('New description');
        self::assertSame('New description', $this->siteExtension->getMetaDescription('Default description'));
    }

    public function testMetaKeywords(): void
    {
        $this->siteExtension->addMetaKeywords(['some', 'keywords']);
        $this->siteExtension->addMetaKeywords(['other', 'keywords']);
        self::assertSame('default,keywords,some,other', $this->siteExtension->getMetaKeywords(['default', 'keywords']));
    }
}
