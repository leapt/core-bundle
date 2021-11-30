<?php

namespace Leapt\CoreBundle\Datalist\Filter;

use Leapt\CoreBundle\Datalist\DatalistInterface;
use Leapt\CoreBundle\Datalist\Filter\Type\FilterTypeInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;

interface DatalistFilterInterface
{
    /**
     * @return FilterTypeInterface
     */
    public function getType(): TypeInterface;

    public function getDatalist(): DatalistInterface;

    public function getName(): string;

    public function getPropertyPath(): string;

    public function getOptions(): array;

    public function hasOption(string $name): bool;

    public function getOption(string $name, mixed $default = null): mixed;
}
