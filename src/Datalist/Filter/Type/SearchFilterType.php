<?php

namespace Leapt\CoreBundle\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\Filter\Expression\CombinedExpression;
use Leapt\CoreBundle\Datalist\Filter\Expression\ComparisonExpression;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFilterType extends AbstractFilterType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(['search_fields'])
            ->setDefault('search_explode_terms', false)
            ->setAllowedTypes('search_explode_terms', ['boolean']);
    }

    public function buildForm(FormBuilderInterface $builder, DatalistFilterInterface $filter, array $options)
    {
        $builder->add($filter->getName(), SearchType::class, [
            'label' => $options['label'],
        ]);
    }

    public function buildExpression(DatalistFilterExpressionBuilder $builder, DatalistFilterInterface $filter, mixed $value, array $options)
    {
        $terms = true === $options['search_explode_terms'] ? explode(' ', $value) : [$value];
        $baseExpression = new CombinedExpression(CombinedExpression::OPERATOR_AND);

        foreach ($terms as $term) {
            if (!empty($term)) {
                if (\is_array($options['search_fields'])) {
                    $expression = new CombinedExpression(CombinedExpression::OPERATOR_OR);
                    foreach ($options['search_fields'] as $searchField) {
                        $comparisonExpression = new ComparisonExpression($searchField, ComparisonExpression::OPERATOR_LIKE, $term);
                        $expression->addExpression($comparisonExpression);
                    }
                } else {
                    $expression = new ComparisonExpression($options['search_fields'], ComparisonExpression::OPERATOR_LIKE, $term);
                }
                $baseExpression->addExpression($expression);
            }
        }

        $builder->add($baseExpression);
    }

    public function getName(): string
    {
        return 'search';
    }

    public function getBlockName(): string
    {
        return 'search';
    }
}
