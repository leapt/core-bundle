<?php

namespace Leapt\CoreBundle\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\Filter\Expression\ComparisonExpression;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceFilterType
 * @package Leapt\CoreBundle\Datalist\Filter\Type
 */
class ChoiceFilterType extends AbstractFilterType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(['choices'])
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
            'choices'  => $options['choices'],
            'label'    => $options['label'],
            'required' => false,
        ];

        foreach ($this->getDefinedOptions() as $option) {
            if (isset($options[$option])) {
                $formOptions[$option] = $options[$option];
            }
        }

        $builder->add($filter->getName(), ChoiceType::class, $formOptions);
    }

    /**
     * @param \Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder $builder
     * @param \Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface $filter
     * @param mixed $value
     * @param array $options
     */
    public function buildExpression(DatalistFilterExpressionBuilder $builder, DatalistFilterInterface $filter, $value, array $options)
    {
        $builder->add(new ComparisonExpression($filter->getPropertyPath(), ComparisonExpression::OPERATOR_EQ, $value));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'choice';
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return 'choice';
    }

    /**
     * @return array
     */
    private function getDefinedOptions()
    {
        return [
            'placeholder',
            'preferred_choices',
            'choice_translation_domain',
            'choice_label',
        ];
    }
}