<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;
use Leapt\CoreBundle\Datalist\ViewContext;

interface FieldTypeInterface extends TypeInterface
{
    public function buildViewContext(ViewContext $viewContext, DatalistFieldInterface $field, mixed $value, array $options);
}
