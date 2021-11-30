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

    /**
     * @param string $name
     */
    public function hasOption($name): bool;

    /**
     * @param string $name
     * @param mixed  $default
     */
    public function getOption($name, $default = null);

    public function setDatalist(DatalistInterface $datalist);

    public function getDatalist(): DatalistInterface;
}
