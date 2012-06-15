<?php

namespace Snowcap\CoreBundle\Tests\Twig\Extension\Mocks;

use Snowcap\CoreBundle\Twig\Extension\TextExtension;

class TextExtensionMock extends TextExtension
{
    public function isMultiByteStringAvailable()
    {
        return false;
    }
}

