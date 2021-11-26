<?php

namespace Leapt\CoreBundle\Datalist\Field;

use Leapt\CoreBundle\Datalist\DatalistInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;

/**
 * Interface DatalistFieldInterface.
 */
interface DatalistFieldInterface
{
    /**
     * @return \Leapt\CoreBundle\Datalist\Field\Type\FieldTypeInterface
     */
    public function getType(): TypeInterface;

    public function getName(): string;

    public function getOptions(): array;

    public function hasOption(string $name): bool;

    public function getOption(string $name, mixed $default = null);

    public function setOption(string $name, mixed $value);

    public function getData(mixed $row): mixed;

    public function setDatalist(DatalistInterface $datalist);

    public function getDatalist(): DatalistInterface;
}
