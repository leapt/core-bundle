<?php

namespace Leapt\CoreBundle\Datalist\Filter;

use Leapt\CoreBundle\Datalist\TypeInterface;

interface DatalistFilterInterface
{
    /**
     * @return \Leapt\CoreBundle\Datalist\Filter\Type\FilterTypeInterface
     */
    public function getType(): TypeInterface;

    public function getDatalist(): \Leapt\CoreBundle\Datalist\DatalistInterface;

    public function getName(): string;

    public function getPropertyPath(): string;

    public function getOptions(): array;

    public function hasOption(string $name): bool;

    public function getOption(string $name, mixed $default = null);
}
