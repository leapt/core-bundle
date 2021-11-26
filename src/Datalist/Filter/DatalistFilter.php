<?php

namespace Leapt\CoreBundle\Datalist\Filter;

use Leapt\CoreBundle\Datalist\DatalistInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;

class DatalistFilter implements DatalistFilterInterface
{
    private DatalistInterface $datalist;

    public function __construct(private DatalistFilterConfig $config)
    {
    }

    public function getName(): string
    {
        return $this->config->getName();
    }

    public function getOptions(): array
    {
        return $this->config->getOptions();
    }

    public function hasOption(string $name): bool
    {
        return $this->config->hasOption($name);
    }

    public function getOption(string $name, mixed $default = null)
    {
        return $this->config->getOption($name, $default);
    }

    public function getType(): TypeInterface
    {
        return $this->config->getType();
    }

    public function setDatalist(DatalistInterface $datalist)
    {
        $this->datalist = $datalist;
    }

    public function getDatalist(): DatalistInterface
    {
        return $this->datalist;
    }

    public function getPropertyPath(): string
    {
        $propertyPath = $this->getOption('property_path');
        if (null === $propertyPath) {
            $propertyPath = $this->config->getName();
            if (null === $this->datalist->getOption('data_class')) {
                $propertyPath = '[' . $propertyPath . ']';
            }
        }

        return $propertyPath;
    }
}
