<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeFieldType extends AbstractFieldType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'format' => 'd/m/Y',
            'time_ago' => false,
        ]);

        $resolver->setAllowedTypes('time_ago', 'bool');
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
