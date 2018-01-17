<?php

namespace Leapt\CoreBundle\Datalist;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface TypeInterface
 * @package Leapt\CoreBundle\Datalist
 */
interface TypeInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * @return string
     */
    public function getBlockName();
}