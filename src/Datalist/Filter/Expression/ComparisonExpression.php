<?php

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

    /**
     * @var string
     */
    private $propertyPath;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $propertyPath
     * @param string $operator
     * @param mixed  $value
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($propertyPath, $operator, $value)
    {
        if (!\in_array($operator, self::getValidOperators(), true)) {
            throw new \InvalidArgumentException(sprintf('Unknown operator "%s"', $operator));
        }

        $this->propertyPath = $propertyPath;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    /**
     * @return array
     */
    private static function getValidOperators()
    {
        return [
            self::OPERATOR_EQ, self::OPERATOR_NEQ, self::OPERATOR_GT, self::OPERATOR_GTE,
            self::OPERATOR_LT, self::OPERATOR_LTE, self::OPERATOR_LIKE, self::OPERATOR_IN,
            self::OPERATOR_NIN, self::OPERATOR_IS_NULL, self::OPERATOR_IS_NOT_NULL,
        ];
    }
}
