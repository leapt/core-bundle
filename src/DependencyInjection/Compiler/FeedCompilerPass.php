<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FeedCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasDefinition('leapt_core.feed_manager')) {
            return;
        }
        $definition = $container->getDefinition('leapt_core.feed_manager');
        foreach ($container->findTaggedServiceIds('leapt_core.feed') as $serviceId => $tag) {
            $alias = $tag[0]['alias'] ?? $serviceId;
            $definition->addMethodCall('registerFeed', [$alias, new Reference($serviceId)]);
        }
    }
}
