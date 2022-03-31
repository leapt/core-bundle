<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\FileStorage;

use Symfony\Component\HttpFoundation\File\File;

final class FilesystemStorage implements StorageInterface
{
    public function __construct(private string $uploadDir)
    {
    }

    public function uploadFile(FileUploadConfig $fileUploadConfig, File $uploadedFile, string $path, string $filename): void
    {
        $uploadedFile->move($this->uploadDir . '/' . $path, $filename);
    }

    public function removeFile(FileUploadConfig $fileUploadConfig, string $file): void
    {
        $fullFilePath = $this->uploadDir . '/' . $file;

        if (is_file($fullFilePath)) {
            unlink($fullFilePath);
        }
    }
}
