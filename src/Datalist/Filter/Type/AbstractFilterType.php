<?php

namespace Leapt\CoreBundle\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractFilterType.
 */
abstract class AbstractFilterType implements FilterTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['property_path' => null])
            ->setDefined(['default']);
    }

    /**
     * @param mixed $row
     */
    public function buildViewContext(ViewContext $viewContext, DatalistFilterInterface $filter, $row, array $options)
    {
        $viewContext['translation_domain'] = $filter->getDatalist()->getOption('translation_domain');
    }
}
