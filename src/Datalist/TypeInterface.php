<?php

namespace Leapt\CoreBundle\Datalist;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface TypeInterface.
 */
interface TypeInterface
{
    public function getName(): string;

    public function configureOptions(OptionsResolver $resolver);

    public function getBlockName(): string;
}
