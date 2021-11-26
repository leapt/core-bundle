<?php

namespace Leapt\CoreBundle\Datalist;

abstract class Config
{
    public function __construct(
        protected string $name,
        protected TypeInterface $type,
        protected array $options = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function hasOption(string $name): bool
    {
        return isset($this->options[$name]);
    }

    /**
     * @param $name
     * @param null $default
     *
     * @return null
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    public function setOption(string $name, mixed $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * @return \Leapt\CoreBundle\Datalist\TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }
}
