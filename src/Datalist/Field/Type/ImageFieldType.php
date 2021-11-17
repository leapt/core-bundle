<?php

namespace Leapt\CoreBundle\Datalist\Field\Type;

class ImageFieldType extends AbstractFieldType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'image';
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return 'image';
    }
}
