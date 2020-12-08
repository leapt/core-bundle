<?php

namespace Leapt\CoreBundle\Datalist\Field;

use Leapt\CoreBundle\Datalist\DatalistInterface;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class DatalistField.
 */
class DatalistField implements DatalistFieldInterface
{
    /**
     * @var DatalistFieldConfig
     */
    private $config;

    /**
     * @var DatalistInterface
     */
    private $datalist;

    public function __construct(DatalistFieldConfig $config)
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
     *
     * @return bool
     */
    public function hasOption($name)
    {
        return $this->config->hasOption($name);
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return $this->config->getOption($name, $default);
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return mixed|void
     */
    public function setOption($name, $value)
    {
        $this->config->setOption($name, $value);
    }

    /**
     * @param mixed $row
     *
     * @return mixed
     *
     * @throws \UnexpectedValueException
     */
    public function getData($row)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $propertyPath = $this->getPropertyPath();
        try {
            $value = $accessor->getValue($row, $propertyPath);
        } catch (NoSuchPropertyException $e) {
            if (\is_object($row) && !$this->getDatalist()->hasOption('data_class')) {
                $message = 'Missing "data_class" option';
            } else {
                $message = sprintf('unknown property "%s"', $propertyPath);
            }
            throw new \UnexpectedValueException($message);
        } catch (UnexpectedTypeException $e) {
            $value = null;
        }

        if (null === $value && $this->hasOption('default')) {
            $value = $this->getOption('default');
        }

        return $value;
    }

    /**
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

    /**
     * @return string
     *
     * TODO: check if not better handled through options
     */
    private function getPropertyPath()
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
