<?php

namespace Snowcap\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SnowcapCoreExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // Handle analytics config
        if(isset($config['google_analytics'])) {
            foreach(array('tracking_id', 'domain_name', 'allow_linker', 'debug') as $option) {
                $container->setParameter('snowcap_core.google_analytics.' . $option, $config['google_analytics'][$option]);
            }
        }
        if(isset($config['google_tags_manager'])) {
            foreach(array('id',) as $option) {
                $container->setParameter('snowcap_core.google_tags_manager.' . $option, $config['google_tags_manager'][$option]);
            }
        }

        // Handle paginator twig extension config
        if(isset($config['paginator'])) {
            foreach(array('template') as $option) {
                $container->setParameter('snowcap_core.paginator.' . $option, $config['paginator'][$option]);
            }
        }
    }
}
