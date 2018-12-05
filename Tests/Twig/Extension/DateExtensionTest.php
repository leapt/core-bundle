<?php

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Tests\Twig\Extension\Mocks\TranslatorMock;
use Leapt\CoreBundle\Twig\Extension\DateExtension;
use PHPUnit\Framework\TestCase;

class DateExtensionTest extends TestCase
{
    /**
     * @var DateExtension
     */
    private $extension;

    /**
     * Sets a translator and the extension
     */
    public function setUp()
    {
        $translator = new TranslatorMock();
        $this->extension = new DateExtension($translator);
    }

    /**
     *  Test the GetFilters method
     */
    public function testGetFilters()
    {
        $filters = $this->extension->getFilters();
        $this->assertSame('time_ago', $filters[0]->getName());
    }

    /**
     * Test the TimeAgo filter
     *
     * @param \Datetime|string $ago      The time to test
     * @param string           $expected The expected string to assert
     *
     * @dataProvider timeAgoData
     */
    public function testTimeAgo($ago, $expected)
    {
        $this->assertEquals($expected, $this->extension->timeAgo($ago, 'en'));
    }

    /**
     * Data used to test the timeAgo method
     *
     * @return iterable
     */
    public function timeAgoData()
    {
        $twoYears = new \DateTime('-2 years');

        // Check with DateTime
        yield [$twoYears, 'timeago.yearsago|2|%years%=2'];
        yield [new \DateTime('now'), 'timeago.justnow'];
        yield [new \DateTime('-2 minutes'), 'timeago.minutesago|2|%minutes%=2'];
        yield [new \DateTime('-2 hours'), 'timeago.hoursago|2|%hours%=2'];
        yield [new \DateTime('-2 days'), 'timeago.daysago|2|%days%=2'];
        yield [new \DateTime('-2 months'), 'timeago.monthsago|2|%months%=2'];

        // Check with string
        yield [$twoYears->format('Y-m-d H:i:s'), 'timeago.yearsago|2|%years%=2'];
    }
}
