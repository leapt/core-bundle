<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\FileStorage;

use Doctrine\ORM\Mapping\ClassMetadata;
use Leapt\CoreBundle\Doctrine\Mapping\File;

final class FileUploadConfig
{
    public function __construct(
        public \ReflectionProperty $property,
        public File $attribute,
        public ClassMetadata $classMetadata,
    ) {
    }
}
