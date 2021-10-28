<?php

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

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPathName()
    {
        return $this->path;
    }
}
