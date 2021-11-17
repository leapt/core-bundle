<?php

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeFieldType extends AbstractFieldType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'format' => 'd/m/Y',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'datetime';
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return 'datetime';
    }
}
