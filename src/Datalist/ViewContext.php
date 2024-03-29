<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist;

class ViewContext implements \ArrayAccess
{
    private array $vars = [];

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->vars);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->vars[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->vars[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->vars[$offset]);
    }

    public function all(): array
    {
        return $this->vars;
    }
}
