<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Type;

use Leapt\CoreBundle\Datalist\DatalistBuilder;
use Leapt\CoreBundle\Datalist\DatalistInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;
use Leapt\CoreBundle\Datalist\ViewContext;

interface DatalistTypeInterface extends TypeInterface
{
    public function buildDatalist(DatalistBuilder $builder, array $options): void;

    public function buildViewContext(ViewContext $viewContext, DatalistInterface $datalist, array $options);
}
