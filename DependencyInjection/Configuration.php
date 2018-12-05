<?php

namespace Leapt\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('leapt_core');
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('leapt_core');
        }

        $rootNode
            ->children()
                ->scalarNode('upload_dir')->defaultValue('%kernel.root_dir%/../web')->end()
                ->arrayNode('google_analytics')
                    ->children()
                        ->scalarNode('tracking_id')->defaultNull()->end()
                        ->scalarNode('domain_name')->defaultValue('auto')->end()
                        ->scalarNode('allow_linker')->defaultValue('false')->end()
                        ->scalarNode('debug')->defaultValue(false)->end()
                    ->end()
                ->end()
                ->arrayNode('google_tags_manager')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('id')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('facebook')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('app_id')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('paginator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('template')->defaultValue('@LeaptCore/Paginator/paginator_default_layout.html.twig')->end()
                    ->end()
                ->end()
                ->arrayNode('recaptcha')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('public_key')->defaultNull()->end()
                        ->scalarNode('private_key')->defaultNull()->end()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->booleanNode('verify_host')->defaultFalse()->end()
                        ->booleanNode('ajax')->defaultFalse()->end()
                        ->scalarNode('locale_key')->defaultValue('%kernel.default_locale%')->end()
                        ->booleanNode('locale_from_request')->defaultFalse()->end()
                        ->arrayNode('http_proxy')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('host')->defaultValue(null)->end()
                                ->scalarNode('port')->defaultValue(null)->end()
                                ->scalarNode('auth')->defaultValue(null)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
