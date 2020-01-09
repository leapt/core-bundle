<?php

namespace Leapt\CoreBundle\Datalist\Action\Type;

use Leapt\CoreBundle\Datalist\Action\DatalistActionInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;
use Leapt\CoreBundle\Datalist\ViewContext;

/**
 * Interface ActionTypeInterface.
 */
interface ActionTypeInterface extends TypeInterface
{
    /**
     * @param $item
     *
     * @return string
     */
    public function getUrl(DatalistActionInterface $action, $item, array $options = []);

    /**
     * @param $item
     */
    public function buildViewContext(ViewContext $viewContext, DatalistActionInterface $action, $item, array $options);
}
