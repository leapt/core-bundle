<?php

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BooleanFieldType.
 */
class BooleanFieldType extends AbstractFieldType
{
    public function configureOptions(OptionsResolver $resolver)
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

    public function buildViewContext(ViewContext $viewContext, DatalistFieldInterface $field, mixed $value, array $options)
    {
        parent::buildViewContext($viewContext, $field, $value, $options);

        $viewContext['true_label'] = $options['true_label'];
        $viewContext['false_label'] = $options['false_label'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'boolean';
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return 'boolean';
    }
}
