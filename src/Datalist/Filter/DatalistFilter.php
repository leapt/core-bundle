<?php

namespace Leapt\CoreBundle\Datalist\Filter;

use Leapt\CoreBundle\Datalist\DatalistInterface;

class DatalistFilter implements DatalistFilterInterface
{
    private DatalistFilterConfig $config;

    /**
     * @var
     */
    private DatalistInterface $datalist;

    public function __construct(DatalistFilterConfig $config)
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
     * @return bool
     */
    public function hasOption(string $name)
    {
        return $this->config->hasOption($name);
    }

    public function getOption(string $name, mixed $default = null)
    {
        return $this->config->getOption($name, $default);
    }

    /**
     * @return \Leapt\CoreBundle\Datalist\Filter\Type\FilterTypeInterface
     */
    public function getType()
    {
        return $this->config->getType();
    }

    public function setDatalist(DatalistInterface $datalist)
    {
        $this->datalist = $datalist;
    }

    /**
     * @return DatalistInterface
     */
    public function getDatalist()
    {
        return $this->datalist;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        $propertyPath = $this->getOption('property_path');
        if (null === $propertyPath) {
            $propertyPath = $this->config->getName();
            if (null === $this->datalist->getOption('data_class')) {
                $propertyPath = '[' . $propertyPath . ']';
            }
        }

        return $propertyPath;
    }
}
