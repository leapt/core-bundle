<?php

namespace Leapt\CoreBundle\Datalist;

class ViewContext implements \ArrayAccess
{
    /**
     * @var array
     */
    private $vars = [];

    /**
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->vars);
    }

    /**
     * @param mixed $offset
     */
    public function offsetGet($offset): mixed
    {
        return $this->vars[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->vars[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->vars[$offset]);
    }

    public function all(): array
    {
        return $this->vars;
    }
}
