<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanFieldType extends AbstractFieldType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'true_label'  => null,
                'false_label' => null,
            ])
            ->setAllowedTypes('true_label', ['null', 'string'])
            ->setAllowedTypes('false_label', ['null', 'string'])
        ;
    }

    public function buildViewContext(ViewContext $viewContext, DatalistFieldInterface $field, mixed $value, array $options): void
    {
        parent::buildViewContext($viewContext, $field, $value, $options);

        $viewContext['true_label'] = $options['true_label'];
        $viewContext['false_label'] = $options['false_label'];
    }

    public function getName(): string
    {
        return 'boolean';
    }

    public function getBlockName(): string
    {
        return 'boolean';
    }
}
