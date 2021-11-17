<?php

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

    /**
     * @param Expression\ExpressionInterface $expression
     */
    public function add(ExpressionInterface $expression)
    {
        $this->expression->addExpression($expression);
    }

    /**
     * @return ExpressionInterface
     */
    public function getExpression()
    {
        return $this->expression;
    }
}
