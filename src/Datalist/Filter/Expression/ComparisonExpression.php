<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Filter\Expression;

class ComparisonExpression implements ExpressionInterface
{
    public const OPERATOR_EQ = 'eq';
    public const OPERATOR_NEQ = 'neq';
    public const OPERATOR_GT = 'gt';
    public const OPERATOR_GTE = 'gte';
    public const OPERATOR_LT = 'lt';
    public const OPERATOR_LTE = 'lte';
    public const OPERATOR_LIKE = 'like';
    public const OPERATOR_IN = 'in';
    public const OPERATOR_NIN = 'nin';
    public const OPERATOR_IS_NULL = 'is_null';
    public const OPERATOR_IS_NOT_NULL = 'is_not_null';

    public function __construct(
        private string $propertyPath,
        private string $operator,
        private mixed $value,
    ) {
        if (!\in_array($operator, self::getValidOperators(), true)) {
            throw new \InvalidArgumentException(sprintf('Unknown operator "%s"', $operator));
        }
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    private static function getValidOperators(): array
    {
        return [
            self::OPERATOR_EQ, self::OPERATOR_NEQ, self::OPERATOR_GT, self::OPERATOR_GTE,
            self::OPERATOR_LT, self::OPERATOR_LTE, self::OPERATOR_LIKE, self::OPERATOR_IN,
            self::OPERATOR_NIN, self::OPERATOR_IS_NULL, self::OPERATOR_IS_NOT_NULL,
        ];
    }
}
