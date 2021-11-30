<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Type;

class DatalistType extends AbstractDatalistType
{
    public function getName(): string
    {
        return 'datalist';
    }

    public function getBlockName(): string
    {
        return 'datalist';
    }
}
