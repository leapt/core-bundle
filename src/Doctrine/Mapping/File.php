<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Doctrine\Mapping;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class File
{
    public function __construct(
        public ?string $path = null,
        public ?string $pathCallback = null,
        public ?string $mappedBy = null,
        public ?string $filename = null,
        public ?string $nameCallback = null,
        public ?string $flysystemConfig = null,
    ) {
    }
}
