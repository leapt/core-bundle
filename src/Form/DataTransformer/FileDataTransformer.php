<?php

namespace Leapt\CoreBundle\Form\DataTransformer;

use Leapt\CoreBundle\File\CondemnedFile;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * This class is used to transform data for the Leapt\CoreBundle\Form\Type\FileType form type.
 */
class FileDataTransformer implements DataTransformerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $value
     *
     * @return array
     */
    public function transform($value)
    {
        return ['file' => $value, 'delete' => false];
    }

    /**
     * @param array $value
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function reverseTransform($value)
    {
        if (true === $value['delete']) {
            return new CondemnedFile();
        }

        return $value['file'];
    }
}
