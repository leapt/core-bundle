<?php

namespace Snowcap\CoreBundle\Tests\Twig\Extension;

use Snowcap\CoreBundle\Twig\Extension\DateExtension;
use Snowcap\CoreBundle\Tests\Twig\Extension\Mocks\TranslatorMock;

class DateExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var DateExtension
     */
    private $extension;

    public function setUp()
    {
        $translator = new TranslatorMock();
        $this->extension = new DateExtension($translator);
    }


    /**
     * Test timeAgo method
     */
    public function testTimeAgo()
    {
        $locale = 'en';
        // Check with Datetime
        $ago = new \DateTime('-2 years');
        $this->assertEquals('timeago.yearsago|2|%years%=2', $this->extension->timeAgo($ago, $locale));
        $ago = new \DateTime('now');
        $this->assertEquals('timeago.justnow', $this->extension->timeAgo($ago, $locale));
        $ago = new \DateTime('-2 minutes');
        $this->assertEquals('timeago.minutesago|2|%minutes%=2', $this->extension->timeAgo($ago, $locale));
        $ago = new \DateTime('-2 hours');
        $this->assertEquals('timeago.hoursago|2|%hours%=2', $this->extension->timeAgo($ago, $locale));
        $ago = new \DateTime('-2 days');
        $this->assertEquals('timeago.daysago|2|%days%=2', $this->extension->timeAgo($ago, $locale));
        $ago = new \DateTime('-2 months');
        $this->assertEquals('timeago.monthsago|2|%months%=2', $this->extension->timeAgo($ago, $locale));

        // Check with string
        $ago = new \DateTime('-2 years');
        $this->assertEquals('timeago.yearsago|2|%years%=2', $this->extension->timeAgo($ago->format('Y-m-d H:i:s'), $locale));

    }
}