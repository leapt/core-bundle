<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\TypeInterface;
use Symfony\Component\Form\FormBuilderInterface;

interface FilterTypeInterface extends TypeInterface
{
    public function buildForm(FormBuilderInterface $builder, DatalistFilterInterface $filter, array $options);

    public function buildExpression(DatalistFilterExpressionBuilder $builder, DatalistFilterInterface $filter, mixed $value, array $options): void;
}
