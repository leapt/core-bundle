<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * This class is meant to signal a file deletion - used in forms (see Leapt\CoreBundle\Form\Type\FileType).
 */
class CondemnedFile extends UploadedFile
{
    private string $path;

    /**
     * Override parent constructor.
     */
    public function __construct()
    {
    }

    public function isValid(): bool
    {
        return true;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getPathName(): string
    {
        return $this->path;
    }
}
