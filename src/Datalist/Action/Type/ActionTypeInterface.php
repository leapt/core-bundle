<?php

namespace Leapt\CoreBundle\Datalist\Action\Type;

use Leapt\CoreBundle\Datalist\Action\DatalistActionInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;
use Leapt\CoreBundle\Datalist\ViewContext;

/**
 * Interface ActionTypeInterface
 * @package Leapt\CoreBundle\Datalist\Action\Type
 */
interface ActionTypeInterface extends TypeInterface
{
    /**
     * @param \Leapt\CoreBundle\Datalist\Action\DatalistActionInterface $action
     * @param $item
     * @param array $options
     * @return string
     */
    public function getUrl(DatalistActionInterface $action, $item, array $options = array());

    /**
     * @param \Leapt\CoreBundle\Datalist\ViewContext $viewContext
     * @param \Leapt\CoreBundle\Datalist\Action\DatalistActionInterface $action
     * @param $item
     * @param array $options
     */
    public function buildViewContext(ViewContext $viewContext, DatalistActionInterface $action, $item, array $options);
}