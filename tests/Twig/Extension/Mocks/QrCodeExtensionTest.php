<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Twig\Extension\QrCodeExtension;
use PHPUnit\Framework\TestCase;

final class QrCodeExtensionTest extends TestCase
{
    private QrCodeExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new QrCodeExtension();
    }

    public function testGetFunctions(): void
    {
        $functions = $this->extension->getFunctions();
        $this->assertSame('get_qr_code_from_string', $functions[0]->getName());
    }

    public function testGetQrCodeFromString(): void
    {
        self::assertStringStartsWith('data:image/png;base64,', $this->extension->getQrCodeFromString('test'));
    }
}
