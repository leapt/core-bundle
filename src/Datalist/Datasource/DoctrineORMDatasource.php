<?php

namespace Leapt\CoreBundle\Datalist\Datasource;

use Doctrine\ORM\QueryBuilder;
use Leapt\CoreBundle\Datalist\Filter\Expression\CombinedExpression;
use Leapt\CoreBundle\Datalist\Filter\Expression\ComparisonExpression;
use Leapt\CoreBundle\Datalist\Filter\Expression\ExpressionInterface;
use Leapt\CoreBundle\Paginator\DoctrineORMPaginator;

/**
 * Class DoctrineORMDatasource.
 */
class DoctrineORMDatasource extends AbstractDatasource
{
    private QueryBuilder $queryBuilder;

    private bool $initialized = false;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @return \Leapt\CoreBundle\Paginator\DoctrineORMPaginator
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

    /**
     * Load the collection.
     */
    protected function initialize()
    {
        if ($this->initialized) {
            return;
        }

        // Handle search
        if (isset($this->searchExpression)) {
            $queryBuilderExpression = $this->buildQueryBuilderExpression($this->searchExpression);
            $this->queryBuilder->andWhere($queryBuilderExpression);
        }

        // Handle filters
        if (isset($this->filterExpression)) {
            $queryBuilderExpression = $this->buildQueryBuilderExpression($this->filterExpression);
            $this->queryBuilder->andWhere($queryBuilderExpression);
        }

        // Handle sort
        if (null !== $this->sortField && null !== $this->sortDirection) {
            $oldOrderBys = $this->queryBuilder->getDQLPart('orderBy');
            $this->queryBuilder->resetDQLPart('orderBy');
            $this->queryBuilder->orderBy($this->sortField, $this->sortDirection);
            foreach ($oldOrderBys as $oldOrderBy) {
                $this->queryBuilder->add('orderBy', $oldOrderBy, true);
            }
        }

        // Handle pagination
        if (isset($this->limitPerPage)) {
            $paginator = new DoctrineORMPaginator($this->queryBuilder->getQuery());
            $paginator
                ->setLimitPerPage($this->limitPerPage)
                ->setRangeLimit($this->rangeLimit)
                ->setPage($this->page);
            $this->iterator = $paginator->getIterator();
            $this->paginator = $paginator;
        } else {
            $items = $this->queryBuilder->getQuery()->getResult();
            $this->iterator = new \ArrayIterator($items);
            $this->paginator = null;
        }

        $this->initialized = true;
    }

    /**
     * @return \Doctrine\ORM\Query\Expr\Andx|\Doctrine\ORM\Query\Expr\Comparison|\Doctrine\ORM\Query\Expr\Orx
     *
     * @throws \InvalidArgumentException
     */
    private function buildQueryBuilderExpression(ExpressionInterface $expression)
    {
        // If we have a combined expression ("AND" / "OR")
        if ($expression instanceof CombinedExpression) {
            $queryBuilderExpression = $this->buildQueryBuilderCombinedExpression($expression);
        } elseif ($expression instanceof ComparisonExpression) {
            $queryBuilderExpression = $this->buildQueryBuilderComparisonExpression($expression);
        } else {
            throw new \InvalidArgumentException(sprintf('Cannot handle expression of class "%s"', \get_class($expression)));
        }

        return $queryBuilderExpression;
    }

    /**
     * @return \Doctrine\ORM\Query\Expr\Andx|\Doctrine\ORM\Query\Expr\Orx
     *
     * @throws \UnexpectedValueException
     */
    private function buildQueryBuilderCombinedExpression(CombinedExpression $expression)
    {
        $queryBuilderSubExpressions = [];
        foreach ($expression->getExpressions() as $subExpression) {
            $queryBuilderSubExpressions[] = $this->buildQueryBuilderExpression($subExpression);
        }
        $operator = $expression->getOperator();
        if (CombinedExpression::OPERATOR_AND === $operator) {
            $expr = $this->queryBuilder->expr()->andX();
        } elseif (CombinedExpression::OPERATOR_OR === $operator) {
            $expr = $this->queryBuilder->expr()->orX();
        } else {
            throw new \UnexpectedValueException(sprintf('Unknown operator "%s"', $operator));
        }
        $expr->addMultiple($queryBuilderSubExpressions);

        return $expr;
    }

    /**
     * @return \Doctrine\ORM\Query\Expr\Comparison
     *
     * @throws \UnexpectedValueException
     */
    private function buildQueryBuilderComparisonExpression(ComparisonExpression $expression)
    {
        $propertyPath = $expression->getPropertyPath();
        $placeholder = ':' . uniqid('p');
        $comparisonValue = $expression->getValue();
        $operator = $expression->getOperator();

        switch ($operator) {
            case ComparisonExpression::OPERATOR_EQ:
                $expr = $this->queryBuilder->expr()->eq($propertyPath, $placeholder);
                break;
            case ComparisonExpression::OPERATOR_NEQ:
                $expr = $this->queryBuilder->expr()->neq($propertyPath, $placeholder);
                break;
            case ComparisonExpression::OPERATOR_GT:
                $expr = $this->queryBuilder->expr()->gt($propertyPath, $placeholder);
                break;
            case ComparisonExpression::OPERATOR_GTE:
                $expr = $this->queryBuilder->expr()->gte($propertyPath, $placeholder);
                break;
            case ComparisonExpression::OPERATOR_LT:
                $expr = $this->queryBuilder->expr()->lt($propertyPath, $placeholder);
                break;
            case ComparisonExpression::OPERATOR_LTE:
                $expr = $this->queryBuilder->expr()->lte($propertyPath, $placeholder);
                break;
            case ComparisonExpression::OPERATOR_LIKE:
                $expr = $this->queryBuilder->expr()->like($propertyPath, $placeholder);
                $comparisonValue = '%' . $comparisonValue . '%';
                break;
            case ComparisonExpression::OPERATOR_IN:
                $expr = $this->queryBuilder->expr()->in($propertyPath, $placeholder);
                break;
            case ComparisonExpression::OPERATOR_NIN:
                $expr = $this->queryBuilder->expr()->notIn($propertyPath, $placeholder);
                break;
            case ComparisonExpression::OPERATOR_IS_NULL:
                $expr = $this->queryBuilder->expr()->isNull($propertyPath);
                break;
            case ComparisonExpression::OPERATOR_IS_NOT_NULL:
                $expr = $this->queryBuilder->expr()->isNotNull($propertyPath);
                break;
            default:
                throw new \UnexpectedValueException(sprintf('Unknown operator "%s"', $operator));
                break;
        }

        if (!\in_array($operator, [ComparisonExpression::OPERATOR_IS_NULL, ComparisonExpression::OPERATOR_IS_NOT_NULL], true)) {
            $this->queryBuilder->setParameter($placeholder, $comparisonValue);
        }

        return $expr;
    }
}
