<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SitemapCompilerPass implements CompilerPassInterface
{
    /**
     * Check for indexer services in configuration.
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('leapt_core.sitemap_manager')) {
            return;
        }
        $definition = $container->getDefinition('leapt_core.sitemap_manager');
        foreach ($container->findTaggedServiceIds('leapt_core.sitemap') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;
            $definition->addMethodCall('registerSitemap', [$alias, new Reference($serviceId)]);
        }
    }
}
