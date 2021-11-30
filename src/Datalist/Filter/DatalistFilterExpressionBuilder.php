<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Filter;

use Leapt\CoreBundle\Datalist\Filter\Expression\CombinedExpression;
use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;

class DatalistFilterExpressionBuilder
{
    private CombinedExpression $expression;

    public function __construct()
    {
        $this->expression = new CombinedExpression(CombinedExpression::OPERATOR_AND);
    }

    public function add(ExpressionInterface $expression): void
    {
        $this->expression->addExpression($expression);
    }

    public function getExpression(): CombinedExpression
    {
        return $this->expression;
    }
}
