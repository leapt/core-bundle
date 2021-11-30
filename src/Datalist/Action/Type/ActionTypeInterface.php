<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Action\Type;

use Leapt\CoreBundle\Datalist\Action\DatalistActionInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;
use Leapt\CoreBundle\Datalist\ViewContext;

interface ActionTypeInterface extends TypeInterface
{
    public function getUrl(DatalistActionInterface $action, mixed $item, array $options = []): string;

    public function buildViewContext(ViewContext $viewContext, DatalistActionInterface $action, mixed $item, array $options): void;
}
