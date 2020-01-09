<?php

namespace Leapt\CoreBundle\Datalist\Type;

use Leapt\CoreBundle\Datalist\DatalistBuilder;
use Leapt\CoreBundle\Datalist\DatalistInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;
use Leapt\CoreBundle\Datalist\ViewContext;

/**
 * Interface DatalistTypeInterface
 * @package Leapt\CoreBundle\Datalist\Type
 */
interface DatalistTypeInterface extends TypeInterface
{
    /**
     * @param \Leapt\CoreBundle\Datalist\DatalistBuilder $builder
     * @param array $options
     * @return mixed
     */
    public function buildDatalist(DatalistBuilder $builder, array $options);

    /**
     * @param \Leapt\CoreBundle\Datalist\ViewContext $viewContext
     * @param \Leapt\CoreBundle\Datalist\DatalistInterface $datalist
     * @param array $options
     */
    public function buildViewContext(ViewContext $viewContext, DatalistInterface $datalist, array $options);
}