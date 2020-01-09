<?php

namespace Leapt\CoreBundle\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\Filter\Expression\ComparisonExpression;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityFilterType
 * @package Leapt\CoreBundle\Datalist\Filter\Type
 */
class EntityFilterType extends AbstractFilterType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults(['query_builder' => null, 'multiple' => false])
            ->setRequired(['class'])
            ->setDefined($this->getDefinedOptions());
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface $filter
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, DatalistFilterInterface $filter, array $options)
    {
        $formOptions = [
            'class'         => $options['class'],
            'label'         => $options['label'],
            'query_builder' => $options['query_builder'],
            'required'      => false,
            'multiple'      => $options['multiple'],
        ];

        foreach ($this->getDefinedOptions() as $option) {
            if (isset($options[$option])) {
                $formOptions[$option] = $options[$option];
            }
        }

        $builder->add($filter->getName(), EntityType::class, $formOptions);
    }

    /**
     * @param \Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder $builder
     * @param \Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface $filter
     * @param mixed $value
     * @param array $options
     */
    public function buildExpression(DatalistFilterExpressionBuilder $builder, DatalistFilterInterface $filter, $value, array $options)
    {
        $operator = true === $options['multiple'] ? ComparisonExpression::OPERATOR_IN : ComparisonExpression::OPERATOR_EQ;
        $builder->add(new ComparisonExpression($filter->getPropertyPath(), $operator, $value));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'entity';
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return 'entity';
    }

    /**
     * @return array
     */
    private function getDefinedOptions()
    {
        return [
            'choices', 'property', 'placeholder', 'group_by', 'attr',
            'choice_label', 'choice_translation_domain',
        ];
    }
}