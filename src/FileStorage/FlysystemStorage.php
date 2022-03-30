<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\FileStorage;

use League\Flysystem\FilesystemOperator;
use Leapt\CoreBundle\Doctrine\Mapping\File;

final class FlysystemStorage implements StorageInterface
{
    /**
     * @param array<FilesystemOperator> $storages
     */
    public function __construct(private array $storages)
    {
    }

    public function removeFile(File $fileMapping, string $file): void
    {
        $this->getStorage($fileMapping->flysystemConfig)->delete($file);
    }

    public function getStorage(string $name): FilesystemOperator
    {
        return $this->storages[$name];
    }
}
