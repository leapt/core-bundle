<?php

namespace Leapt\CoreBundle\Datalist\Datasource;

use Leapt\CoreBundle\Datalist\Filter\Expression\CombinedExpression;
use Leapt\CoreBundle\Datalist\Filter\Expression\ComparisonExpression;
use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;
use Leapt\CoreBundle\Paginator\ArrayPaginator;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class ArrayDatasource.
 */
class ArrayDatasource extends AbstractDatasource
{
    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @var array
     */
    private $items = [];

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return \Leapt\CoreBundle\Paginator\ArrayPaginator
     */
    public function getPaginator()
    {
        $this->initialize();

        return $this->paginator;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $this->initialize();

        return $this->iterator;
    }

    protected function initialize()
    {
        if ($this->initialized) {
            return;
        }

        $items = $this->items;

        // Handle search
        if (isset($this->searchExpression)) {
            $searchCallback = $this->buildExpressionCallback($this->searchExpression);
            $items = array_filter($items, $searchCallback);
        }

        // Handle filters
        if (isset($this->filterExpression)) {
            $filterCallback = $this->buildExpressionCallback($this->filterExpression);
            $items = array_filter($items, $filterCallback);
        }

        // @TODO Handle sort

        // Handle pagination
        if (isset($this->limitPerPage)) {
            $paginator = new ArrayPaginator($items);
            $paginator
                ->setLimitPerPage($this->limitPerPage)
                ->setRangeLimit($this->rangeLimit)
                ->setPage($this->page);

            $this->iterator = $paginator->getIterator();
            $this->paginator = $paginator;
        } else {
            $this->iterator = new \ArrayIterator($items);
            $this->paginator = null;
        }

        $this->initialized = true;
    }

    /**
     * @return callable
     *
     * @throws \InvalidArgumentException
     */
    private function buildExpressionCallback(ExpressionInterface $expression)
    {
        // If we have a combined expression ("AND" / "OR")
        if ($expression instanceof CombinedExpression) {
            $function = $this->buildCombinedExpressionCallback($expression);
        } elseif ($expression instanceof ComparisonExpression) {
            $function = $this->buildComparisonExpressionCallback($expression);
        } else {
            throw new \InvalidArgumentException(sprintf('Cannot handle expression of class "%s"', \get_class($expression)));
        }

        return $function;
    }

    /**
     * @return callable
     *
     * @throws \UnexpectedValueException
     */
    private function buildCombinedExpressionCallback(CombinedExpression $expression)
    {
        $tests = [];
        foreach ($expression->getExpressions() as $subExpression) {
            $tests[] = $this->buildExpressionCallback($subExpression);
        }
        $operator = $expression->getOperator();
        // If we have a "AND" expression, return a function testing that all sub-expressions succeed
        if (CombinedExpression::OPERATOR_AND === $operator) {
            $function = function ($item) use ($tests) {
                foreach ($tests as $test) {
                    if (!\call_user_func($test, $item)) {
                        return false;
                    }
                }

                return true;
            };
        }
        // If we have a "OR" expression, return a function testing that at least one sub-expression succeeds
        elseif (CombinedExpression::OPERATOR_OR === $operator) {
            $function = function ($item) use ($tests) {
                foreach ($tests as $test) {
                    if (\call_user_func($test, $item)) {
                        return true;
                    }
                }

                return false;
            };
        } else {
            throw new \UnexpectedValueException(sprintf('Unknown operator "%s"', $operator));
        }

        return $function;
    }

    /**
     * @return callable
     *
     * @throws \UnexpectedValueException
     */
    private function buildComparisonExpressionCallback(ComparisonExpression $expression)
    {
        $function = function ($item) use ($expression) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $value = $accessor->getValue($item, $expression->getPropertyPath());
            $comparisonValue = $expression->getValue();
            $operator = $expression->getOperator();

            switch ($operator) {
                case ComparisonExpression::OPERATOR_EQ:
                    $result = $value === $comparisonValue;
                    break;
                case ComparisonExpression::OPERATOR_NEQ:
                    $result = $value !== $comparisonValue;
                    break;
                case ComparisonExpression::OPERATOR_GT:
                    $result = $value > $comparisonValue;
                    break;
                case ComparisonExpression::OPERATOR_GTE:
                    $result = $value >= $comparisonValue;
                    break;
                case ComparisonExpression::OPERATOR_LT:
                    $result = $value < $comparisonValue;
                    break;
                case ComparisonExpression::OPERATOR_LTE:
                    $result = $value <= $comparisonValue;
                    break;
                case ComparisonExpression::OPERATOR_LIKE:
                    $result = str_contains($value, $comparisonValue);
                    break;
                case ComparisonExpression::OPERATOR_IN:
                    $result = \in_array($value, $comparisonValue, true);
                    break;
                case ComparisonExpression::OPERATOR_NIN:
                    $result = !\in_array($value, $comparisonValue, true);
                    break;
                case ComparisonExpression::OPERATOR_IS_NULL:
                    $result = null === $value;
                    break;
                case ComparisonExpression::OPERATOR_IS_NOT_NULL:
                    $result = null !== $value;
                    break;
                default:
                    throw new \UnexpectedValueException(sprintf('Unknown operator "%s"', $operator));
                    break;
            }

            return $result;
        };

        return $function;
    }
}
