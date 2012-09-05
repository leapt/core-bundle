<?php

namespace Snowcap\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Snowcap\CoreBundle\File\CondemnedFile;

/**
 * This class is used to transform data for the Snowcap\CoreBundle\Form\Type\FileType form type
 *
 */
class FileDataTransformer implements DataTransformerInterface {
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $value
     * @return array
     */
    function transform($value)
    {
        return array('file' => $value, 'delete' => false);
    }

    /**
     * @param array $value
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    function reverseTransform($value)
    {
        if($value['delete'] === true) {
            return new CondemnedFile();
        }
        return $value['file'];
    }
}