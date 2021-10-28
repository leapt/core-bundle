<?php

namespace Leapt\CoreBundle\Datalist;

/**
 * Class Config.
 */
abstract class Config
{
    protected string $name;

    protected TypeInterface $type;

    protected array $options = [];

    /**
     * @param $name
     */
    public function __construct($name, TypeInterface $type, array $options = [])
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
     * @return bool
     */
    public function hasOption(string $name)
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

    /**
     * @param $value
     */
    public function setOption(string $name, $value)
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
