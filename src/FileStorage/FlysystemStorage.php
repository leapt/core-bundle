<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\FileStorage;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\File;

final class FlysystemStorage implements StorageInterface
{
    /**
     * @param array<FilesystemOperator> $storages
     */
    public function __construct(private array $storages)
    {
    }

    public function uploadFile(FileUploadConfig $fileUploadConfig, File $uploadedFile, string $path, string $filename): void
    {
        $this->getStorage($fileUploadConfig->attribute->flysystemConfig)->write($path . '/' . $filename, $uploadedFile->getContent());
    }

    public function removeFile(FileUploadConfig $fileUploadConfig, string $file): void
    {
        $this->getStorage($fileUploadConfig->attribute->flysystemConfig)->delete($file);
    }

    public function getStorage(string $name): FilesystemOperator
    {
        return $this->storages[$name];
    }
}
