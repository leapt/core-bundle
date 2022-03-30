<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\FileStorage;

use Leapt\CoreBundle\Doctrine\Mapping\File;

final class FileStorageManager
{
    public function __construct(
        private FilesystemStorage $filesystemStorage,
        private FlysystemStorage $flysystemStorage,
    ) {
    }

    public function removeFile(File $fileMapping, string $file): void
    {
        if (null !== $fileMapping->flysystemConfig) {
            $this->flysystemStorage->removeFile($fileMapping, $file);
        } else {
            $this->filesystemStorage->removeFile($fileMapping, $file);
        }
    }
}
