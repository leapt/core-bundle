<?php

namespace Leapt\CoreBundle\Form\DataTransformer;

use Leapt\CoreBundle\File\CondemnedFile;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * This class is used to transform data for the Leapt\CoreBundle\Form\Type\FileType form type.
 */
class FileDataTransformer implements DataTransformerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $value
     */
    public function transform($value): array
    {
        return ['file' => $value, 'delete' => false];
    }

    /**
     * @param array $value
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function reverseTransform($value): CondemnedFile|UploadedFile
    {
        if (true === $value['delete']) {
            return new CondemnedFile();
        }

        return $value['file'];
    }
}
