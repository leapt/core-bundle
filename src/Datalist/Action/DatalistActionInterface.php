<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Action;

use Leapt\CoreBundle\Datalist\DatalistInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;

interface DatalistActionInterface
{
    public function getType(): TypeInterface;

    public function getName(): string;

    public function getOptions(): array;

    public function hasOption(string $name): bool;

    public function getOption(string $name, mixed $default = null): mixed;

    public function setDatalist(DatalistInterface $datalist);

    public function getDatalist(): DatalistInterface;
}
