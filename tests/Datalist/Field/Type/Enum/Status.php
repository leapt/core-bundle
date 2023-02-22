<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Field\Type\Enum;

enum Status: string
{
    case Draft = 'Draft';
    case Published = 'Published';
}
