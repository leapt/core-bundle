<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\Filter\DatalistFilterExpressionBuilder;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\Filter\Expression\ComparisonExpression;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanFilterType extends AbstractFilterType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'true_label'   => 'Yes',
                'false_label'  => 'No',
                'use_booleans' => false,
            ])
            ->setAllowedTypes('true_label', ['null', 'string'])
            ->setAllowedTypes('false_label', ['null', 'string'])
            ->setAllowedTypes('use_booleans', ['bool'])
            ->setDefined(['placeholder'])
        ;
    }

    public function buildForm(FormBuilderInterface $builder, DatalistFilterInterface $filter, array $options): void
    {
        $formOptions = [
            'label'    => $options['label'],
            'required' => false,
            'choices'  => [
                $options['false_label'] => '0',
                $options['true_label']  => '1',
            ],
        ];
        if (isset($options['placeholder'])) {
            $formOptions['placeholder'] = $options['placeholder'];
        }

        $builder->add($filter->getName(), ChoiceType::class, $formOptions);
    }

    public function buildExpression(DatalistFilterExpressionBuilder $builder, DatalistFilterInterface $filter, mixed $value, array $options): void
    {
        $builder->add(new ComparisonExpression($filter->getPropertyPath(), ComparisonExpression::OPERATOR_EQ, $options['use_booleans'] ? (bool) $value : $value));
    }

    public function getName(): string
    {
        return 'boolean';
    }

    public function getBlockName(): string
    {
        return 'choice';
    }
}
