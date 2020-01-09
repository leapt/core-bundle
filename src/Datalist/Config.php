<?php

namespace Leapt\CoreBundle\Datalist;

/**
 * Class Config
 * @package Leapt\CoreBundle\Datalist
 */
abstract class Config
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var TypeInterface
     */
    protected $type;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param $name
     * @param TypeInterface $type
     * @param array $options
     */
    public function __construct($name, TypeInterface $type, array $options = array())
    {
        $this->name = $name;
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasOption($name)
    {
        return isset($this->options[$name]);
    }

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * @param string $name
     * @param $value
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * @return \Leapt\CoreBundle\Datalist\TypeInterface
     */
    public function getType()
    {
        return $this->type;
    }
}