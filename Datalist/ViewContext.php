<?php

namespace Leapt\CoreBundle\Datalist;

/**
 * Class ViewContext
 * @package Leapt\CoreBundle\Datalist
 */
class ViewContext implements \ArrayAccess
{
    /**
     * @var array
     */
    private $vars = [];

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->vars);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
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

    /**
     * @return array
     */
    public function all()
    {
        return $this->vars;
    }
}