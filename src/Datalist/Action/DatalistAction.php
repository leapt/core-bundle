<?php

namespace Leapt\CoreBundle\Datalist\Action;

use Leapt\CoreBundle\Datalist\DatalistInterface;

/**
 * Class DatalistAction
 * @package Leapt\CoreBundle\Datalist\Action
 */
class DatalistAction implements DatalistActionInterface
{
    /**
     * @var DatalistActionConfig
     */
    private $config;

    /**
     * @var DatalistInterface
     */
    private $datalist;

    /**
     * @param DatalistActionConfig $config
     */
    public function __construct(DatalistActionConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->config->getName();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->config->getOptions();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasOption($name)
    {
        return $this->config->hasOption($name);
    }

    /**
     * @param string $name
     * @param mixed $default
     */
    public function getOption($name, $default = null)
    {
        return $this->config->getOption($name, $default);
    }

    /**
     * @param \Leapt\CoreBundle\Datalist\DatalistInterface $datalist
     * @return mixed
     */
    public function setDatalist(DatalistInterface $datalist)
    {
        $this->datalist = $datalist;
    }

    /**
     * @return \Leapt\CoreBundle\Datalist\DatalistInterface
     */
    public function getDatalist()
    {
        return $this->datalist;
    }

    /**
     * @return \Leapt\CoreBundle\Datalist\Field\Type\FieldTypeInterface
     */
    public function getType()
    {
        return $this->config->getType();
    }
}