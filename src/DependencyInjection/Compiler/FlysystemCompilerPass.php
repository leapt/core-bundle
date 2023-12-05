<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\DependencyInjection\Compiler;

use League\FlysystemBundle\FlysystemBundle;
use Leapt\CoreBundle\FileStorage\FlysystemStorage;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class FlysystemCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasDefinition(FlysystemStorage::class)) {
            return;
        }

        if (class_exists(FlysystemBundle::class)) {
            $definition = $container->getDefinition(FlysystemStorage::class);
            $storages = [];

            foreach ($container->findTaggedServiceIds('flysystem.storage') as $serviceId => $tags) {
                foreach ($tags as $tag) {
                    if (isset($tag['storage'])) {
                        $storages[$tag['storage']] = new Reference($serviceId);
                    }
                }
            }

            $definition->replaceArgument('$storages', $storages);
        }
    }
}
