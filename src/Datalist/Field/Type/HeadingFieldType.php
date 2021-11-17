<?php

namespace Leapt\CoreBundle\Datalist\Field\Type;

class HeadingFieldType extends TextFieldType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'heading';
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return 'heading';
    }
}
