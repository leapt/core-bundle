<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\Filter\Expression\ComparisonExpression;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnumFilterType extends AbstractFilterType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults(['multiple' => false])
            ->setRequired(['class'])
            ->setDefined($this->getDefinedOptions())
            ->setAllowedTypes('multiple', ['bool']);
    }

    public function buildForm(FormBuilderInterface $builder, DatalistFilterInterface $filter, array $options): void
    {
        $formOptions = [
            'label'    => $options['label'],
            'class'    => $options['class'],
            'required' => false,
            'multiple' => $options['multiple'],
        ];

        foreach ($this->getDefinedOptions() as $option) {
            if (isset($options[$option])) {
                $formOptions[$option] = $options[$option];
            }
        }

        $builder->add($filter->getName(), EnumType::class, $formOptions);
    }

    public function buildExpression(DatalistFilterExpressionBuilder $builder, DatalistFilterInterface $filter, mixed $value, array $options): void
    {
        $operator = true === $options['multiple'] ? ComparisonExpression::OPERATOR_IN : ComparisonExpression::OPERATOR_EQ;
        $builder->add(new ComparisonExpression($filter->getPropertyPath(), $operator, $value));
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
            'choice_value',
        ];
    }
}
