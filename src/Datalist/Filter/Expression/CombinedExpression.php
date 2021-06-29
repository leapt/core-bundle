<?php

namespace Leapt\CoreBundle\Datalist\Filter\Expression;

class CombinedExpression implements ExpressionInterface
{
    public const OPERATOR_AND = 'and';
    public const OPERATOR_OR = 'or';

    /**
     * @var string
     */
    private $operator;

    /**
     * @var array
     */
    private $expressions = [];

    /**
     * @param string $operator
     */
    public function __construct($operator)
    {
        if (!\in_array($operator, self::getValidOperators(), true)) {
            throw new \InvalidArgumentException(sprintf('Unknown operator "%s"', $operator));
        }

        $this->operator = $operator;

        $this->expressions = \array_slice(\func_get_args(), 1);
    }

    public function addExpression(ExpressionInterface $expression)
    {
        $this->expressions[] = $expression;
    }

    /**
     * @return array
     */
    public function getExpressions()
    {
        return $this->expressions;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return array
     */
    private static function getValidOperators()
    {
        return [
            self::OPERATOR_AND, self::OPERATOR_OR,
        ];
    }
}
