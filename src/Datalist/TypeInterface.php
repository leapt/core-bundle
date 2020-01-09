<?php

namespace Leapt\CoreBundle\Datalist;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface TypeInterface.
 */
interface TypeInterface
{
    /**
     * @return string
     */
    public function getName();

    public function configureOptions(OptionsResolver $resolver);

    /**
     * @return string
     */
    public function getBlockName();
}
