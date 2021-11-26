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

    public function getName(): string
    {
        return 'datetime';
    }

    public function getBlockName(): string
    {
        return 'datetime';
    }
}
