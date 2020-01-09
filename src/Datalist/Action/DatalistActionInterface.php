<?php

namespace Leapt\CoreBundle\Datalist\Action;

use Leapt\CoreBundle\Datalist\DatalistInterface;

/**
 * Interface DatalistActionInterface.
 */
interface DatalistActionInterface
{
    /**
     * @return \Leapt\CoreBundle\Datalist\Action\Type\ActionTypeInterface
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
     * @param string $name
     *
     * @return bool
     */
    public function hasOption($name);

    /**
     * @param string $name
     * @param mixed  $default
     */
    public function getOption($name, $default = null);

    public function setDatalist(DatalistInterface $datalist);

    /**
     * @return DatalistInterface
     */
    public function getDatalist();
}
