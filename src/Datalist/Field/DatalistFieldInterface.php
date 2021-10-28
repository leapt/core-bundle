<?php

namespace Leapt\CoreBundle\Datalist\Field;

use Leapt\CoreBundle\Datalist\DatalistInterface;

/**
 * Interface DatalistFieldInterface.
 */
interface DatalistFieldInterface
{
    /**
     * @return \Leapt\CoreBundle\Datalist\Field\Type\FieldTypeInterface
     */
    public function getType();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @return bool
     */
    public function hasOption(string $name);

    public function getOption(string $name, mixed $default = null);

    public function setOption(string $name, mixed $value);

    /**
     * @return mixed
     */
    public function getData(mixed $row);

    public function setDatalist(DatalistInterface $datalist);

    /**
     * @return DatalistInterface
     */
    public function getDatalist();
}
