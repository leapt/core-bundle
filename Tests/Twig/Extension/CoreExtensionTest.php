<?php

namespace Snowcap\CoreBundle\Tests\Twig\Extension;

use Snowcap\CoreBundle\Twig\Extension\CoreExtension;

class CoreExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CoreExtension
     */
    private $extension;

    public function setUp()
    {
        $this->extension = new CoreExtension();
    }

    /**
     * Test isActivePath method
     */
    public function testIsActivePath()
    {
        $fakeRequest = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $fakeRequest->expects($this->any())->method('getRequestUri')->will($this->returnValue('some/request/uri'));

        $fakeContainer = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $fakeContainer->expects($this->any())->method('get')->with($this->equalTo('request'))->will($this->returnValue($fakeRequest));

        $this->extension->setContainer($fakeContainer);

        $this->extension->setActivePaths(array('some/specific/path'));
        $this->assertTrue($this->extension->isActivePath('some/specific/path'));
        $this->assertTrue($this->extension->isActivePath('some/request/uri'));
    }

    /**
     * Test safeTruncate method with MultiByte string
     */
    public function testSafeTruncateWithMultiByteString()
    {
        $this->assertSafeTruncate();
    }

    /**
     * Test safeTruncate method without MultiByte string
     */
    public function testSafeTruncateWithoutMultiByteString()
    {
        $this->extension->setMultiByteString(false);
        $this->assertSafeTruncate();
        $this->extension->setMultiByteString(true);
    }

    /**
     * Assertions for safeTruncate tests
     */
    private function assertSafeTruncate()
    {
        $separator = '...';

        $env = $this->getMock('\Twig_Environment');
        $env->expects($this->any())->method('getCharset')->will($this->returnValue('utf8'));

        // Simple text
        $test = 'Lorem ipsum dolor sit amet';
        $this->assertEquals('Lorem' . $separator, $this->extension->safeTruncate($env, $test, 10, true, $separator), 'safeTruncate: Should trim after first word and add separator');
        $this->assertEquals('Lorem', $this->extension->safeTruncate($env, $test, 10, true, ''), 'safeTruncate: Should trim after first word without separator');
        $this->assertEquals('Lorem ipsum' . $separator, $this->extension->safeTruncate($env, $test, 16, true, $separator), 'safeTruncate: Should trim after second word and add separator');
        $this->assertEquals('Lorem ipsum', $this->extension->safeTruncate($env, $test, 16, true, ''), 'safeTruncate: Should trim after second word without separator');

        // Text with html tags
        $test = 'Lorem <strong class="super" style="display: none;">ipsum dolor sit</strong> amet';
        $this->assertEquals('Lorem <strong class="super" style="display: none;">ipsum</strong>' . $separator, $this->extension->safeTruncate($env, $test, 16, true, $separator), 'safeTruncate: Should trim after second word and add separator');
        $this->assertEquals('Lorem <strong class="super" style="display: none;">ipsum dolo</strong>' . $separator, $this->extension->safeTruncate($env, $test, 16, false, $separator), 'safeTruncate: Should trim after 16 chars with separator');
        $this->assertEquals('Lorem', $this->extension->safeTruncate($env, $test, 8, true, ''), 'safeTruncate: Should trim after first word without separator');
        $this->assertEquals($separator, $this->extension->safeTruncate($env, $test, 4, true, $separator), 'safeTruncate: Should only display separator');
        $this->assertEquals('Lore', $this->extension->safeTruncate($env, $test, 4, false, ''), 'safeTruncate: Should trim after 4 chars without separator');
        $this->assertEquals('Lore' . $separator, $this->extension->safeTruncate($env, $test, 4, false, $separator), 'safeTruncate: Should trim after 4 chars with separator');
        $this->assertEquals($test, $this->extension->safeTruncate($env, $test, 10000, true, $separator), 'safeTruncate: Should not trim and add no separator');
        $this->assertEquals($test, $this->extension->safeTruncate($env, $test, 10000, true, ''), 'safeTruncate: Should not trim and add no separator');

        // Special case with unclosed tag
        $test = '<div>Lorem <strong>ipsum</strong> dolor sit amet</div>';
        $this->assertEquals('<div>Lorem <strong>ipsum</strong></div>', $this->extension->safeTruncate($env, $test, 11, true, ''), 'safeTruncate: Should close tags');

        // Testing the condition where there is no extra space after the defined length
        $test = 'Lorem Ipsum DolorSitAmet';
        $this->assertEquals('Lorem Ipsum' . $separator, $this->extension->safeTruncate($env, $test, 15, $separator), 'safeTruncate= Should trim after second word with separator');

    }
}