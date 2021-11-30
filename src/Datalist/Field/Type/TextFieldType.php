<?php

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextFieldType extends AbstractFieldType
{
    public function getName(): string
    {
        return 'text';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(['truncate']);
    }

    public function buildViewContext(ViewContext $viewContext, DatalistFieldInterface $field, mixed $value, array $options): void
    {
        parent::buildViewContext($viewContext, $field, $value, $options);

        if (isset($options['truncate'])) {
            $viewContext['truncate'] = $options['truncate'];
        }
    }

    public function getBlockName(): string
    {
        return 'text';
    }
}
