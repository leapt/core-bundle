<?php

namespace Leapt\CoreBundle\Doctrine\Mapping;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"PROPERTY"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class File
{
    public function __construct(
        public ?string $path = null,
        public ?string $pathCallback = null,
        public ?string $mappedBy = null,
        public ?string $filename = null,
        public ?string $nameCallback = null,
    ) {
    }
}
