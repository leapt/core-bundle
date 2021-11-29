<?php

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface FieldTypeInterface.
 */
interface FieldTypeInterface extends TypeInterface
{
    public function configureOptions(OptionsResolver $resolver): void;
}
