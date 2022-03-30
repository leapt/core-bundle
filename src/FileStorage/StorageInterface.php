<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\FileStorage;

use Leapt\CoreBundle\Doctrine\Mapping\File;

interface StorageInterface
{
    public function removeFile(File $fileMapping, string $file): void;
}
