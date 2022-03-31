<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\FileStorage;

use Symfony\Component\HttpFoundation\File\File;

interface StorageInterface
{
    public function uploadFile(FileUploadConfig $fileUploadConfig, File $uploadedFile, string $path, string $filename): void;

    public function removeFile(FileUploadConfig $fileUploadConfig, string $file): void;
}
