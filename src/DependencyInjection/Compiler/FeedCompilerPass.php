<?php

namespace Leapt\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FeedCompilerPass.
 */
class FeedCompilerPass implements CompilerPassInterface
{
    /**
     * Check for indexer services in configuration.
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('leapt_core.feed_manager')) {
            return;
        }
        $definition = $container->getDefinition('leapt_core.feed_manager');
        foreach ($container->findTaggedServiceIds('leapt_core.feed') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;
            $definition->addMethodCall('registerFeed', [$alias, new Reference($serviceId)]);
        }
    }
}
