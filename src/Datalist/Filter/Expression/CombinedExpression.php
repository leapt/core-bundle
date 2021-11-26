<?php

namespace Leapt\CoreBundle\Datalist\Filter\Expression;

class CombinedExpression implements ExpressionInterface
{
    public const OPERATOR_AND = 'and';
    public const OPERATOR_OR = 'or';

    private array $expressions;

    public function __construct(private string $operator)
    {
        if (!\in_array($operator, self::getValidOperators(), true)) {
            throw new \InvalidArgumentException(sprintf('Unknown operator "%s"', $operator));
        }

        $this->expressions = \array_slice(\func_get_args(), 1);
    }

    public function addExpression(ExpressionInterface $expression)
    {
        $this->expressions[] = $expression;
    }

    public function getExpressions(): array
    {
        return $this->expressions;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    private static function getValidOperators(): array
    {
        return [
            self::OPERATOR_AND, self::OPERATOR_OR,
        ];
    }
}
