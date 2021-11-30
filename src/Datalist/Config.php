<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist;

abstract class Config
{
    public function __construct(
        protected string $name,
        protected TypeInterface $type,
        protected array $options = [],
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

    public function getOption(string $name, mixed $default = null): mixed
    {
        return $this->options[$name] ?? $default;
    }

    public function setOption(string $name, mixed $value): self
    {
        $this->options[$name] = $value;

        return $this;
    }

    public function getType(): TypeInterface
    {
        return $this->type;
    }
}
