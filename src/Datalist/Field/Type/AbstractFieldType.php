<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFieldType implements FieldTypeInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                'property_path'      => null,
                'default'            => null,
                'escape'             => true,
                'sortable'           => false,
                'sort_property_path' => null,
            ])
            ->setDefined(['callback', 'order'])
            ->setAllowedTypes('callback', 'callable');
    }

    public function buildViewContext(ViewContext $viewContext, DatalistFieldInterface $field, mixed $row, array $options): void
    {
        if (isset($options['callback'])) {
            $viewContext['value'] = \call_user_func($options['callback'], $row);
        } else {
            $viewContext['value'] = $field->getData($row);
        }

        $viewContext['field'] = $field;
        $viewContext['options'] = $options;
        $viewContext['translation_domain'] = $field->getDatalist()->getOption('translation_domain');
    }
}
