<?php

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class ImageType extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix(): string
    {
        return 'leapt_core_image';
    }

    public function getParent(): ?string
    {
        return FileType::class;
    }
}
