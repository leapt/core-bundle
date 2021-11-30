<?php

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

    public function offsetSet(mixed $offset, mixed $value)
    {
        $this->vars[$offset] = $value;
    }

    public function offsetUnset(mixed $offset)
    {
        unset($this->vars[$offset]);
    }

    public function all(): array
    {
        return $this->vars;
    }
}
