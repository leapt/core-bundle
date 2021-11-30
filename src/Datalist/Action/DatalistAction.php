<?php

namespace Leapt\CoreBundle\Datalist\Action;

use Leapt\CoreBundle\Datalist\DatalistInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;

class DatalistAction implements DatalistActionInterface
{
    private DatalistInterface $datalist;

    public function __construct(private DatalistActionConfig $config)
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

    /**
     * @param string $name
     */
    public function hasOption($name): bool
    {
        return $this->config->hasOption($name);
    }

    /**
     * @param string $name
     * @param mixed  $default
     */
    public function getOption($name, $default = null): ?string
    {
        return $this->config->getOption($name, $default);
    }

    /**
     * @return mixed
     */
    public function setDatalist(DatalistInterface $datalist): self
    {
        $this->datalist = $datalist;

        return $this;
    }

    public function getDatalist(): DatalistInterface
    {
        return $this->datalist;
    }

    public function getType(): TypeInterface
    {
        return $this->config->getType();
    }
}
