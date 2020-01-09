<?php

namespace Leapt\CoreBundle\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Interface FilterTypeInterface
 * @package Leapt\CoreBundle\Datalist\Filter\Type
 */
interface FilterTypeInterface extends TypeInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface $filter
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, DatalistFilterInterface $filter, array $options);

    /**
     * @param \Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder $builder
     * @param \Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface $filter
     * @param mixed $value
     * @param array $options
     */
    public function buildExpression(DatalistFilterExpressionBuilder $builder, DatalistFilterInterface $filter, $value, array $options);
}