<?php

namespace Leapt\CoreBundle\Datalist\Type;

use Leapt\CoreBundle\Datalist\DatalistBuilder;
use Leapt\CoreBundle\Datalist\DatalistInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractDatalistType
 * @package Leapt\CoreBundle\Datalist\Type
 */
abstract class AbstractDatalistType implements DatalistTypeInterface
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class'         => null,
                'layout'             => 'grid',
                'filters_on_top'     => false,
                'limit_per_page'     => null,
                'range_limit'        => 10,
                'search_placeholder' => null,
                'search_submit'      => 'datalist.search.submit',
                'filter_submit'      => 'datalist.filter.submit',
                'filter_reset'       => 'datalist.filter.reset',
                'translation_domain' => 'messages',
            ])
            ->setDefined([
                'search',
            ]);
    }

    /**
     * @param \Leapt\CoreBundle\Datalist\DatalistBuilder $builder
     * @param array $options
     * @return mixed|void
     */
    public function buildDatalist(DatalistBuilder $builder, array $options)
    {

    }

    /**
     * @param \Leapt\CoreBundle\Datalist\ViewContext $viewContext
     * @param \Leapt\CoreBundle\Datalist\DatalistInterface $datalist
     * @param array $options
     */
    public function buildViewContext(ViewContext $viewContext, DatalistInterface $datalist, array $options)
    {
        $viewContext['datalist'] = $datalist;
        $viewContext['options'] = $options;
        $viewContext['translation_domain'] = $options['translation_domain'];
    }
}