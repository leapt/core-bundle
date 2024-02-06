<?php

declare(strict_types=1);

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
     * @param UploadedFile $value
     */
    public function transform(mixed $value): array
    {
        return ['file' => $value, 'delete' => false];
    }

    /**
     * @param array $value
     */
    public function reverseTransform(mixed $value): CondemnedFile|UploadedFile|null
    {
        if (true === $value['delete']) {
            return new CondemnedFile();
        }

        return $value['file'];
    }
}
