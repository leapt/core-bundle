<?php

namespace Snowcap\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FeedCompilerPass implements CompilerPassInterface
{
    /**
     * Check for indexer services in configuration
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('snowcap_core.feed_manager')) {
            return;
        }
        $definition = $container->getDefinition('snowcap_core.feed_manager');
        foreach ($container->findTaggedServiceIds('snowcap_core.feed') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;
            $definition->addMethodCall('registerFeed', array($alias, new Reference($serviceId)));
        }
    }

}