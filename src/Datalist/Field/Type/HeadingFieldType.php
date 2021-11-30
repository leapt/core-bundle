<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Field\Type;

class HeadingFieldType extends TextFieldType
{
    public function getName(): string
    {
        return 'heading';
    }

    public function getBlockName(): string
    {
        return 'heading';
    }
}
