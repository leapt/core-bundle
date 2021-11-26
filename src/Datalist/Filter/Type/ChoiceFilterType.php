<?php

namespace Leapt\CoreBundle\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\Filter\Expression\ComparisonExpression;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceFilterType extends AbstractFilterType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(['choices'])
            ->setDefined($this->getDefinedOptions());
    }

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

    public function buildExpression(DatalistFilterExpressionBuilder $builder, DatalistFilterInterface $filter, mixed $value, array $options)
    {
        $builder->add(new ComparisonExpression($filter->getPropertyPath(), ComparisonExpression::OPERATOR_EQ, $value));
    }

    public function getName(): string
    {
        return 'choice';
    }

    public function getBlockName(): string
    {
        return 'choice';
    }

    private function getDefinedOptions(): array
    {
        return [
            'placeholder',
            'preferred_choices',
            'choice_translation_domain',
            'choice_label',
        ];
    }
}
