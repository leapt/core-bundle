<?php

namespace Leapt\CoreBundle\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\Filter\Expression\CombinedExpression;
use Leapt\CoreBundle\Datalist\Filter\Expression\ComparisonExpression;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SearchFilterType
 * @package Leapt\CoreBundle\Datalist\Filter\Type
 */
class SearchFilterType extends AbstractFilterType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(['search_fields']);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface $filter
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, DatalistFilterInterface $filter, array $options)
    {
        $builder->add($filter->getName(), SearchType::class, [
            'label' => $options['label']
        ]);
    }

    /**
     * @param \Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder $builder
     * @param \Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface $filter
     * @param mixed $value
     * @param array $options
     */
    public function buildExpression(DatalistFilterExpressionBuilder $builder, DatalistFilterInterface $filter, $value, array $options)
    {
        if (is_array($options['search_fields'])) {
            $expression = new CombinedExpression(CombinedExpression::OPERATOR_OR);
            foreach($options['search_fields'] as $searchField) {
                $comparisonExpression = new ComparisonExpression($searchField, ComparisonExpression::OPERATOR_LIKE, $value);
                $expression->addExpression($comparisonExpression);
            }
        } else {
            $expression = new ComparisonExpression($options['search_fields'], ComparisonExpression::OPERATOR_LIKE, $value);
        }
        $builder->add($expression);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'search';
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return 'search';
    }
}