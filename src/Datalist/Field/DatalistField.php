<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Field;

use Leapt\CoreBundle\Datalist\DatalistInterface;
use Leapt\CoreBundle\Datalist\Field\Type\FieldTypeInterface;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DatalistField implements DatalistFieldInterface
{
    private DatalistInterface $datalist;

    public function __construct(private DatalistFieldConfig $config)
    {
    }

    public function getName(): string
    {
        return $this->config->getName();
    }

    public function getOptions(): array
    {
        return $this->config->getOptions();
    }

    public function hasOption(string $name): bool
    {
        return $this->config->hasOption($name);
    }

    public function getOption(string $name, mixed $default = null): mixed
    {
        return $this->config->getOption($name, $default);
    }

    public function setOption(string $name, mixed $value): self
    {
        $this->config->setOption($name, $value);

        return $this;
    }

    public function getData(mixed $row): mixed
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

    public function setDatalist(DatalistInterface $datalist): void
    {
        $this->datalist = $datalist;
    }

    public function getDatalist(): DatalistInterface
    {
        return $this->datalist;
    }

    public function getType(): FieldTypeInterface
    {
        return $this->config->getType();
    }

    /**
     * TODO: check if not better handled through options.
     */
    private function getPropertyPath(): string
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
