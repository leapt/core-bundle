<?php

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TextFieldType.
 */
class TextFieldType extends AbstractFieldType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'text';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(['truncate']);
    }

    /**
     * @param mixed $row
     */
    public function buildViewContext(ViewContext $viewContext, DatalistFieldInterface $field, $row, array $options)
    {
        parent::buildViewContext($viewContext, $field, $row, $options);

        if (isset($options['truncate'])) {
            $viewContext['truncate'] = $options['truncate'];
        }
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return 'text';
    }
}
