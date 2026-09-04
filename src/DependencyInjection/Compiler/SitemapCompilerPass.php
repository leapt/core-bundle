<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\DependencyInjection\Compiler;

use Leapt\CoreBundle\Sitemap\SitemapManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SitemapCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasDefinition(SitemapManager::class)) {
            return;
        }
        $definition = $container->getDefinition(SitemapManager::class);
        foreach ($container->findTaggedServiceIds('leapt_core.sitemap') as $serviceId => $tags) {
            $alias = $serviceId;
            foreach ($tags as $tag) {
                if (isset($tag['alias'])) {
                    $alias = $tag['alias'];
                    break;
                }
            }
            $definition->addMethodCall('registerSitemap', [$alias, new Reference($serviceId)]);
        }
    }
}
