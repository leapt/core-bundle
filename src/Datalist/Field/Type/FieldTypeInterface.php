<?php

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;
use Leapt\CoreBundle\Datalist\ViewContext;

/**
 * Interface FieldTypeInterface
 * @package Leapt\CoreBundle\Datalist\Field\Type
 */
interface FieldTypeInterface extends TypeInterface
{
    /**
     * @param \Leapt\CoreBundle\Datalist\ViewContext $viewContext
     * @param \Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface $field
     * @param mixed $value
     * @param array $options
     */
    public function buildViewContext(ViewContext $viewContext, DatalistFieldInterface $field, $value, array $options);
}