<?php

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * Class ImageType.
 */
class ImageType extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'leapt_core_image';
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return FileType::class;
    }
}
