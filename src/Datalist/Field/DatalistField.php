<?php

namespace Leapt\CoreBundle\Datalist\Field;

use Leapt\CoreBundle\Datalist\DatalistInterface;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DatalistField implements DatalistFieldInterface
{
    private DatalistInterface $datalist;

    public function __construct(private DatalistFieldConfig $config)
    {
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

    /**
     * @return mixed
     */
    public function getOption(string $name, mixed $default = null)
    {
        return $this->config->getOption($name, $default);
    }

    /**
     * @return mixed|void
     */
    public function setOption(string $name, mixed $value)
    {
        $this->config->setOption($name, $value);
    }

    /**
     * @return mixed
     *
     * @throws \UnexpectedValueException
     */
    public function getData(mixed $row)
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
