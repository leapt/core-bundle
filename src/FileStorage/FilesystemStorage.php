<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\FileStorage;

use Leapt\CoreBundle\Doctrine\Mapping\File;

final class FilesystemStorage implements StorageInterface
{
    public function removeFile(File $fileMapping, string $file): void
    {
        if (is_file($file)) {
            unlink($file);
        }
    }
}
