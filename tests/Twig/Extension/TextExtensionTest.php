<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Tests\Twig\Extension\Mocks\TextExtensionMock;
use Leapt\CoreBundle\Twig\Extension\TextExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class TextExtensionTest extends TestCase
{
    /**
     * @var TextExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $this->extension = new TextExtension();
    }

    /**
     * Test that the constructor correctly set the default MultiByte string.
     */
    public function testConstruct()
    {
        $this->assertSame($this->extension->isMultiByteStringAvailable(), $this->extension->getMultiByteString(), '__construct: Check that MultiByte string is correctly set');
    }

    public function testGetFilters(): void
    {
        $filters = $this->extension->getFilters();
        $this->assertSame('camelize', $filters[0]->getName());
        $this->assertSame('safe_truncate', $filters[1]->getName());
    }

    public function testSafeTruncate(): void
    {
        // Test SafeTruncate without MultiByte string
        $this->extension->setMultiByteString(false);
        $this->assertSafeTruncate();

        // Test SafeTruncate with MultiByte string if available
        if (!$this->extension->isMultiByteStringAvailable()) {
            $this->markTestSkipped('mb_string is not available.');
        }
        $this->assertSafeTruncate();
    }

    public function testSetMultiByteString(): void
    {
        $this->extension->setMultiByteString(true);

        $this->assertTrue($this->extension->getMultiByteString());
    }

    public function testSetMultiByteStringException(): void
    {
        $this->expectException(\BadFunctionCallException::class);

        $extension = new TextExtensionMock();

        $extension->setMultiByteString(true);
    }

    /**
     * Test that isMultiByteStringAvailable method returns the same as function_exists('mb_get_info').
     */
    public function testIsMultiByteStringAvailable(): void
    {
        $this->assertSame(\function_exists('mb_get_info'), $this->extension->isMultiByteStringAvailable());
    }

    private function assertSafeTruncate(): void
    {
        $separator = '...';

        $env = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $env->expects($this->any())->method('getCharset')->willReturn('utf8');

        // Simple text
        $test = 'Lorem ipsum dolor sit amet';
        $this->assertEquals('Lorem' . $separator, $this->extension->safeTruncate($env, $test, 10, true, $separator), 'safeTruncate: Should trim after first word and add separator');
        $this->assertEquals('Lorem', $this->extension->safeTruncate($env, $test, 10, true, ''), 'safeTruncate: Should trim after first word without separator');
        $this->assertEquals('Lorem ipsum' . $separator, $this->extension->safeTruncate($env, $test, 16, true, $separator), 'safeTruncate: Should trim after second word and add separator');
        $this->assertEquals('Lorem ipsum', $this->extension->safeTruncate($env, $test, 16, true, ''), 'safeTruncate: Should trim after second word without separator');

        // Text with html tags
        $test = 'Lorem <strong class="super" style="display: none;">ipsum dolor sit</strong> amet';
        $this->assertEquals('Lorem <strong class="super" style="display: none;">ipsum' . $separator . '</strong>', $this->extension->safeTruncate($env, $test, 16, true, $separator), 'safeTruncate: Should trim after second word and add separator');
        $this->assertEquals('Lorem <strong class="super" style="display: none;">ipsum dolo' . $separator . '</strong>', $this->extension->safeTruncate($env, $test, 16, false, $separator), 'safeTruncate: Should trim after 16 chars with separator');
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
        $this->assertEquals('Lorem Ipsum' . $separator, $this->extension->safeTruncate($env, $test, 15, true, $separator), 'safeTruncate= Should trim after second word with separator');
    }
}
