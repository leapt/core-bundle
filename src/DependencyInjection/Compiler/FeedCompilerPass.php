<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\DependencyInjection\Compiler;

use Leapt\CoreBundle\Feed\FeedManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FeedCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasDefinition(FeedManager::class)) {
            return;
        }
        $definition = $container->getDefinition(FeedManager::class);
        foreach ($container->findTaggedServiceIds('leapt_core.feed') as $serviceId => $tag) {
            $alias = $tag[0]['alias'] ?? $serviceId;
            $definition->addMethodCall('registerFeed', [$alias, new Reference($serviceId)]);
        }
    }
}
