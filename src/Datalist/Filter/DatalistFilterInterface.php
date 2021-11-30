<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Filter;

use Leapt\CoreBundle\Datalist\DatalistInterface;
use Leapt\CoreBundle\Datalist\Filter\Type\FilterTypeInterface;

interface DatalistFilterInterface
{
    public function getType(): FilterTypeInterface;

    public function getDatalist(): DatalistInterface;

    public function getName(): string;

    public function getPropertyPath(): string;

    public function getOptions(): array;

    public function hasOption(string $name): bool;

    public function getOption(string $name, mixed $default = null): mixed;
}
