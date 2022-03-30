<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Tests\Twig\Extension\Mocks\TranslatorMock;
use Leapt\CoreBundle\Twig\Extension\DateExtension;
use PHPUnit\Framework\TestCase;

class DateExtensionTest extends TestCase
{
    private DateExtension $extension;

    protected function setUp(): void
    {
        $translator = new TranslatorMock();
        $this->extension = new DateExtension($translator);
    }

    public function testGetFilters(): void
    {
        $filters = $this->extension->getFilters();
        $this->assertSame('time_ago', $filters[0]->getName());
    }

    /**
     * @dataProvider timeAgoData
     */
    public function testTimeAgo(\DateTime|string $ago, string $expected): void
    {
        $this->assertEquals($expected, $this->extension->timeAgo($ago, 'en'));
    }

    public function timeAgoData(): iterable
    {
        $twoYears = new \DateTime('-2 years');

        // Check with DateTime
        yield [$twoYears, 'timeago.yearsago|%years%=2|%count%=2'];
        yield [new \DateTime('now'), 'timeago.justnow'];
        yield [new \DateTime('-2 minutes'), 'timeago.minutesago|%minutes%=2|%count%=2'];
        yield [new \DateTime('-2 hours'), 'timeago.hoursago|%hours%=2|%count%=2'];
        yield [new \DateTime('-2 days'), 'timeago.daysago|%days%=2|%count%=2'];
        yield [new \DateTime('-2 months'), 'timeago.monthsago|%months%=2|%count%=2'];

        // Check with string
        yield [$twoYears->format('Y-m-d H:i:s'), 'timeago.yearsago|%years%=2|%count%=2'];
    }
}
