<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface TypeInterface
{
    public function getName(): string;

    public function configureOptions(OptionsResolver $resolver): void;

    public function getBlockName(): string;
}
