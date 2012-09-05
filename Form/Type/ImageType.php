<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class ImageType extends AbstractType {
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
     * @param array $options
     * @return null|string
     */
    public function getParent()
    {
        return 'snowcap_core_file';
    }
}