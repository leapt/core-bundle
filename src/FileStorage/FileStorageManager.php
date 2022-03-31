<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\FileStorage;

use Symfony\Component\HttpFoundation\File\File;

final class FileStorageManager
{
    public function __construct(
        private FilesystemStorage $filesystemStorage,
        private FlysystemStorage $flysystemStorage,
    ) {
    }

    public function uploadFile(FileUploadConfig $fileUploadConfig, File $uploadedFile, string $path, string $filename): void
    {
        if (null !== $fileUploadConfig->attribute->flysystemConfig) {
            $this->flysystemStorage->uploadFile($fileUploadConfig, $uploadedFile, $path, $filename);
        } else {
            $this->filesystemStorage->uploadFile($fileUploadConfig, $uploadedFile, $path, $filename);
        }
    }

    public function removeFile(FileUploadConfig $fileUploadConfig, string $file): void
    {
        if (null !== $fileUploadConfig->attribute->flysystemConfig) {
            $this->flysystemStorage->removeFile($fileUploadConfig, $file);
        } else {
            $this->filesystemStorage->removeFile($fileUploadConfig, $file);
        }
    }
}
