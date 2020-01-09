<?php

namespace Leapt\CoreBundle\Datalist\Filter\Type;

use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractFilterType
 * @package Leapt\CoreBundle\Datalist\Filter\Type
 */
abstract class AbstractFilterType implements FilterTypeInterface
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['property_path' => null])
            ->setDefined(['default']);
    }

    /**
     * @param ViewContext $viewContext
     * @param DatalistFilterInterface $filter
     * @param mixed $row
     * @param array $options
     */
    public function buildViewContext(ViewContext $viewContext, DatalistFilterInterface $filter, $row, array $options)
    {
        $viewContext['translation_domain'] = $filter->getDatalist()->getOption('translation_domain');
    }
}