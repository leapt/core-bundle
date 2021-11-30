<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class ImageType extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return 'leapt_core_image';
    }

    public function getParent(): string
    {
        return FileType::class;
    }
}
