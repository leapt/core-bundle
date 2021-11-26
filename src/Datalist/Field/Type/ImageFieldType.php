<?php

namespace Leapt\CoreBundle\Datalist\Field\Type;

class ImageFieldType extends AbstractFieldType
{
    public function getName(): string
    {
        return 'image';
    }

    public function getBlockName(): string
    {
        return 'image';
    }
}
