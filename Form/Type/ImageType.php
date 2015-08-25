<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * Class ImageType
 * @package Snowcap\CoreBundle\Form\Type
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
        return 'snowcap_core_image';
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return 'snowcap_core_file';
    }
}