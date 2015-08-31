<?php

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * Class ImageType
 * @package Leapt\CoreBundle\Form\Type
 */
class ImageType extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    function getName()
    {
        return 'leapt_core_image';
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return 'leapt_core_file';
    }
}