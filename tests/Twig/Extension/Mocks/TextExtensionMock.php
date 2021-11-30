<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\Extension\Mocks;

use Leapt\CoreBundle\Twig\Extension\TextExtension;

class TextExtensionMock extends TextExtension
{
    public function isMultiByteStringAvailable(): bool
    {
        return false;
    }
}
