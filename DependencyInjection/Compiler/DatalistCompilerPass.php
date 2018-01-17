<?php

namespace Leapt\CoreBundle\DependencyInjection\Compiler;

use Leapt\CoreBundle\Datalist\DatalistFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DatalistCompilerPass
 * @package Leapt\CoreBundle\DependencyInjection\Compiler
 */
class DatalistCompilerPass implements CompilerPassInterface
{
    /**
     * Check for indexer services in configuration
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(DatalistFactory::class)) {
            return;
        }
        $definition = $container->getDefinition(DatalistFactory::class);

        foreach ($container->findTaggedServiceIds('leapt_core.datalist.action_type') as $serviceId => $tag) {
            $definition->addMethodCall('registerActionType', [new Reference($serviceId)]);
        }

        foreach ($container->findTaggedServiceIds('leapt_core.datalist.field_type') as $serviceId => $tag) {
            $definition->addMethodCall('registerFieldType', [new Reference($serviceId)]);
        }

        foreach ($container->findTaggedServiceIds('leapt_core.datalist.filter_type') as $serviceId => $tag) {
            $definition->addMethodCall('registerFilterType', [new Reference($serviceId)]);
        }

        foreach ($container->findTaggedServiceIds('leapt_core.datalist.type') as $serviceId => $tag) {
            $definition->addMethodCall('registerType', [new Reference($serviceId)]);
        }
    }
}