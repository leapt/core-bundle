<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Datasource;

use Leapt\CoreBundle\Datalist\Filter\Expression\CombinedExpression;
use Leapt\CoreBundle\Datalist\Filter\Expression\ComparisonExpression;
use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;
use Leapt\CoreBundle\Paginator\ArrayPaginator;
use Leapt\CoreBundle\Paginator\PaginatorInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ArrayDatasource extends AbstractDatasource
{
    private bool $initialized = false;

    public function __construct(private array $items = [])
    {
    }

    public function getPaginator(): ?PaginatorInterface
    {
        $this->initialize();

        return $this->paginator;
    }

    public function getIterator(): \Iterator
    {
        $this->initialize();

        return $this->iterator;
    }

    protected function initialize(): void
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

    private function buildExpressionCallback(ExpressionInterface $expression): callable
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

    private function buildCombinedExpressionCallback(CombinedExpression $expression): callable
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

    private function buildComparisonExpressionCallback(ComparisonExpression $expression): callable
    {
        return function ($item) use ($expression) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $value = $accessor->getValue($item, $expression->getPropertyPath());
            $comparisonValue = $expression->getValue();
            $operator = $expression->getOperator();

            return match ($operator) {
                ComparisonExpression::OPERATOR_EQ          => $value === $comparisonValue,
                ComparisonExpression::OPERATOR_NEQ         => $value !== $comparisonValue,
                ComparisonExpression::OPERATOR_GT          => $value > $comparisonValue,
                ComparisonExpression::OPERATOR_GTE         => $value >= $comparisonValue,
                ComparisonExpression::OPERATOR_LT          => $value < $comparisonValue,
                ComparisonExpression::OPERATOR_LTE         => $value <= $comparisonValue,
                ComparisonExpression::OPERATOR_LIKE        => str_contains($value, $comparisonValue),
                ComparisonExpression::OPERATOR_IN          => \in_array($value, $comparisonValue, true),
                ComparisonExpression::OPERATOR_NIN         => !\in_array($value, $comparisonValue, true),
                ComparisonExpression::OPERATOR_IS_NULL     => null === $value,
                ComparisonExpression::OPERATOR_IS_NOT_NULL => null !== $value,
                default                                    => throw new \UnexpectedValueException(sprintf('Unknown operator "%s"', $operator)),
            };
        };
    }
}
